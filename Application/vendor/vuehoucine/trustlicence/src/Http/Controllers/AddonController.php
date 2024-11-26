<?php

namespace Vuehoucine\Trustlicence\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addons = Addon::all();
        return view('trustlicence::addons.index', ['addons' => $addons]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (parse_url(url('/'))['host'] == 'localhost') {
            toastr()->error(__('Addons cannot be installed on local server'));
            return back();
        }
        if (!class_exists('ZipArchive')) {
            toastr()->error(__('ZipArchive extension is not enabled'));
            return back();
        }
        if (!$request->hasFile('addon_files')) {
            toastr()->error(__('Addon files required'));
            return back();
        }
        if (empty($request->purchase_code)) {
            toastr()->error(__('Purchase code is required'));
            return back();
        }
        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $request->purchase_code)) {
            toastr()->error(__('Invalid purchase code'));
            return back();
        }
        $addonZip = $request->addon_files;
        $addonZipFileExt = $addonZip->getClientOriginalExtension();
        if ($addonZipFileExt != "zip") {
            toastr()->error(__('File type not allowed'));
            return back();
        }
        try {
            $uploadPath = vFileUpload($addonZip, 'addons/temp/');
            $zip = new ZipArchive;
            $res = $zip->open($uploadPath);
            if ($res != true) {
                removeFile($uploadPath);
                toastr()->error(__('Could not open the zip file'));
                return back();
            }
            $dir = trim($zip->getNameIndex(0), '/');
            $addonsPath = base_path('addons/temp/');
            $thisAddonPath = base_path('addons/temp/' . $dir);
            if (File::exists($thisAddonPath)) {
                removeDirectory($thisAddonPath);
            }
            $res = $zip->extractTo($addonsPath);
            if ($res == true) {
                removeFile($uploadPath);
            }
            $zip->close();
            if (!File::exists($thisAddonPath . '/config.json')) {
                removeFile($thisAddonPath);
                toastr()->error(__('Config.json is missing'));
                return back();
            }
            $str = file_get_contents($thisAddonPath . '/config.json');
            $json = json_decode($str, true);
            $puchaseValidate = $this->addonPurchaseCodeValidate($request->purchase_code, $json['symbol'], $json['version']);
            if ($puchaseValidate->status != "success") {
                removeDirectory($thisAddonPath);
                toastr()->error($puchaseValidate->message);
                return back();
            }
            if (strtolower(systemInfo()['name']) != $json['script_symbol']) {
                removeDirectory($thisAddonPath);
                toastr()->error(__('Invalid action request'));
                return back();
            }
            if (systemInfo()['version'] < $json['minimal_script_version']) {
                removeDirectory($thisAddonPath);
                toastr()->error(__('Addon require version ' . $json['minimal_script_version'] . ' or above'));
                return back();
            }
            $addonExist = Addon::where('symbol', $json['symbol'])->first();
            if ($addonExist) {
                toastr()->error(__('Addon is already exists'));
                return back();
            }
            if (!empty($json['remove_directories'])) {
                foreach ($json['remove_directories'] as $remove_directory) {
                    removeDirectory($remove_directory);
                }
            }
            if (!empty($json['remove_files'])) {
                foreach ($json['remove_files'] as $remove_file) {
                    removeFile($remove_file);
                }
            }
            if (!empty($json['directories'])) {
                foreach ($json['directories'][0]['assets'] as $assets_directory) {
                    makeDirectory($assets_directory);
                }
                foreach ($json['directories'][0]['files'] as $files_directory) {
                    makeDirectory(base_path($files_directory));
                }
            }
            if (!empty($json['assets'])) {
                foreach ($json['assets'] as $asset) {
                    File::copy(base_path($asset['root_directory']), $asset['update_directory']);
                }
            }
            if (!empty($json['files'])) {
                foreach ($json['files'] as $file) {
                    File::copy(base_path($file['root_directory']), base_path($file['update_directory']));
                }
            }
            if (!empty($json['sql_file'])) {
                if (file_exists(base_path($json['sql_file']))) {
                    DB::unprepared(file_get_contents(base_path($json['sql_file'])));
                }
            }
            $createAddon = Addon::create([
                "api_key" => $puchaseValidate->data->api_key,
                "logo" => url($json['logo']),
                "name" => $json['name'],
                "symbol" => strtolower($json['symbol']),
                "version" => $json['version'],
                'action_text' => $json['action_text'],
                'action_link' => $json['action_link'],
                "status" => 1,
            ]);
            if ($createAddon) {
                removeDirectory($thisAddonPath);
                toastr()->success(__('Addon has been installed successfully'));
                return back();
            }
        } catch (\Exception$e) {
            removeFile($uploadPath);
            removeDirectory($thisAddonPath);
            toastr()->error($e->getMessage());
            return back();
        }

    }

    protected function addonPurchaseCodeValidate($purchaseCode, $symbol, $version)
    {
        return eval(trustHash('U2toQ01XTnRUbTlaV0U1c1VUSTVhMXBUUVRsSlExSjNaRmhLYW1GSFJucGFWVTUyV2tkVk4wTnBRV2RKUTBGblNVTkJaMHBJVGpWaVYwcDJZa05CT1VsRFVucGxWekZwWWpKM04wTnBRV2RKUTBGblNVTkJaMHBJWkd4WmJrNXdaRWRWWjFCVFFqRmpiWGR2U25rNGJrdFVjMHRKUTBGblNVTkJaMGxEUVd0a2JWWjVZekpzZG1KcFFUbEpRMUl5V2xoS2VtRlhPWFZQZDI5blNVTkJaMGxEUVdkSlExSnFZa2RzYkdKdVVXZFFVMEoxV2xoaloxaEZaREZsYm5CeldsVm9NR1JJUW1OUk1uaHdXbGMxTUV0RGF6ZERhVUZuU1VOQlowbERRV2RLU0Vwc1kxaFdiR016VVdkUVUwRnJXVEo0Y0ZwWE5UQk1WRFZ1V2xoUmIwb3lhREJrU0VKNlQyazRkbVJJU2pGak0xSnpZVmRPYkdKdFRteE1iVTUyWWxNNWFHTkhhM1prYWtWMllrZHNhbHBYTldwYVZEbDNaRmhLYW1GSFJucGFWVTUyV2tkVk9VcDVRWFZKUTFKM1pGaEthbUZIUm5wYVZVNTJXa2RWWjB4cFFXNUtibVJzV1c1T2NHUkhWVGxLZVVGMVEybEJaMGxEUVdkSlEwRm5TVU5CWjBsRFVqTmFWMHA2WVZoU2JFbEROR2RLZVZwNlpWY3hhV0l5ZHpsS2VVRjFTVU5TZW1WWE1XbGlNbmRuVEdsQmJrcHVXbXhqYms1d1lqSTBPVXA1UVhWSlExSXlXbGhLZW1GWE9YVkxWSE5MU1VOQlowbERRV2RKUTBGclkyMVdlbU5IT1hWak1sVm5VRk5DY1dNeU9YVllNbEpzV1RJNWExcFRaMnRqYlZaNFpGZFdlbVJETUN0YU1sWXdVVzA1YTJWVFozQkxWSE5MU1VOQlowbERRV2RKUTBKNVdsaFNNV050TkdkS1NFcHNZek5DZG1KdVRteFBkejA5', 3));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function edit(Addon $addon)
    {
        return view('trustlicence::addons.edit', ['addon' => $addon]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Addon $addon)
    {
        if (parse_url(url('/'))['host'] == 'localhost') {
            toastr()->error(__('Addons cannot be installed on local server'));
            return back();
        }
        if (!class_exists('ZipArchive')) {
            toastr()->error(__('ZipArchive extension is not enabled'));
            return back();
        }
        if (!$request->hasFile('addon_files')) {
            toastr()->error(__('Addon files required'));
            return back();
        }
        $addonZip = $request->addon_files;
        $addonZipFileExt = $addonZip->getClientOriginalExtension();
        if ($addonZipFileExt != "zip") {
            toastr()->error(__('File type not allowed'));
            return back();
        }
        try {
            $uploadPath = vFileUpload($addonZip, 'addons/temp/');
            $zip = new ZipArchive;
            $res = $zip->open($uploadPath);
            if ($res != true) {
                removeFile($uploadPath);
                toastr()->error(__('Could not open the zip file'));
                return back();
            }
            $dir = trim($zip->getNameIndex(0), '/');
            $addonsPath = base_path('addons/temp/');
            $thisAddonPath = base_path('addons/temp/' . $dir);
            if (File::exists($thisAddonPath)) {
                removeDirectory($thisAddonPath);
            }
            $res = $zip->extractTo($addonsPath);
            if ($res == true) {
                removeFile($uploadPath);
            }
            $zip->close();
            if (!File::exists($thisAddonPath . '/config.json')) {
                removeFile($thisAddonPath);
                toastr()->error(__('Config.json is missing'));
                return back();
            }
            $str = file_get_contents($thisAddonPath . '/config.json');
            $json = json_decode($str, true);
            $puchaseValidate = $this->addonApiKeyValidate($addon->api_key, $addon->symbol);
            if ($puchaseValidate->status != "success") {
                removeDirectory($thisAddonPath);
                toastr()->error($puchaseValidate->message);
                return back();
            }
            if ($addon->symbol != $json['symbol']) {
                removeDirectory($thisAddonPath);
                toastr()->error(__('Invalid action request'));
                return back();
            }
            if (strtolower(systemInfo()['name']) != $json['script_symbol']) {
                removeDirectory($thisAddonPath);
                toastr()->error(__('Invalid action request'));
                return back();
            }
            if (systemInfo()['version'] < $json['minimal_script_version']) {
                removeDirectory($thisAddonPath);
                toastr()->error(__('Addon require version ' . $json['minimal_script_version'] . ' or above'));
                return back();
            }
            if (!empty($json['remove_directories'])) {
                foreach ($json['remove_directories'] as $remove_directory) {
                    removeDirectory($remove_directory);
                }
            }
            if (!empty($json['remove_files'])) {
                foreach ($json['remove_files'] as $remove_file) {
                    removeFile($remove_file);
                }
            }
            if (!empty($json['directories'])) {
                foreach ($json['directories'][0]['assets'] as $assets_directory) {
                    makeDirectory($assets_directory);
                }
                foreach ($json['directories'][0]['files'] as $files_directory) {
                    makeDirectory(base_path($files_directory));
                }
            }
            if (!empty($json['assets'])) {
                foreach ($json['assets'] as $asset) {
                    File::copy(base_path($asset['root_directory']), $asset['update_directory']);
                }
            }

            if (!empty($json['files'])) {
                foreach ($json['files'] as $file) {
                    File::copy(base_path($file['root_directory']), base_path($file['update_directory']));
                }
            }
            if (!empty($json['sql_file'])) {
                if (file_exists(base_path($json['sql_file']))) {
                    DB::unprepared(file_get_contents(base_path($json['sql_file'])));
                }
            }
            $updateAddon = $addon->update([
                "version" => $json['version'],
                'action_text' => $json['action_text'],
                'action_link' => $json['action_link'],
            ]);
            if ($updateAddon) {
                removeDirectory($thisAddonPath);
                toastr()->success(__('Addon has been updated successfully'));
                return back();
            }
        } catch (\Exception$e) {
            removeFile($uploadPath);
            removeDirectory($thisAddonPath);
            toastr()->error($e->getMessage());
            return back();
        }
    }

    protected function addonApiKeyValidate($api_key, $symbol)
    {
        return eval(trustHash('VTJ0a1IyUXlSbGhrUjNoc1ZUQkZOVk5WVGxOaFIwNUlZa2RhYUUxc1dURlVNMlIyV2pCc1JGRlhaRXBSTUVadVUxVk9VMlZ0VmxoTlYyeHBUVzVrYmxWR1RrSmhNazE2WWtoU1dtSlViSHBVTTJSMldqQnNSRkZYWkVwUk1FWnVVMVZPVTAweGNGaFRibkJvVjBaS2MxTlZVWGRhTWxKWlUyNU9URkV5VGpKVGJteHlUakJPY0ZGWFpFcFJNRVp1VTFWT1Fsb3djRWhVYms1b1ZqRmFNVnBGVGtKUFZXeElUbGQ0YTJWVlNtcFZhazVYVG0xV2RHVkhlRlJUUmtsM1dUQmFORkpIU2toaVIzaHBZbXhHZGxNeFVucFRNR3hFVVZka1NsRXdSbTVUVlU1Q1lUSk9kRlp1YUd0V01WbzJXa1ZPUWs5VmJFUlZiWEJwVWpKNGMxbHROVkprUmtKMFdrZDRhMUV5WkhWWlZXaFRUVWRPU1ZSVVdrMWxWR3QzV1RJMVYyVnRVa2hsU0VKYVRXeGFNVmRVU2xaa1ZtdDVUMWhTVFUxcldqTlpWazAxVFdzeFZFOVlUbWhXTURWeldXMHhUMkpGZDNsVmJYaHJVakJhZDFscmFFNU1NV3haVVc1Q2FFMXNXVEZWUms1cVdqQjRjRkZYZEZwWFJVcDNXVlJLVjA1VmJFUk9SMlJMWlZadmVsZHNaRXRsYlVaWlZXMTRVVlV5VG01VVIyUjJXakJzUkZGWFpFcFJNRVp1VTFWT1Fsb3diRVJSVjNSclRXeGFjRmw2U25OTlJuQlVVVmhXU2xFeVRuUlplazV6WkVac2RFOVlUbEZWTWs1dVZFZHNRbUV5VFhwaVNGSmFZbFJzZWxNeFVucFRNR3hFVVZka1NsRXdSbTVUVlU1Q1lUSk9kRlp1Y0dwU2Vtd3hXWHBLVmxveFFsUlJia1pxVFdwc01WZEVTbE5pUm10NVQxZDBZVlV5WkhKWk1qRlhaVWRTV0ZadWNHdFJla0Z5VjJwS1YwMUdSblJQVjNSc1ZUSmtkMU14VW5wVE1HeEVVVmRrU2xFd1JtNVRWVTVEWlZad1dWVnFSbXBpVkZKdVUydG9TMkpIVFhwUmJscHBZbXMxYzFRell6bFFVVDA5', 4));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Addon $addon)
    {
        return abort(404);
    }
}
