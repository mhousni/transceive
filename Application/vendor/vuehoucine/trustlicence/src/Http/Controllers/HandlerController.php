<?php

namespace Vuehoucine\Trustlicence\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HandlerController extends Controller
{
    public function redirect()
    {return redirect()->route('install.requirements');}
    public function redirectToDatabase()
    {return redirect()->route('install.information.database');}
    protected function extensionsArray()
    {
        $extensions = phpExtensions();
        $extensionsArray = [];
        foreach ($extensions as $extension) {$extensionsArray[] = extensionAvailability($extension);}
        return $extensionsArray;
    }
    public function requirements()
    {
        if (env('VIRONEER_REQUIREMENTS')) {return redirect()->route('install.permissions');}
        $error = 0;
        $extensions = phpExtensions();
        if (in_array(false, $this->extensionsArray())) {
            $error = 1;
        }
        
        return view('trustlicence::requirements', ['extensions' => $extensions, 'error' => $error]);
    }
    public function requirementsAction(Request $request)
    {
        if (in_array(false, $this->extensionsArray())) {return redirect()->route('install.requirements');}
        \Artisan::call('key:generate');
        setEnv('APP_ENV', 'production');
        setEnv('VIRONEER_REQUIREMENTS', 1);
        return redirect()->route('install.permissions');
    }
    protected function permissionsArray()
    {
        $permissions = filePermissions();
        $permissionsArray = [];
        foreach ($permissions as $permission) {
            $permissionsArray[] = filePermissionValidation($permission);
        }
        return $permissionsArray;
    }
    public function permissions()
    {
        if (env('VIRONEER_FILEPERMISSIONS')) {return redirect()->route('install.licence');}
        if (!env('VIRONEER_REQUIREMENTS')) {return redirect()->route('install.requirements');}
        $error = 0;
        $permissions = filePermissions();
        if (in_array(false, $this->permissionsArray())) {
            $error = 1;
        }
        return view('trustlicence::permissions', ['permissions' => $permissions, 'error' => $error]);
    }
    public function permissionsAction(Request $request)
    {
        if (in_array(false, $this->permissionsArray())) {return redirect()->route('install.permissions');}
        setEnv('VIRONEER_FILEPERMISSIONS', 1);
        return redirect()->route('install.licence');
    }
    public function licence()
    {
        if (env('VIRONEER_LICENCE')) {return redirect()->route('install.information.database');}
        if (!env('VIRONEER_FILEPERMISSIONS')) {return redirect()->route('install.requirements');}
        return view('trustlicence::licence');
    }
    public function licenceAction(Request $request)
    {
        return eval(trustHash('VjFaYWExZHNiM2RrUld4U1lsZG9jVmx0Y3pGak1XUkZWR3hrYkZZd2NFVlpWVkpIWVcxS1dWcElXbFJpYlhNd1dXMTBNRlpIU2toVGEzUk9ZbGRuZVZkWWNFOVZiVXB6WVVaU1VsWXlhRkpXVkVKSFpGWnNjbGw2Vm1oaVIzaFpXbFZqTldGVk1IZE9TR1JVWW0xNGVWZHFTbGROTWtsNVdrVndVazFGV25WVk1WWlBVV3h2ZDJKRlVsSldNbEpMVlZSQ1MyUXhaSFJpUlVwcFRWaENXVlJXYUd0aE1VNUlaRWhhVkdFeWFFeFphMlJQVjFaYWRHVkhjRTVOVlZsM1ZsVmpNVkV3TVZoVWJsSlZZbFJzWVZZd1ZURmpNV1JGVTJzNWEySklRa2xXYkdoRFZFWlZkMU5VVGxwTmFrWllWRlZrVTFkV1RuVldhM0JVVWxoQ2VsWXlkR3RqTWxaWFkwWm9WV0ZyU2sxVlZFb3dUVVpXU0UxVmRHaFNiWFExV1hwSk5WUkdWalpSV0VwaFVrVndlbFJWWkVkU2JGcDFZa2R3YVZaSGR6RlhWRTV6WWpGc2NtSXphRkpoYTFweFdXeFZNV1JzWkZkaFJUbHBVbGQ0U1ZaSE5XRlpWa2w0Vm0wMVdsWnRhRTlYYWtwUFpFWmFkV0ZIZEZkTmJtY3hWako0YTFWdFNuTmhSbEpvWlcxU2RGWldZelZpYkU1V1ZHdEtZVTFIZUVWVlZtUnJVMnhGZDFKdE5WUldWVFZFV1RCYWQyTkdSbGhQVlhCWVVsVnZNVll5ZUd0aE1YQjBVMnhvVTJGclNtRlVWekZyWkd4T1dHRjZWbUZXV0ZKSVdrVmtiMVJXV1hoaVJFNVZVbXhLZWxkclpGZE9iVVpGWWtWNFZtVnJTakpWZWtKaFRVZEdSbVZHYUZoWFIxSk9WbXRrTkdFeGNGbGpSazVRVmxoU1ZWUlZhRU5pUjFaeVRrUldWRTFHYjNkWlZWWTBWMFprV1ZwRk1WZFNNMmh5VjJ4b2QxSnRWbGhYYkZKb1RXNVNUbFJYTVRCalJsSkdWR3RLYUUxck5UQldiVFZ2WVRGWmVGZHFXbUZTVlRFelUzcEtUMU5XV25WaVJuQk9ZbGRvZGxkWWNFdFdNWEJ6WVROc1VGWXpVbWhXVkVvd1pERk9WbUZFUW14V2JrSmFWbGR3UjJGdFNsVlZiVFZhVFdwR1dGbFVTa2RYVms1MFpVWndUazFWV2pKVmVrWk9aREJ6ZUdKSVVsTmlXRUp2V2xaa2EyUXhVa2RWVkVaT1RXdGFXbFpYTURWVk1XUkdZMFJXV21Gck5VeGFWM1F3VWpKTmVVNVdVbGRsYkZZMVZqRmFhMDVIVGtkalJWSlNZbTFTY2xZd1ZuZGpWbXhXV2tWa2JHSklRbFZWVnpGM1lWVXhjMU51VGxSaGExWXpXVEJWTkdWck1VWmtSWEJTVFVWYWRWVXhWazlSYkc5M1lrVlNVbFl5VWt0VlZFSkhZbXh3Um1GRmRFOVdWM2hLV1hwQ01GTnNSWGRTYlRWVVZsVTFRMWRxUW5OU1JrWllXa1Z3VWsxRlduVlZNVlpQVVd4dmQySkZVbFpoZWxaVlZtMTRTMVZzWkZkaFJrNXBUVWQ0UmxSVlpHdFhiVXBXVjJwYVdHSkdhekZhUkVaelYxWldkRTlWZUZKTmJFWTFWako0YWsxWFJYbFRXSEJVVjBad2NGVnFRbUZPVm1SWFlVZEdhVkl3Y0VWVU1XTjRZVzFLVmxkcVFsaGlSMmh5V2tjeFQyUkhUWHBYYXpGb1pXdGFURlp0ZEU5aGJVNUdUMVJPYVUxdFVrdFZWRUpIWW14T1ZsUnJTbUZOUjNoRlZWWmthMU5zUlhkU2JUVlVWbFUxUTFkcVNrZFhSbVJZV2tWNFVrMUdjREJYVmxwclRrZEtSMW96YkZkaGJFcHZWakJWTUdReGEzcGlSelZvVFZaS1YxbHJXbGRXYkVsM1YxaGtXbFpWTlhsWk1GWnpVMWRTU1dKSFJsaFNhMncwVjFSSmQwMUdiM2xVYmxKWFlsaFNiMVl3Vm5kak1XUlZWR3hLYVUxSVVsVlVWVTR3VjIxS1ZsZHVSbHBYUjNoMVdUQldORlpWTlZWVWJXaFlVbXR3TWxaWGVHOVRNbFpZVTFod1ZHSnVRazFWYlRWUFpGWmFWVk5VVm10U2JrSmFWbGN3TldGR1dqWldibFpVVmxkb2NsbHJaRXRrVmxaWVdrZDBUazFJUVhsWFZ6QjRZVzFLYzJGR1VtaGxiVkowVmxaak5XSnNUbFpVYTBwaFRVZDRSVlZXWkd0VGJFVjNVbTAxVkZaVk5VTlhha0p6VWtaR1dGcEhhRmROVjNoMVZYcENUMUl5VGtoVVdIQlZZbGhvY2xWVVNtOWtSbXhYV2tSU2FWSllVa1ZXVjNNeFZrWmFjMU5zU2xoV2JXaFVXV3BDTUZZeVRYcGFSbXhXVFc1U00xVXhWbTlOUjFaWFkwWnNWbUZyV25GWmJGSlRZbXhyZVUxV1pHaE5hMXBhVlRJeE5GZHJNSGhTYmxwVVRWVXhNMU42Um5Oa1JrcDBZMGRvYkZZeVVqTldSVnBTVFZVd2VWSnNiRlppVkd4VVZqQldkMDVXYkhGVWEzUnNZVE5TU0ZsNlNURldhekZ4WWtSQ1dHSkhhRlJaYWtwSFYwVTFXRTVWY0ZSU01VcDZWMWN3TVZWc2IzbFZXSEJVWW14d2NGbHNaRTlrVm1SSFZHNUtUMDFzY0ZOWmFrcHJVMnhGZDFKdE5WUldWVFZEVjJwQ2MxSkdSbGhhUlhCU1RVVmFkVlV4Vms5UmJHOTNZMFZvVjJGc1NuRlZhazV2VFd4a2NscEdaRlZXTUZwWlZHeGtORk5zU2tWUmJUVllZa2RvZGxwRVNrdFRSVGxZWkVkR1ZrMXRVakZXUlZwUFlXMU5kMkpGYUZoaWEwcHdWV3BHVjJSc1RuSldXR2hVWWtad1NGVlhNVzloTVVsNVdraGtWMDB6UWtSWGExWXdWbGROZDJSRmNGSk5SVnAxVlRGV1QxRnNiM2RpUlZKU1ZqSlNTMVZVUWtkaWJFNVdWR3RLWVUxSGVFbFphMk40VTJ4RmVWcElTbGhpUjJoMldrUktTMU5GT1Zoa1IwWlhWMGRvTTFkWE1IaFdNV3h5VFZWa1RsSXlVa3RXYTFKRFlteHNObFJ1VG14aVZrcEpWbTAxVTFaR1dqWldibEphWW0xNGRWa3dXbXRPVm5CSlZteHdWMlZyV25wVk1uQkdaREpPUm1KRmJHdFRSM2hvVmpCYVNtVkdhM2xOUkVKaFRXczFNRlp0TVRCaFJtUkdZMGhPV0ZaRk5WTlpha0l3VmtVeFJHUkdjR2xXVm5CNFYxWm9jMkp0VGtabFJsWlBWa1UxYjFZd1drdGtiRlp6WVVWMGJGWXdiRFpWTWpWM1ZFWktkVlJ1VmxkV1JXc3hXa1ZhZDFkV1ZuUlBWMmhYWld4YU1WVXhWbTloTWtwSVUyNVdWbFl5VW5KVVZFSjNUV3hzZEUxWGNHbGlSMmhWV1Zod2EySldWbGhQVnpWVVZsVTFRMWRxUW5OU1JrWllXa1Z3VWsxRlduVlZNVlpQVVd4dmQySkZVbEpXTWxKdlZtcEdjMkpzVFhkVWJFNXBVakZhU2xWWE5VOWhWVEZ6VTI1T1YxSlhVbnBhUmxwM1ZqSk5lbUZHYkZaTlJWcDJWbFZhVDFFeVZuUldiR3hWWVd0S2FGWnVjRWRUTVd4MFRWZEdhMkV6VWtWWmEyUkxVekF3ZUdOSVRscE5hbFpRV1RCa1NtVlZOVWhPVm14V1RXNVNkVmRzVWs5VE1rcElWV3RzVjJKdGVIQlpWbFpMVGxaa2MxcEdUbXBTTURVd1ZtMHhkMkV4UlhsYVNHUlZVbXhGZUZsV1duTlhSbEoxVTJ0NFVrMXVVWGRXVldNeFlUSk9TRlZyYUdoU1ZuQnhXVzEwZDAxc2EzbE9WVFZwVFZkUk1WZHJXbE5oVlRGeFVtNU9ZVkpYVW5aWk1HUkxaRVpyZVZwSGRFNWlSbTk0VjJ0V1QxRXdNSGxVYmxKUVYwWmFhRnBXWkZOaE1VMTRWV3BDVUZaVk5YZFZWbVJyVTJ4RmQxSnROVlJXVlRWRFYycENjMUpHUmxoYVJYQlNUVVZhZFZVeFZrOVJiVVY1Vkd0c1YySnRlR0ZVVnpGdllqRnNObE5zWkZOU01HdzFWbGN4TkZOc1NrVlJiVFZVWVRKb1RGbHJaRTlYVmxwMFpVZHdUazFWV1hkV1ZXTXhVVEF4V0ZSdVVsVmlWR3hoVmpCVk1XTXhaRVZUYXpscllraENTVlpzVW10U1IwWldVbTAxVkZaVk5VTlhha0p6VWtaR1dGcEZjRkpOUlZwMVZURldUMUZzYjNkaVJWSlNWak5TY1ZSVVNqUk5SbVJZVFVSV2FrMUhlRVpVVldSclV6RkplRmRVUWxwTlIxRXdXa2Q0ZDFOR1duSk5WMmhYWld4YWVsWnFUbmRSTVhCR1QxUk9hVTF0VWt0VlZFSkhZbXhPVmxSclNtRk5SM2hGVlZaa2ExTnNSWGRTYlRWVVZsVTFRMWRxUW5kVFZuQklaVVp3YVdGNlZqTlhhMVpyVm14dmVGRnNVbEpoYTFweFdXeG9hMlJzVG5WaGVsSnBZVE5TVmxsNlFqQlRiRVYzVW0wMVZGWlZOVU5YYWtKelVrWkdXRnBGY0ZKTlJWcDFWVEZXVDFGc2IzZGlSVkpXWVd0d2FGWXdWbmRPYkd4WFdYcFdhMVpYZUVaVVZXUnJVekZKZUZkVVFscE5SMUV3V2tkNGQxTkdXbkpOVjJoWFpXeGFlbFpxVG5kU01YQkdUMVJPYVUxdFVrdFZWRUpIWW14T1ZsUnJTbUZOUjNoRlZWWmthMU5zUlhkU2JUVlVWbFUxUTFkcVFuZFRSbEoxVkcxb1YwMVdiM2hYYTFaUFVXczVWbUpGYUU5V00yaHlXbFpXUzJGc1ZuRlViR1JQWWxaYU1GcFZaRFJXUms1SFUxaGtXazFHYnpCVmEyUkxVMGRLU0dWSGJHbGlSVm95VlhwR1UyVnNUWGRpUlZKU1ZqSlNTMVZVUWtkaWJFNVdWR3RLWVUxSGVFVlZWbVJyVTJ4RmQxSnROVlJXVlRWVVdsWmFkMWRXU25GU2JVWllVbFJTTTFVeFZsSmtNVzkzWTBWb1ZXSnJOVzlXYWtaaFRWWndSbFJZWkV4TlZ6azFWbTF3UTFSR1JYbFZibHBoVWxkb1ZGcEVTazlPYlVsNlYyc3hUazFWYnpGWGExcHZWREF4U0ZOcmFHbFNNMEpvVm01d1YyTldaSE5VVkVab1lsVnNOVlJWYUdGWGJHUkdVMjVrVlZKRk5XRmFWVll6WlZkV1NWRnNjRTVpUm05NFZqRlNTMVpyZDNsVWEyeFhZbTE0WVZSWE1XOWlNV3cyVTJ4a1UxSXdiRFZXVnpFMFZWWlZlVlJ0TlZWU01uaERXVlJLVDFOV1duVmlSbkJPWWxkb2RsZFljRXRXTVVwSVUxaHNWbUpZYUV0VldIQlRZbXhPZFdKSFJrNU5XRUpaVlRJMWQyRkdaRWRUYms1V1VtczFjVmRxUWpSalJrWllaRWQwVG1KR2NIZFhXSEJMWXpBeFIyTkdVbEpYUmxwTFZWUktUMlJHYkRaVWJrNXJVbTE0TUZReGFFOVZWbFY1VkcwMVZWSXllRU5aVkVwT1pXMUtTVlZzY0dsV1IzZzJWVEZXVGsxR2IzZGpSRlpZWVd0d2FGWXdWbmRPYkd4WFdYcFdhMVpyU2xWWFZFcHJWRmRHVmxKdVNtRlNla1pZV2xaa1RtVlhTa2xYYld4b1ZqTk5lbFZVU25OUmJHOTNZa1ZTVWxZeVVrdFZWRUpIWW14T1ZsUnJTbUZOUjNoRlZWWmthMU5zUlhkU2JrcGFUV3BHV0ZwWE1VOVRSVGxaVm0xd1RtSkdXblZXVlZwUFVUSk9XRlJZYkZCWFJscGFWRmQ0UzJNeFpGVlRWRlpvVFZoQ1ZWZHFTakJoYlVwWFYycFNZVkp0VWxoYVZ6RlRVa1V4UkdSSFJrNWlSbXd6VmxaamQwNVhSWGxXYkZKaFRUQktUVlpyYUU5VVJrNVdWR3RLWVUxSGVFVlZWbVJyVTJ4RmQxSnROVlJXVlRWRFYycENjMUpHUmxoYVJYQlRUVzVvTUZVeFZrOWliVVY1Vkc1U1YySnVRbkZWYm5CelRWWnNObE5zV210U2Ewb3hWa2R3UTFkc1pFZFRXR2hhVFRKNFExUXhXa05XUmtaWVlrZEdXRkpZUVRGWFYzQlBVMjFHVm1SR1VsSmhiVkp4V1d4YVdtUXhjRWRoUlhSclZsZDRTbFV5TVRSWlZrbDVaVVJXV0dKSFVsQlVWVll3VWtkRmVsVnNSbWxXV0VKMlZqRlNTMlZ0U1hka1JsSk9VVE5TY2xSWE1UTmtNV3hXVm14a2JGWXdOVEJVTVdoellXMVdXR0ZIYkZSaE1taE1XV3RrVG1Wc1JuVlhiV3hwWVhwV2VsWkZXbEpOVjFKSFkwWnNWV0p1UW1GV2FrcFRZekZrUjFSdVNrOU5iSEJUV1dwS2ExTnNSWGRTYlRWVVZsVTFRMWRxUW5OU1JrWllXa1Z3VWsxRlduVlZNVlpQVVd4dmVWSnNhRmhXTWxKTlZWUkdTMDVXWkhOaFJUbHJUV3RzTlZSc2FIZFpWbFkyVVZoS1dtVnJOVlJaVldSVFUxWmFkV05GY0ZOU1JVVXhWVEZXVDFNeVZuUlZiR2hWWWxoQ2FGWXdWVEZPYkU1WVlraEtZVTFzV1hwWmFrcHJVMnhGZDFKdE5WUldWVFZEVjJwQ2MxSkdSbGhhUlhCU1RVVmFkVlV4Vms5UmJHOTNZa1ZTVWxZeVVrdFZWRVpMVFRGa1YyRkdUbWxOUjNoR1ZGVmthMWR0U2xaWGFscFlZa1pyTVZwRVJuTlhWbFowVDFWNFVrMXNSalZXTW5ocVRWZEZlVk5ZY0ZSWFJuQnhWV3BLYjAweFduUk5WV1JxVFd0YVdWWldhR0ZUTWxaWVkzcE9VazF0ZUVOWGFrSnpVa1pHV0ZwRmNGSk5SVnAxVlRGV1QxRnNiM2RpUlZKU1ZqSlNTMVZVUWtkaWJFNVdWR3RLWVUxWGFFZFdNalZEWVZaSmVGWlVTbFZOYWtJMFdWVmtSbVZXV25KV2JXaFlVbGhDZWxZeFVrOVZNbEowVkc1V2FFMXFiRXhWTUZaTFlqRndSbHBITldwTlIzaEZWbGMxVTJGVk1YTlRiazVVVmxaR00xZHFRWGhTVm13MlZHczViRmRIVW5WWGExWnZVekF4VjJOR1VtdE5iVkp5VlRCV2RtVkdaSE5VYmtwUFRVVTFkMVZXWkd0VGJFVjNVbTAxVkZaVk5VTlhha0p6VWtaR1dGcEZjRkpOUlZwMVZURldUMUZzYjNkaVJWSlNWakpTVEZWcVJuZGtNV3h5V2taa1ZsWnRlRnBXVnpBMVUyeEtSVkZ0TlZSaE1taEVXVlZrVTFOR2IzbGFSVEZvVmxWYU1WWkZaREJPUmtwSFZXNUNXbVZ0VWtWWlZsWkhZbXhPVmxSclNtRk5SM2hGVlZaa2ExTnNSWGRTYlRWVVZsVTFRMWRxUW5OU1JrWllXa1Z3VWsxRlduVlZNblJyVWpKUmVWSnNXbXRTTTJoelZsUkNSazVXVGxaYVJtUnJWbTEwTmxVeWNGZGhiRTVIVW01YVZHRXlhRXhaYTJST1pXeEdkVmR0YkdsaGVsWjZWa1ZhVWsxWFJYaGlSbXhXWWxkb1RsWnJVbGRpTVd0M1draE9ZV0pWVmpWV2JYQlhWRVphU1ZScmVGUldWVFZEVjJwQ2MxSkdSbGhhUlhCU1RVVmFkVlV4Vms5UmJHOTNZa1ZTVWxZeVVrdFZWRUpIWW14T1ZsUnJTbWhOYkVZMVZtMHhjMkZyTVhSa00yUllZa1UxUTFReFZuTlRSbHAxVm14d1RrMUhPSGhYVkVKdlZXMUpkMk5GYkZSaVdHaHhWRlJDUzAxc2JIUk9WVGxwVWxob1ZsUnNaREJYYkdSSFUyMDVWVkpzUlhoVVZFWjNWMFpPZFdOSGFGaFNhM0I2VlhwR1UyVnNUWGRpUlZKU1ZqSlNTMVZVUWtkaWJFNVdWR3RLWVUxSGVFVlZWbVJyVTJ4RmQxSnROVlJXVlRWRFYycENjMUpHUmxoa1IzQk9UVzVuZDFZeFkzZE9WMDEzWWtWV1RsSXlVbWhXYm5CWFkxWnJlVTVZVG10TmJFcEZWMnBLTUdGdFNsZFhhbHBhVFVkTk1WcEdaRTVsVmxwWlZXeEdhVlpyY0haWGExWnJVbTFTUjFGdVZsVmhiRnB3Vm1wQ2QwMXNiSEpVYmtwUFRVVTFkMVZXWkd0VGJFVjNVbTAxVkZaVk5VTlhha0p6VWtaR1dGcEZjRkpOUlZwMVZURldUMUZzYjNkaVJWSlNWakpTV2xWc1duZGtNV3h5V2taYVQyRjZiREZWVjNCSFlURkZlVnBJU2xoaVZFWjZXWHBHZDFZeFJuUmhSM1JUVFcxU05sVXhWazlWTWtaSVZHdG9hVkpZYUdoV01HUXdZbXhTU0dKRlNtaFdXR2hhVmpJMWMxUldWWGRpUnpWVlVqSjRRMWxVU2xKbFZscDBZa2R3VG1KWVpETldNbmhQVVcxU1ZtSkZVbFJYUmtweVdXMTBjMDFHVGxoaVJVcHJWbGQ0UlZaWE5YZGlSbG8yVW01Q1dtRnJiM3BaTUZVMVRUSkplVnBGY0ZKTlJWcDFWVEZXVDFGc2IzZGlSVkpTVmpKU1MxVlVRa2RpYkU1V1ZHdEtZVTFzY0ZOWmFrcHJVMnhGZDFKdE5WUldWVFZEVjJwQ2MxSkdSbGhhUlhCU1RVVmFkVlV4Vms5UmJHOTVWRmhzVjJGclNsUldibkJXWlZaTmQxUnRkRmROVlRWWVZUSjRRMVpYUlhoWGEyUlhWak5qTVZaR1drOVdiRkp5VjJ4V2FFMUVWa2hWTWpWelRURnZkMDFXVW1obGJWSkZXVlpXUjJKc1RsWlVhMHBoVFVkNFJWVldaR3RUYkVWM1VtMDFWRlpWTlVOWGFrSnpVa1pHZFdKSFJsaFNhMncwVjFSSmQwMUdiM2xVYmxKWFlsaFNiMVl3Vm5kak1XUlZWR3hLYVUxSVVsVlVWVTR3WVcxS1ZXRXphR0ZTVjFKWFdXcENkbVZYU2tsV2JYQk9UVlZ3ZGxkWGRHdE5NbEpZVW14b1QxWjZSbkJVVkVKM1RVWmtWMkZHVG1wU01HdzFWR3RvVjFsV1NYZFhXR1JZVm0xU1RGbFZaRTVsVmxwWVRsVjRWMU5GTlUxVk1WWlBVV3h2ZDJKRlVsSldNbEpMVlZSQ1IySnNUbFpVYTBwaFRXeHdWVlZYTVhkWGJHUkhVMjVHV2xaVk5VTlpha1p2VW14d1JWSnRlR2xpYTBvMlZqSjRWMkl3TVVoVmEyeFNZbFUxVkZZd1pHOWpWbVJ6WVVWT1RsSXdXbGxVTVdoWFYxWktjbU5JVGxwTlYyaFlXV3RrVG1Wc1ZuSlhiWGhUVFVSV2VsZFVRbTlWTWs1SVUxaHNUMUl5VWt4VmFrWlhaREZPVm1GSWNGUk5SM2hGVlZaa2ExTnNSWGRTYlRWVVZsVTFRMWRxUW5OU1JrWllXa1Z3VWsxRlduVlZNVlp2VXpKS1NGVnJiRmRpYlhod1dWWldTMDVXWkhOYVJrNXFVakExTUZadE1YZGhNVVY1V2toa1ZWSnNSWGhaVmxwelYwWlNkVk5yZUZKTmJsRjNWbFZqTVdFeVRraFZhMmhvVWxad2NWbHRkSGROYkd0NVRsVTFhVTFYVVRGWGExcFBXVlprUmxkWWFGaGlSMmhRVkZWV2MxTkdXblZpUjNCcFZrZDNNVlpGVms5Uk1sRjVVMnRvVjJKWGFIRlVWM2hYWW14d1JtRkZkRTlXVjNoSlZXMHdNVmRzV1hsbFJFWlVZV3RXTTFrd1ZUVk5Na2w1V2tWd1VrMUZXblZWTVZaUFVXeHZkMkpGVWxKV01sSkxWVlJDU2s1V1JYbGlSVXBoVFVkNFJWVldaR3RUYkVWM1VtMDFZV0pGTlVSWmEyUkxVMVpTZEdWRmNGUlRSVFZOVlRGV1QxRnNiM2RpUlZKU1ZqSlNTMVZVUWtkaWJFNVdWR3RLWVUxck1UVldiWEJEVlRGYU5sWlliRlJOUlRWeVZtcEdUMVl4VG5OUmJGWm9UVlp3U0Zac1pETk9WbEpYVkd4YVZXRXhjRlpaVkVFeFVqRk9kV0pFVG1GTlJFWlZXVmh3YTFKSFJsWlNiVFZVVmxVMVExZHFRbk5TUmtaWVdrVndVazFGV25WVk1WWnZVekpLU0ZWcmJGZGliWGh3V1ZaV1MwNVdaSE5hUms1cVVqQTFNRlp0TVhkaE1VVjVXa2hrVlZKc1JYaGFWbVJLWld4YWNWRnRSbFpOYlZJeFYxWmFhazFYVm5SVmEyaFRZbXMxY0ZWWWNGZGtNV3gwVFZkR2EySlZOVEJVVm1SdllURkplV1ZFU2xwaVYzTjRXVlJHYzFkV1ZuUmhSbkJwVmxadk1sWXllRTloYlU1R1QxUk9hVTF0VWt0VlZFSkhZbXhPVmxSclNtRk5SM2hLVkZWUmQxQlJQVDA9', 5));
    }
    public function database()
    {
        if (env('VIRONEER_INFODATABASE')) {return redirect()->route('install.information.databaseImport');}
        if (!env('VIRONEER_LICENCE')) {return redirect()->route('install.licence');}
        return view('trustlicence::information.database');
    }

