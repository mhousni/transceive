<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\Folder;
use App\Models\TransferFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransferMail;

// use Illuminate\Support\Facades\Log;

class WorkspaceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('starred')) {
            $q = $request->input('starred');
            $transfersFiles = TransferFile::where([['starred',   true ], ['user_id', userAuthInfo()->id]])
                // ->OrWhere([['archived', 'like', '%' . $q . '%'], ['user_id', userAuthInfo()->id]])
                // ->withCount('transferFiles')
                // ->orderbyDesc('id')
                ->paginate(20);
            // $transfersFiles->appends(['q' => $q]);
        }else if($request->input('archived')) {
            $q = $request->input('archived');
            $transfersFiles = TransferFile::where([['archived',   true ], ['user_id', userAuthInfo()->id]])
            // ->OrWhere([['archived', 'like', '%' . $q . '%'], ['user_id', userAuthInfo()->id]])
            // ->withCount('transferFiles')
            // ->orderbyDesc('id')
            ->paginate(20);
            // $transfersFiles->appends(['q' => $q]);
        }else {
            //$transfers = Transfer::where('user_id', userAuthInfo()->id)->withCount('transferFiles')->orderbyDesc('id')->paginate(20);
            $transfersFiles = TransferFile::where('user_id', userAuthInfo()->id)->orderbyDesc('id')->paginate(20);
        }
        $activeTransfersCount = TransferFile::where([['user_id', userAuthInfo()->id]])
            // ->OrWhere([['user_id', userAuthInfo()->id], ['expiry_at', null], ['status', 1]])
            ->count();
        $starredTransfersCount = TransferFile::where([['user_id', userAuthInfo()->id],  ['starred', true]])->count();
        $archivedTransfersCount = TransferFile::where([['user_id', userAuthInfo()->id], ['archived', true]])->count();
       
        $folders = Folder::where([['user_id', userAuthInfo()->id] ])->get();

        return view('frontend.user.workspace.index', [
            'transfers' => $transfersFiles,
            'folders' => $folders,
            'activeTransfersCount' => $activeTransfersCount,
            'starredTransfersCount' => $starredTransfersCount,
            'archivedTransfersCount' => $archivedTransfersCount,
        ]);
    }

    public function show($unique_id)
    {
        $transfer = Transfer::where([['unique_id', $unique_id], ['user_id', userAuthInfo()->id]])->with('transferFiles')->withCount('transferFiles')->firstOrFail();
        return view('frontend.user.transfers.show', ['transfer' => $transfer]);
    }

    public function update(Request $request, $unique_id)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['max:255'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        $transfer = Transfer::where([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', '>', Carbon::now()]])
            ->orWhere([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', null]])
            ->withCount('transferFiles')
            ->first();
        if (is_null($transfer)) {
            toastr()->error(lang('Transfer not exists or expired', 'user'));
            return back();
        }
        $subscription = subscription();
        if (!$subscription->plan->transfer_notify && !$subscription->plan->transfer_password) {
            return back();
        }
        if ($request->has('transfer_password') && !is_null($request->transfer_password)) {
            if ($subscription->plan->transfer_password) {
                $request->transfer_password = Hash::make($request->transfer_password);
            } else {
                toastr()->error(lang('Setting password feature not available for your subscription', 'upload zone'));
                return back();
            }
        } else {
            $request->transfer_password = null;
        }
        $request->download_notify = 0;
        $request->expiry_notify = 0;
        if ($request->has('download_notify') or $request->has('expiry_notify')) {
            if ($subscription->plan->transfer_notify) {
                $request->download_notify = ($request->has('download_notify')) ? 1 : 0;
                $request->expiry_notify = ($request->has('expiry_notify')) ? 1 : 0;
            } else {
                toastr()->error(lang('The notify on download and expiry feature not available for your subscription', 'upload zone'));
                return back();
            }
        }
        $request->downloaded_at = ($request->download_notify) ? null : $transfer->downloaded_at;
        $transferUpdate = $transfer->update([
            'password' => $request->transfer_password,
            'download_notify' => $request->download_notify,
            'expiry_notify' => $request->expiry_notify,
            'downloaded_at' => $request->downloaded_at,
        ]);
        if ($transferUpdate) {
            toastr()->success(lang('Transfer updated successfully', 'user'));
            return back();
        }
    }

    public function downloadFiles($unique_id, $id)
    {
        $transfer = Transfer::where([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', '>', Carbon::now()]])
            ->orWhere([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', null]])
            ->withCount('transferFiles')
            ->firstOrFail();
        $transferFile = TransferFile::where([['id', unhashid($id)], ['user_id', userAuthInfo()->id], ['transfer_id', $transfer->id]])->firstOrFail();
        try {
            $handler = $transferFile->storageProvider->handler;
            $download = $handler::download($transferFile);
            if ($transferFile->storageProvider->symbol != "local") {
                return redirect($download);
            } else {
                return $download;
            }
        } catch (Exception $e) {
            toastr()->error(lang('There was a problem while trying to download the file', 'download page'));
            return redirect()->route('user.dashboard');
        }
    }

    public function deleteFiles(Request $request, $unique_id, $id)
    {
        $transfer = Transfer::where([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', '>', Carbon::now()]])
            ->orWhere([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', null]])
            ->withCount('transferFiles')
            ->first();
        if (is_null($transfer)) {
            toastr()->error(lang('Transfer not exists or expired', 'user'));
            return back();
        }
        if ($transfer->transfer_files_count < 2) {
            toastr()->error(lang('Transfer must have one file at least', 'user'));
            return back();
        }
        $transferFile = TransferFile::where([['id', unhashid($id)], ['user_id', userAuthInfo()->id], ['transfer_id', $transfer->id]])->first();
        if (is_null($transferFile)) {
            toastr()->error(lang('Transfer file not exists', 'user'));
            return back();
        }
        $handler = $transferFile->storageProvider->handler;
        $deleteFile = $handler::delete($transferFile->path);
        if ($deleteFile) {
            $transferFile->delete();
            toastr()->success(lang('File deleted successfully', 'user'));
            return back();
        }
    }


    public function setStarredFiles( Request $request) {
        $unique_id = $request->get('unique_id');

        $transfer = TransferFile::where([['id', $unique_id], ['user_id', userAuthInfo()->id]])
            //->orWhere([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', null]])
            // ->withCount('transferFiles')
            ->first();
            if (is_null($transfer)) {
                toastr()->error(lang('Transfer not exists or expired', 'user'));
                return response()->json([
                    "status" => false,
                    "unique_id" => $unique_id,
                ]);
                // return back();
            }
                $transferUpdate =  $transfer->update([
                    'starred' => !$transfer->starred,
                ]);
                if ($transferUpdate) {
                    return response()->json([
                        "status" => true,
                        "unique_id" => $unique_id,
                        "okay" => "Transfer updated successfully"
                    ]);
                }
       

    }

    public function renameFile( Request $request) {
        $unique_id = $request->get('unique_id');
        $new_name = $request->get('new_name');

        $transferFile = TransferFile::where([['id', $unique_id], ['user_id', userAuthInfo()->id]])
            //->orWhere([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', null]])
            // ->withCount('transferFiles')
            ->first();
            if (is_null($transferFile)) {
                toastr()->error(lang('Transfer not exists or expired', 'user'));
                return response()->json([
                    "status" => false,
                    "unique_id" => $unique_id,
                ]);
                // return back();
            }
                $transferUpdate =  $transferFile->update([
                    'name' => $new_name,
                ]);
                if ($transferUpdate) {
                    return response()->json([
                        "status" => true,
                        "unique_id" => $unique_id,
                        "okay" => "Transfer updated successfully"
                    ]);
                }
       

    }

    public function archiveFile(Request $request)
    {
        $unique_id = $request->get('unique_id');
        $transfer = TransferFile::where([['id', $unique_id], ['user_id', userAuthInfo()->id]])
            // ->orWhere([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', null]])
            ->first();
        if (is_null($transfer)) {
            return response()->json([
                "status" => false,
                "unique_id" => $unique_id,
                "message" => lang('Transfer not exists or expired', 'user')
            ]);
            // toastr()->error(lang('Transfer not exists or expired', 'user'));
            // return back();
        }
        

        $transferUpdate =  $transfer->update([
            'archived' => !$transfer->archived,
        ]);
        if ($transferUpdate) {
            return response()->json([
                "status" => true,
                "unique_id" => $unique_id,
                "okay" => "Transfer archived successfully"
            ]);
        }


        // $handler = $transferFile->storageProvider->handler;
        // $deleteFile = $handler::delete($transferFile->path);
        // if ($deleteFile) {
        //     $transferFile->delete();
        //     toastr()->success(lang('File deleted successfully', 'user'));
        //     return back();
        // }



    }

    public function archiveFiles(Request $request)
    {
        $ids = $request->get('ids');
        
        $transfer = TransferFile::whereIn('id', $ids)
                ->where('user_id', userAuthInfo()->id)
                ->get();
            // ->orWhere([['unique_id', $unique_id], ['user_id', userAuthInfo()->id], ['expiry_at', null]])
            
            //dd($transfer);

            // return response()->json([
            //     "status" => $transfer,
            //     "unique_ids" => $ids,
            // ]);
        if (is_null($transfer)) {
            return response()->json([
                "status" => false,
                "unique_ids" => $ids,
                "message" => lang('Transfer not exists or expired', 'user')
            ]);
            // toastr()->error(lang('Transfer not exists or expired', 'user'));
            // return back();
        }
        
        
        foreach ($transfer as $tr) {
            $tr->update([
                'archived' => !$tr->archived,
            ]);
        }

        return response()->json([
            "status" => true,
            "okay" => "Transfer archived successfully"
        ]);


    }

    public function shareFile(Request $request)
    {
        $unique_id = $request->get('unique_id');
        $emails = $request->get('emails');
        $message = $request->get('message');

        $mhIsPublic = $request->get("mhIsPublic");
        $mhCanModify = $request->get("mhCanModify");
        $mhWithPass = $request->get("mhWithPass");
        $mhDateEx = $request->get("mhDateEx");
        
        $transferFile = TransferFile::where([['id', $unique_id], ['user_id', userAuthInfo()->id]])
        ->first();
        $transfer = Transfer::find($transferFile->transfer_id);
        if($mhWithPass =! null) { 
            $mhWithPass = Hash::make($mhWithPass); }
        else {
            $mhWithPass = $transfer->password;
        }
        if($mhCanModify =! null) {
            $mhCanModify = $mhCanModify;
        }else {
            $mhCanModify = $transferFile->can_edit;
        }
        if($mhIsPublic =! null) {
            $mhIsPublic = $mhIsPublic;
        }else {
            $mhIsPublic = $transferFile->is_public;
        }

        if($mhDateEx != null) {
            $mhDateEx = $mhDateEx;
        }else {
            $mhDateEx = $transfer->expiry_at;
        }
        $transferFileUpdated =  $transferFile->update([
            //'archived' => !$transfer->archived,
            'is_public' =>  $mhIsPublic ,
            'can_edit' => $mhCanModify
        ]);
        
        // dd($transfer);

        $emailList = explode(',',$emails);
        $newList = array_merge((array)($transfer->emails), (array)($emailList));

        $transferUpdated =  $transfer->update([
            'expiry_at' => $mhDateEx,
            'emails' => (object) $newList,
            'password' => $mhWithPass
        ]);

        
        if($transferFileUpdated && $transferUpdated) {
            // $transferSubject = ($createTransfer->subject) ? ' (' . $createTransfer->subject . ')' : '';
            $subject = mailTemplates('You have received some files', 'transfer files notification');
            $details = [
                'sender' => $transfer->sender_name ?? $transfer->sender_email,
                'subject' => $subject,
                'message' => $message,
                'password' => $mhWithPass,
                'total_files' => 1,
                'total_size' => formatBytes($transferFile->size),
                'expiry_at' => ($transfer->expiry_at) ? vDate($transfer->expiry_at) : null,
                'transfer_link' => route('transfer.download.index', $transferFile->link),
                // 'files' => $files,
            ];
            foreach ($newList as $key => $value) {
                Mail::to($value)->send(new TransferMail($details));
            }
            // Log::info($transferUpdated);
        }

        // if (settings('mail_status')) {
        //     $transferSubject = ($createTransfer->subject) ? ' (' . $createTransfer->subject . ')' : '';
        //     $subject = mailTemplates('You have received some files', 'transfer files notification') . $transferSubject;
        //     $details = [
        //         'sender' => $createTransfer->sender_name ?? $createTransfer->sender_email,
        //         'subject' => $subject,
        //         'message' => $createTransfer->message,
        //         'password' => $password,
        //         'total_files' => count($files),
        //         'total_size' => formatBytes($transferSize),
        //         'expiry_at' => ($createTransfer->expiry_at) ? vDate($createTransfer->expiry_at) : null,
        //         'transfer_link' => route('transfer.download.index', $createTransfer->link),
        //         'files' => $files,
        //     ];
        //     foreach ($createTransfer->emails as $key => $value) {
        //         Mail::to($value)->send(new TransferMail($details));
        //     }
        // }

      
        
       

        return response()->json([
            "status" => true,
            "data" => [
                "unique_id" => $unique_id,
                "emails" => $emails,
                "message" => (object) $newList,
                "mhIsPublic" => $mhIsPublic,
                "mhCanModify" => $mhCanModify,
                "mhWithPass" => $mhWithPass,
                "mhDateEx" => $mhDateEx
            ],
            "okay" => "Transfer share successfully"
        ]);


    }

    public function createFolder(Request $request)
    {
        // $unique_id = $request->get('unique_id');
        $folderName = $request->get('folder_name');
        $ids = $request->get('ids');
        $createFolder = Folder::create([
            'name' => $folderName,
            'user_id' => userAuthInfo()->id,
        ]);

        if( $createFolder) {
            return response()->json([
                "status" => true,
                "okay" => "Folder created successfully"
            ]);
        }
        if($createFolder) {


            $transfer = TransferFile::whereIn('id', $ids)
                ->where('user_id', userAuthInfo()->id)
                ->get();

            foreach ($transfer as $tr) {
                $tr->update([
                    'folder_id' => $createFolder->id,
                ]);
            }
            return response()->json([
                "status" => true,
                "data" => [
                   "folder_name" => $folderName,
                ],
                "okay" => "Folder created successfully"
            ]);
        }

        


    }
    public function moveFileToFolder(Request $request)
    {
        // $unique_id = $request->get('unique_id');
        $folderId = $request->get('folder_id');
        $ids = $request->get('ids');
       

        $transfer = TransferFile::whereIn('id', $ids)
                ->where('user_id', userAuthInfo()->id)
                ->get();

            foreach ($transfer as $tr) {
                $tr->update([
                    'folder_id' => $folderId,
                ]);
            }
            return response()->json([
                "status" => true,
                "data" => [
                //    "folder_name" => $folderName,
                ],
                "okay" => "File folder updated successfully"
            ]);

        


    }

    
}
