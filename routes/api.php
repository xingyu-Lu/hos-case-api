<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Exceptions\BaseException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api\Back')->prefix('back')->group(function () {
    // 获取 token
    Route::post('authorizations', 'AuthorizationsController@store')->name('login');

    // 需要 token 验证的接口
    Route::middleware(['auth:api', 'permission', 'filter.empty.string'])->name('api.back.')->group(function () {
        // 角色管理
        Route::apiResource('roles', 'RolesController');

        //save路由api新增权限
        Route::post('permissions/saveApiPermission', 'PermissionsController@saveApiPermission')->name('permissions.saveApiPermission');
        Route::apiResource('permissions', 'PermissionsController');

        // 登录用户信息
        Route::get('admins/info', 'AdminsController@info')->name('admins.info');

        // 管理员
        Route::put('admins/changepwd', 'AdminsController@changepwd')->name('admins.changepwd');
        Route::put('admins/status', 'AdminsController@status')->name('admins.status');
        Route::apiResource('admins', 'AdminsController');

        // 菜单
        Route::put('menus/status', 'MenusController@status')->name('menus.status');
        Route::get('menus/list', 'MenusController@list')->name('menus.list');
        Route::apiResource('menus', 'MenusController');

        // 更新日志
        Route::get('updatelogs/index', 'UpdateLogsController@index')->name('updatelogs.index');

        //文件下载
        Route::get('files/down', 'FilesController@down')->name('files.down');
        //文件上传
        Route::post('files/upload', 'FilesController@upload')->name('files.upload');

        // 病例管理
        Route::put('cases/status', 'CasesController@status')->name('cases.status');
        Route::apiResource('cases', 'CasesController');

        // 病例类型管理
        Route::apiResource('caseTypes', 'CaseTypesController');

        // 胃ca
        Route::apiResource('stomachCas', 'StomachCasController');
        // 胃ca随访
        Route::apiResource('stomachFollowUps', 'StomachFollowUpsController');        
    });
});