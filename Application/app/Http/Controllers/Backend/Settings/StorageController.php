<?php
namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\StorageProvider;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Storage;
// use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{

    public function index()
    {
        $storageProviders = StorageProvider::all();
        return view('backend.settings.storage.index', ['storageProviders' => $storageProviders]);
    }

    public function updateSettings(Request $request)
    {
        $update = Settings::updateSettings('unaccepted_file_types', $request->unaccepted_file_types);
        if ($update) {
            toastr()->success(__('Updated Successfully'));
            return back();
        }
    }

    public function edit($id)
    {
        $storageProvider = StorageProvider::findOrFail($id);
        abort_if($storageProvider->symbol == "local", 404);
        return view('backend.settings.storage.edit', ['storageProvider' => $storageProvider]);
    }

    public function update(Request $request, $id)
    {
        $storageProvider = StorageProvider::find($id);
        if (!$storageProvider && $storageProvider->symbol == "local") {
            toastr()->error(__('Provider not exists'));
            return back();
        }
        foreach ($request->credentials as $key => $value) {
            if (!array_key_exists($key, (array) $storageProvider->credentials)) {
                toastr()->error(__('Credentials parameter error'));
                return back();
            }
        }
        if ($request->has('status')) {
            foreach ($request->credentials as $key => $value) {
                if (empty($value)) {
                    toastr()->error(str_replace('_', ' ', $key) . __(' cannot be empty'));
                    return back();
                }
            }
            $request->status = 1;
        } else {
            if (env('FILESYSTEM_DRIVER') == $storageProvider->symbol) {
                toastr()->error(__('Default provider cannot disabled'));
                return back();
            }
            $request->status = 0;
        }
        $update = $storageProvider->update([
            'status' => $request->status,
            'credentials' => $request->credentials,
        ]);
        if ($update) {
            $storageProvider->handler::setCredentials($storageProvider);
            toastr()->success(__('Updated Successfully'));
            return back();
        }

    }

    public function setDefault(Request $request, $id)
    {
        $currentStorageProvider = StorageProvider::where('symbol', env('FILESYSTEM_DRIVER'))->first();
        $uploads = Upload::where([['storage_provider_id', $currentStorageProvider->id], ['created_at', '<', Carbon::now()->addHour()]])->get();
        if ($uploads->count() > 0) {
            toastr()->error(__('Some files are being uploaded to the current storage provider please try again after 1 hour'));
            return back();
        }
        $storageProvider = StorageProvider::findOrFail($id);
        if (!$storageProvider->status) {
            toastr()->error($storageProvider->name . __(' status is disabled'));
            return back();
        }
        setEnv('FILESYSTEM_DRIVER', $storageProvider->symbol);
        toastr()->success(__($storageProvider->name . ' is now default storage'));
        return back();
    }

    public function storageTest($id)
    {
        $storageProvider = StorageProvider::find($id);
        if (!$storageProvider && $storageProvider->symbol == "local") {
            toastr()->error(__('Provider not exists'));
            return back();
        }
        if (!$storageProvider->status) {
            toastr()->error($storageProvider->name . __(' status is disabled'));
            return back();
        }
        try {
            // var_dump($storageProvider->symbol);
            
            $upload = Storage::disk('b2')->put('test.txt', 'Contents');
            
            if ($upload) {
                Storage::disk($storageProvider->symbol)->delete('test.txt');
                toastr()->success($storageProvider->name . __(' Connected Successfully'));
                return back();
            } else {
                toastr()->error($storageProvider->symbol . __(' Connection error2'));
                return back();
            }
        } catch (\Exception$e) {
            toastr()->error($storageProvider->name . __(' Connection error1'));
            return back();
        }

    }
}
