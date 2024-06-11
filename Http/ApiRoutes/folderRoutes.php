<?php

use Illuminate\Routing\Router;

Route::prefix('/folders')->middleware('auth:api')->group(function (Router $router) {
    $router->bind('folder', function ($id) {
        return app(\Modules\Media\Repositories\FileRepository::class)->find($id);
    });

    $router->bind('folderByName', function ($name) {
        return app(\Modules\Media\Repositories\FileRepository::class)->findByAttributes(['filename' => $name]);
    });

    $router->get('/', [
        'as' => 'api.imedia.folders.index',
        'uses' => 'NewApi\FolderApiController@index',
        'middleware' => 'auth-can:media.folders.index',
    ]);
    $router->get('/all-nestable', [
        'as' => 'api.imedia.folders.all-nestable',
        'uses' => 'NewApi\FolderApiController@allNestable',
        'middleware' => 'auth-can:media.folders.index',
    ]);

    $router->get('/breadcrumb/{folderByName}', [
        'as' => 'api.imedia.folders.breadcrumb',
        'uses' => 'NewApi\FolderApiController@breadcrumb',
        'middleware' => 'auth-can:media.folders.index',
    ]);

    $router->post('/', [
        'as' => 'api.imedia.folders.create',
        'uses' => 'NewApi\FolderApiController@create',
        'middleware' => 'auth-can:media.folders.create',
    ]);

    $router->get('/{criteria}', [
        'as' => 'api.imedia.folders.show',
        'uses' => 'NewApi\FolderApiController@show',
        'middleware' => 'auth-can:media.folders.show',
    ]);

    $router->put('/{folder}', [
        'as' => 'api.imedia.folders.update',
        'uses' => 'NewApi\FolderApiController@update',
        'middleware' => 'auth-can:media.folders.edit',
    ]);

    $router->delete('/{folder}', [
        'as' => 'api.imedia.folders.delete',
        'uses' => 'NewApi\FolderApiController@delete',
        'middleware' => 'auth-can:media.folders.destroy',
    ]);
});
