<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

    Route::prefix('install')->name('install.')->middleware('installed')->group(function () {
        Route::get('/', 'HandlerController@redirect')->name('index');
        Route::get('requirements', 'HandlerController@requirements');
        Route::post('requirements', 'HandlerController@requirementsAction')->name('requirements');
        Route::get('permissions', 'HandlerController@permissions');
        Route::post('permissions', 'HandlerController@permissionsAction')->name('permissions');
        Route::get('licence', 'HandlerController@licence');
        Route::post('licence', 'HandlerController@licenceAction')->name('licence');
        Route::get('information', 'HandlerController@redirectToDatabase');
        Route::get('information/database', 'HandlerController@database');
        Route::post('information/database', 'HandlerController@databaseAction')->name('information.database');
        Route::get('information/database/import', 'HandlerController@databaseImport');
        Route::post('information/database/import', 'HandlerController@databaseImportAction')->name('information.databaseImport');
        Route::post('information/database/manual/download', 'HandlerController@downloadSqlFile')->name('information.databaseImport.download.sql');
        Route::post('information/database/manual/skip', 'HandlerController@databaseImportSkip')->name('information.databaseImport.skip');
        Route::get('information/building', 'HandlerController@building');
        Route::post('information/building/back', 'HandlerController@backToDatabaseImport')->name('information.building.back');
        Route::post('information/building', 'HandlerController@buildingAction')->name('information.building');
    });

    Route::group(['middleware' => ['notInstalled'], 'prefix' => 'admin'], function () {
        Route::name('admin.additional.')->prefix('additional')->group(function () {
            Route::resource('addons', 'AddonController');
        });
    });
});
