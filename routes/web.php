<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
Route::get('welcome', function () {
    return view('welcome');
});
Route::get('main', function () {
    return view('main');
});

Route::group(['prefix' => 'manager','namespace' => 'Manager'],function ($router)
{
    $router->get('/', 'DashboardController@index');
    $router->get('dash', 'DashboardController@index');
    $router->get('login', 'LoginController@showLoginForm')->name('manager.login');
    $router->post('login', 'LoginController@login');
    $router->any('logout', 'LoginController@logout');
});

Route::group(['prefix' => 'manager', 'middleware' => 'auth.manager:manager', 'namespace' => 'Manager'], function ($router)
{
    $router->any('secure', 'LoginController@secure');
    $router->get('menu', 'DashboardController@menu');

    $router->any('accounts.index', 'AccountsController@index')->name('manager.accounts.index');
    $router->any('accounts.add', 'AccountsController@addAccount')->name('manager.accounts.add');
    $router->any('accounts.edit', 'AccountsController@editAccount')->name('manager.accounts.edit');
    $router->any('accounts.destroy', 'AccountsController@destroyAccount')->name('manager.accounts.destroy');
    $router->any('accounts.group.index', 'AccountsController@groups')->name('manager.accounts.group.index');
    $router->any('accounts.addGroup', 'AccountsController@addGroup')->name('manager.accounts.addGroup');
    $router->any('accounts.editGroup', 'AccountsController@editGroup')->name('manager.accounts.editGroup');
    $router->any('accounts.modifyLimit', 'AccountsController@modifyLimit')->name('manager.accounts.modifyLimit');
    $router->any('permission.index', 'AccountsController@permissions')->name('manager.permission.index');
    $router->any('permission.add', 'AccountsController@addPermission')->name('manager.permission.add');
    $router->any('permission.edit', 'AccountsController@editPermission')->name('manager.permission.edit');
    $router->any('permission.del', 'AccountsController@delPermission')->name('manager.permission.del');
});