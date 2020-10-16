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
    return redirect()->route('admin.index');
});

Route::namespace('Admin')->prefix('admin')->as('admin.')->group(function(){

    Route::get('login', 'BackendController@showLoginForm')->name('login');

    Route::post('login', 'BackendController@login')->name('postLogin');

    Route::middleware('auth.admin:admin')->group(function(){

        Route::post('logout', 'BackendController@logout')->name('logout');

        Route::get('/', 'IndexController@index')->name('index');

        Route::get('dashboard', 'IndexController@dashboard')->name('dashboard');

        Route::match(['get', 'post'], 'settings', 'SystemController@settings')->name('system.settings');
        Route::get('systemlog', 'SystemController@logs')->name('system.logs');

        Route::get('return', 'CustomerController@return')->name('customer.return');
        Route::get('ordersanalysi', 'OrdersController@analysi')->name('orders.analysi');
        
        Route::resources([
            'customer' => 'CustomerController',
            'tags' => 'TagsController',
            'source' => 'SourceController',
            'bank' => 'BankController',
            'remind' => 'RemindController',
            'fup' => 'FupController',
            'sea' => 'SeaController'
        ]);
        Route::get('customeranalysi', 'CustomerController@analysi')->name('customer.analysi');

        Route::get('doremind', 'RemindController@remind')->name('doremind');

        Route::resources([
            'users' => 'UsersController',
            'roles' => 'RolesController',
            'permissions' => 'PermissionsController',
            'grade' => 'GradeController'
        ]);

        //订单列表
        Route::resources([
            'orders' => 'OrdersController',
            'contract' => 'ContractController',
            'goods' => 'GoodsController',
            'maintenance' => 'MaintenanceController',
            'abutment' => 'AbutmentController',
        ]);

        Route::match(['get', 'post'], 'logs', 'OrdersController@logs')->name('orders.logs');
        Route::match(['get','post'], 'back', 'OrdersController@back')->name('orders.back');
        Route::match(['get','post'], 'refund', 'OrdersController@refund')->name('orders.refund');
        Route::match(['get','post'], 'verify', 'OrdersController@verify')->name('orders.verify'); 
        Route::match(['get','post'], 'examine', 'OrdersController@examine')->name('orders.examine'); 

        Route::get('qualified', 'ContractController@qualified')->name('contract.qualified'); 

        Route::get('detail/{id}', 'OrdersController@detail')->name('orders.detail'); 
        Route::get('process/{id}', 'OrdersController@process')->name('orders.process');
        Route::match(['get', 'post'],'processedit', 'OrdersController@processedit')->name('orders.processedit'); 
        Route::match(['get', 'post'], 'stop/{id}', 'OrdersController@stop')->name('orders.stop');
        Route::match(['get', 'post'], 'jscost/{id}', 'OrdersController@jscost')->name('orders.jscost');
        
        Route::get('delimage', 'OrdersController@delimage')->name('orders.delimage');
        Route::post('getorder', 'ContractController@getorder')->name('contract.getorder');
        Route::get('getcity', 'OrdersController@getcity')->name('orders.getcity');
        Route::get('getreview', 'OrdersController@getreview')->name('orders.getreview');

        Route::get('record/{id}', 'CustomerController@record')->name('customer.record');

        Route::get('getgood', 'AbutmentController@getgood')->name('abutment.getgood');
        Route::get('getnode', 'AbutmentController@getnode')->name('abutment.getnode');
    });
});
