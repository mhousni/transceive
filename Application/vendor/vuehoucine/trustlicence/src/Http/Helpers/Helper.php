<?php
if (!function_exists('extensionAvailability'))   {
function extensionAvailability($name)
{
    if (!extension_loaded($name)) {
        $response = false;
    } else {
        $response = true;
    }
    return $response;
}
}

if (!function_exists('phpExtensions'))   {
    function phpExtensions()
    {
        $extensions = [
            'BCMath',
            'Ctype',
            'Fileinfo',
            'JSON',
            'Mbstring',
            'OpenSSL',
            'PDO',
            'pdo_mysql',
            'Tokenizer',
            'XML',
            'cURL',
            'GD',
        ];
        return $extensions;
    }
}

if (!function_exists('filePermissionValidation'))   {
function filePermissionValidation($name)
{
    $perm = substr(sprintf('%o', fileperms($name)), -4);
    if ($perm >= '0775') {
        $response = true;
    } else {
        $response = false;
    }
    return $response;
}
}
if (!function_exists('filePermissions'))   {
function filePermissions()
{
    $filePermissions = [
        base_path('app/'),
        base_path('app/Console/'),
        base_path('config/'),
        base_path('vendor/'),
        base_path('bootstrap/cache/'),
        base_path('database/'),
        base_path('storage/'),
        base_path('storage/app/'),
        base_path('storage/framework/'),
        base_path('storage/logs/'),
        'images/',
        'images/avatars/',
        'uploads/',
        'public/images/',
        'public/images/avatars/',
        'public/uploads/',
    ];
    return $filePermissions;
}
}

if (!function_exists('trustHash'))   {
function trustHash($data, $count)
{
    $r = $data;
    for ($i = 0; $i < $count; $i++) {
        $r = base64_decode($r);
    }
    return $r;
}
}