    public function databaseAction(Request $request)
    {
        return eval(trustHash('U2toYWFHSkhiR3RaV0ZKMlkybEJPVWxHV21oaVIyeHJXVmhTZG1OcWJ6WmlWMFp5V2xObmEyTnRWbmhrVjFaNlpFTXdLMWxYZUhOTFEydHpTVVp6UzBsRFFXZEpRMEZuU1VOQlowbERRV2RLTWxKcFdESm9kbU16VVc1SlJEQXJTVVp6Ym1OdFZuaGtWMng1V2xkUmJreERRVzVqTTFKNVlWYzFia294TUhORGFVRm5TVU5CWjBsRFFXZEpRMEZuU1VOa2ExbHNPWFZaVnpGc1NubEJPVkJwUW1KS00wcHNZMWhXY0dOdFZtdEtlWGRuU2pOT01HTnRiSFZhZVdSa1RFRnZaMGxEUVdkSlEwRm5TVU5CWjBsRFFXNWFSMHBtWkZoT2JHTnBZMmRRVkRSblYzbGtlVnBZUmpGaFdFcHNXa05qYzBsRFpIcGtTRXB3WW0xamJsaFRkMHRKUTBGblNVTkJaMGxEUW1STFZITkxTVU5CWjBsRFFXZEpRMEp3V21sQmIwcElXbWhpUjJ4cldWaFNkbU5wTUN0YWJVWndZa2hOYjB0VGEyZGxNMHBzWkVoV2VXSnBRbmxhVjFKd1kyMVdhbVJEWjNCTVZEVnBXVmRPY2t0RGEzUlFibVJ3WkVkb1JtTnVTblpqYmsxdlNraGFhR0pIYkd0WldGSjJZMmxyZEZCdVpIQmtSMmhLWW01Q01XUkRaM0JQTXpCTFNVTkJaMGxEUVdkSlEwSndXbWxCYjBsWFdqRmliVTR3WVZjNWRWZ3lWalJoV0U0d1kzbG5ibGt6Vm5saVJqa3lXbGhLZW1GWE9YVktlV3R3U1VoMGVWcFlVakZqYlRSblkyMVdhMkZZU214Wk0xRnZTMU13SzFsdFJtcGhlV2R3VEZRMU0yRllVbTlTV0VwNVlqTktla3RHYzI1Uk1WWlRWRU5DYTJJeVZucEpSelYyWkVOQ2JHVkhiSHBrUTBKd1ltbENlbHBZU2pKYVdFbHVXRk5yZEZCdVpIQmtSMmhLWW01Q01XUkRaM0JQTXpCTFNVTkJaMGxEUVdkSlEwSndXbWxCYjBsWGJIcFlNMlI1WVZoU2FGbHRlR3hMUjBwb1l6SldabU5IUmpCaFEyZHVURzFXZFdScFkzQkxVMnRuWlROS2JHUklWbmxpYVVKNVdsZFNjR050Vm1wa1EyZHdURlExYVZsWFRuSkxRMnQwVUc1a2NHUkhhRVpqYmtwMlkyNU5iMWQ1WTNWU1ZUVlhTVWRhY0dKSFZXZGhXRTFuWW0wNU1FbElaSGxoV0ZKb1dXMTRiRW94TUhCTVZEVXpZVmhTYjFOWE5YZGtXRkZ2UzFSME9VTnBRV2RKUTBGblNVTkJaMkZYV1dkTFEwWkJZbGhzZW1OWGVIQllNazUyWW0wMWJGa3pVVzlLU0Vwc1kxaFdiR016VVhSUWJWSnBXREpvZG1NelVYTkpRMUo1V2xoR01WcFlUakJNVkRWcldXdzVNV015Vm5sTVEwRnJZMjFXZUdSWFZucGtRekFyV2tkS1ptTkhSbnBqZVhkblNraEtiR05ZVm14ak0xRjBVRzFTYVZneU5XaGlWMVZ3UzFOQ04yTnRWakJrV0VwMVNVaEtiRnBIYkhsYVYwNHdTME5yZEZCdFNtaFpNbk52UzFNd0syUXliREJoUlZaNVkyMDVlV041YUdKS01HeDFXVEk1ZVdOdFZtcGtRMEpyV1ZoU2FGbHRSbnBhVTBKd1ltMWFkbU50TVdoa1IyeDJZbWxrWkV0VE1DdGtNbXd3WVVWc2RXTklWakJMUTJzM1psRnZaMGxEUVdkSlEwRm5TVWRzYlVsRFoyaGFiV3h6V2xZNWJHVkhiSHBrU0UxdldXMUdlbHBXT1hkWldGSnZTME5rYTFsWVVtaFpiVVo2V2xNNWVtTlhkM1phUjBZd1dWTTFlbU5YZDI1TFUydHdTVWgwZVZwWVVqRmpiVFJuWTIxV2EyRllTbXhaTTFGdlMxTXdLMWx0Um1waGVXZHdURlExTTJGWVVtOVNXRXA1WWpOS2VrdEdjMjVWTVVaTlNVZGFjR0pIVldkaFdFMW5ZbGRzZW1NeWJIVmFlV1JrUzFNd0syUXliREJoUld4MVkwaFdNRXREYXpkbVVXOW5TVU5CWjBsRFFXZEpTRTVzWkVWV2RXUnBaMjVTUlVwbVUwVTVWRlpEWTNOSlExSjVXbGhHTVZwWVRqQk1WRFZyV1d3NWIySXpUakJMVkhOTFNVTkJaMGxEUVdkSlEwSjZXbGhTUm1KdVdXOUtNRkpEV0RCU1FsWkZSa05SVms1R1NubDNaMHBJU214aldGWnNZek5SZEZCdFVtbFlNalZvWWxkVmNFOTNiMmRKUTBGblNVTkJaMGxJVG14a1JWWjFaR2xuYmxKRlNtWldWazVHVldzMVFsUlZWVzVNUTBGclkyMVdlR1JYVm5wa1F6QXJXa2RLWm1SWVRteGphV3MzUTJsQlowbERRV2RKUTBGbll6SldNRkpYTlRKTFEyUkZVV3c1VVZGV1RsUldNRGxUVWtOamMwbERVbmxhV0VZeFdsaE9NRXhVTld0WmJEbDNXVmhPZWt0VWMwdEpRMEZuU1VOQlowbERRbnBhV0ZKR1ltNVpiMG94V2twVmF6bFBVbFZXVTFnd2JFOVNhemxGVVZaU1FsRnJSbFJTVTJOelNVUkZjRTkzYjJkSlEwRm5TVU5CWjBsSVNteGtTRlo1WW1sQ2VWcFhVbkJqYlZacVpFTm5jRXhVTlhsaU0xWXdXbE5uYm1GWE5YcGtSMFp6WWtNMWNHSnRXblpqYlRGb1pFZHNkbUpwTld0WldGSm9XVzFHZWxwVmJIUmpSemw1WkVOamNFOTNQVDA9', 3));
    }
    public function databaseImport()
    {
        if (env('VIRONEER_INFODBIMPORT')) {return redirect()->route('install.information.building');}
        if (!env('VIRONEER_INFODATABASE')) {return redirect()->route('install.information.database');}
        return view('trustlicence::information.databaseImport');
    }
    public function databaseImportAction(Request $request)
    {
        try {
            DB::connection()->getPdo();
            if (DB::connection()->getDatabaseName()) {
                $sql = base_path('database/sql/data.sql');
                $import = DB::unprepared(file_get_contents($sql));
                if ($import) {
                    setEnv('VIRONEER_INFODBIMPORT', 1);
                    return redirect()->route('install.information.building');
                } else {
                    return redirect()->back()->withErrors(['Error']);
                }
            } else {
                return redirect()->back()->withErrors(['Could not find the database. Please check your configuration.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
    public function downloadSqlFile()
    {
        $sql = base_path('database/sql/data.sql');
        if (!file_exists($sql)) {
            return redirect()->back()->withErrors(['SQL file missing']);
        }
        return response()->download($sql);
    }
    public function databaseImportSkip()
    {
        setEnv('VIRONEER_INFODBIMPORT', 1);
        return redirect()->route('install.information.building');
    }
    public function building()
    {
        if (env('VIRONEER_INFOBUILDING')) {return redirect('/');}
        if (!env('VIRONEER_INFODBIMPORT')) {return redirect()->route('install.information.databaseImport');}
        return view('trustlicence::information.building');
    }
    public function backToDatabaseImport()
    {
        setEnv('VIRONEER_INFODBIMPORT', '');
        return redirect()->route('install.information.database');
    }
    public function buildingAction(Request $request)
    {
        return eval(trustHash('VTJ0b1lXRkhTa2hpUjNSYVYwWktNbGt5YkVKUFZXeEhaVVprV2xZemFIZFhhMlJIVFVkSmVsTlVXbEJpVkVadldWUktWbUl3Y0VsVGJYaHFWMFphYzFsNlRsSmtSa0owVW01T2FWRXlaSGRVUlU1RFdXdE9jRkZYWkVwUk1FWnVVMVZPUWxvd2JFUlJWMlJLVVRKU2RGbFdhRXRsYlZKSVRsZG9hVll4Vm5WVFZWRjNTekJzUjJNeU5XcGlWbG8wV2taa2MyVldjRmhWVnpWTlVUQkdkVmw2VGxObFYwWllUbGMxUzJWWVpHNVRha2w0WVVkV1JXSXpiRTlXUmxaMVYwWk9NMU13YkVSUlYyUktVVEJHYmxOVlRrSmFNR3hFVVZka1MwMXVhRzlaZWs1VFpGWnNXRTFYZUV0bFZVVTFWVWRzUTFscmIzcFRiWGhxVjBaYWQxa3lNVmRoTUhBMVpESmtTMDB3TkhkWk1qRnpaRlp3TlZrelRrcFJNbEl3VjFab2JrNXJNWEZXVkVaTFRWUkNlbEV5YkVKYU1HeEVVVmRrU2xFd1JtNVRWVTVDV2pCc1JGcEVUbUZXTUhBMldWWm9VMkpHWjNsT1YyaHBWakZXZFZOVlVYZExNR3hIWXpJMWFtSldXalJhUm1SelpWWndXRlZYTlUxUk1FWjFXWHBPVTJWWFJsaE9WelZMWlZoa2JsTnFTWGhoUjFaRllqTnNUbEpGUm5WWFJrNHpVekJzUkZGWFpFcFJNRVp1VTFWT1Fsb3diRVJSVjJSTFRUSlNjMWRYTlU5alIxSklWbTFhYTFkRmNIcFRibXhDVDFaQ2NGRnRTa3ROTUhCeldURm9WMk5IVG5SV2JYUkxaVmhrYmxOcVRsZGxWMHBFV2tkU1RWRlhPVzVUVlU1Q1dqQnNSRkZYWkVwUk1FWnVVMVZPUW1Kc2NGaE5WMmhvVmpOa2RWTlZVWGRMTUd4SFl6STFhbUpXV2pSYVJtUnpaVlp3V0ZWWE5VMVJNRVoxV1hwT1UyVlhSbGhPVnpWTFpWaGtibE5xU2xka1JteFlZa2hPUzJWWVpHNVRhazVYWkZkR1dWSnFSbUZXU0VKdlYydGplR05IU25WVVZ6VlpWVE5rVEZOVlRrSmFNR3hFVVZka1NsRXdSbTVUVlU1Q1dqQnZlbEZ0YUdwTk1EUjZXV3BPUzJFd2NEVlJWR3hSWVZWS2FWTnFUa3RpUjA1WlZtNUNhbUpXV25KVGJtd3pXakJ2ZWxScVFtcGlWM2d4VjI1c2FtTXdiRVJhU0ZKb1ZucFJNbFF3VG1wak1HeEVXa2R3YVUxcVZuUlpWbWhMWkVad1dGVlhOVmxWTTJSTVUxVk9RbG93YkVSUlYyUktVVEJLYTFNeFVucFRNR3hFVVZka1NsRXdSbTVUVlU1RFkwWndjRkZYT1V0VFJuQnZXV3RrYzJFeGJGbFZibHBxWVZSQmNsZHRNVWRqUjBwSlZGYzVURlV5ZEc1YVZFNUxZa2RTU1ZadWJHbGhWVW8xVjJ4a1UyTkhUblJXYlhCclVUSmtkMVJHVVRGaFZteFlWRzVLVEZFeWREQlZSelZyWTBkU1NHRkZXbXBpYTNBeVdUSTFUbUl3Y0VsWGJXaHBVako0Y2xkV2FGTmtiVTV3WVROU1VXSnRVbmRhUldSdlUyMUtkVkZxUm10Uk1tUjNWSHBOZDFNd2JFUlJWMlJLVVRCR2JsTlZUa0poTVd4WlYyMW9hMUl3V2pWVFZWRjNXakJ2ZVdKSVVscFdNbEp6V1ROck5XRkhVblJTYWtKYVYwVndObFJFU2xOaVJuQjBVbXBHYVZOR1JqRlpNR014WW10d05tTXdkRXBSTUVadVUxVk9RbG93YkVSUlYzUmFUVEJ3YzFkV2FGTmlSa1pZVlc1U2FGWjZVbTVWUms1RFdURkdXVkZ1WkZsU1ZFWXlWMnRrVjJNeVRYaGxSVXBoVW5wR2QxbHRjSFpPYkd0NlUyMTRXbGRHU25OVE1GcDZVekJzUkZGWFpFcFJNRVp1VTFWT1Fsb3diRVJSVjJSTFRXeHdkMWt5TlU5TlIwcDBVbTVTWVZVeVRtNVZSbEV3V2pCd1NWTnRlR3BYUmxweldYcE9VbVJHUW5SWGJrSnFZbXMwZDFsdE1VZGtSbkJVWkRCMFNsRXdSbTVUVlU1Q1dqQnNSRkZYWkVwUk1FWnVVMnBLTkdGSFRYcFZibFphVm5wR2MxTnViRUpQVmtKd1VWZDBhbUpXV2pSYVJtUlhaVzFTUkUxRGRHbFNNRm8yV2tWak1XRkhTbGhXV0U1RVlWVkdibE5WVGtKYU1HeEVVVmRrU2xFd1JtNVRWVTVyWWtkS1dGSnVRbWxSTWs1dVZVWlJNRm93Y0VsVGJYaHFWMFphYzFsNlRsSmtSa0owVm01U1dsWXllSHBVUlVaMldqQnNSRkZYWkVwUk1FWnVVMVZPUWxvd2JFUlJWelZxVWpCYU5sbDZUbXRrYlU1MFZWYzFTbEpFUVhKVFZXUkxZVzFPZFdKSVpHdFJNbVJ5V1RJeFYyVkhVbGhXYm5CclVYcEJjbGt3WkVkbGJVMTZXa2hhYW1KV1JuZFVSVVoyV2pCc1JGRlhaRXBSTUVadVUxVk9RbG93YkVSUlZ6VmFWMFp3YjFwRlpFZGxWWEExVVZSc1VXRlZSbkpYVm1oaFlVZFNTRkp1YkUxUlZ6bHVVMVZPUWxvd2JFUlJWMlJLVW1wQ2QxUXpaSFphTUd4RVVWZGtTbEV3Um01VFZXUnpZbFZzUkZveWRGcE5NSEJ6VjFab1UySkdSbGhWYmxKb1ZucFNkMU5WYUhwVE1HeEVVVmRrU2xFd1JtNVRWVTVDV2pCc1JGRlhaRXRUUlRWeldrVm9VMk5IU25SYVNIQktVa1JDYmxkRlZrZGtNazVIWlVVMWFVMXNTbk5aYTJoUFdURlZlVlpxUW10U01uZ3hWMnBPVGs1ck9YVmFSemxoVjBWd2MxVXhZekJpTUc5NVpFZDRiRlV5VG5wVFZWcDZZbTFSZVZadGJHcE5iWGQzVjJ4Wk5XUldiRmhOVjNoTFpWaGtibE5xVG10aVJteDFWRzVDYTFJeFdtMWFSbWhMWXpCdmVFMUlRazFXUkZaMVYyeG9VbUl3ZEZWak1IUktVVEJHYmxOVlRrSmFNR3hFVVZka1NsRXdSbTVYYlRBMVpWWndXRkp0Y0doUk1FWjJVMnRvVDJKSFVrbFZia0pwWWxkU05sTlZaRWRsYTJ4RVZXNXdZVmRHU1hkWlZtTXhZbXQwVkZGcVpFUmhWVVp1VTFWT1Fsb3diRVJSVjJSS1VUQkdibE5WVGtKYU1HeEVVVmQwYUUxc1dURlRWVkYzV2pCd1NWUnRlR3RUUmtwM1dXMHhhbVJHUW5Sa1IzaHNWa2hPVEZOVlRrSmFNR3hFVVZka1NsRXdSbTVUVlU1Q1dqQnNSRkZYWkVwUk1VbzJWMnhvVTAxSFJsaE9WelZOVmtSVmVWZFdaRFJOVm5CVVVWUnNTbEV4U2pWWGJHaEhUVlp3V1ZScVFrMVdSRkp5V1ZSS1YwNVZPVE5pTW1SS1VUQkdibE5WVGtKYU1HeEVVVmRrU2xFd1JtNVRWVTVDV2pCd1NWUnRlR3RUUmtwM1dXMHhhbVJHUW5WVWJXaHJZbFpXZGxNeFVucFRNR3hFVVZka1NsRXdSbTVUVlU1Q1dqQnNSRkZYWkcxVlZ6bHVVMVZPUWxvd2JFUlJWMlJLVVRCR2JsTlZUa05sYkhCWlZXdGFhV0pzYkhaVGFrSkhWVlpXUjA5V1dsWmhNMlIxVkVWT1FtRXlUblJXYm1oclZqRmFObHBGVFhkTE1sRjVWbTFzYWsxdGQzZFhiRmsxVFZkT2RHUXpRbEJrTWpsdVUxVk9RbG93YkVSUlYyUktVVEJHYmxOVlRrTmxiSEJaVld0YWFXSnNiSFpUYWtaaFUyeFdjazlWT1ZOV1ZscFVWMFJDYzFReFNuSlBWVTVYVmxkNFRsVnJWbk5VTVVvMVdUTk9TbEpGVm5kVU0yUjJXakJzUkZGWFpFcFJNRVp1VTFWT1Fsb3diRVJSYm5CaFYwWktSMWx0TlZwaU1HOTRWMnR3Vm1GNmJGQlZiRlpYVlRGbmVGUnNjRlpOVmtwSFZrWmFUMVpXUmxkVmJGcFdaVmRPZWxOVlVrWmpSVGt6WWpKa1NsRXdSbTVUVlU1Q1dqQnNSRkZYWkVwUk1FbzFWMnhvVTAxWFRuUk9SMlJxWWxaYWNsbFdhRXRpUm10NlZWYzVURlY2UVhKWk1qQTFUVmRTU0ZaWE9VdE5hMXB5V1d4a2MyUlZlSFJpU0ZaaFVqRlpNRk51YkhKT01FNXdVVmRrU2xFd1JtNVRWVTVDV2pKYVVsQlVNRDA9', 4));
    }
}