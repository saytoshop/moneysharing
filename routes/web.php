<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    /**
     * Home Routes
     */
    Route::get('/', 'HomeController@index')->name('home.index');

    Route::group(['middleware' => ['guest']], function () {
        /**
         * Register Routes
         */
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');

    });

    Route::group(['middleware' => ['auth']], function () {
        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
        /**
         * Create group
         */
        Route::post('create_group', [GroupController::class, 'store']);
        /**
         * Join to group
         */
        Route::get('/join_group/{group}', [GroupController::class, 'join'])->name('join_group');
        /**
         * Left group
         */
        Route::get('/leave_group/{group}', [GroupController::class, 'leave'])->name('leave_group');
        /**
         *  make a deposit
         */
        Route::post('make_deposit/{group}', [BudgetController::class, 'makeDeposit'])->middleware('group.admin');
    /**
         *  add/remove user from group admins
         */
        Route::post('toggle_admin/{group}', [GroupController::class, 'toggleAdmin'])->middleware('group.admin');
        /**
         *  spend money
         */
        Route::post('spend_money/{group}', [GroupController::class, 'spendMoney'])->middleware('group.admin');

    /**
         *  change users weight
         */
        Route::post('/mult_change/{group}/{user}', [UserController::class, 'multChange'])->middleware('group.admin');
        Route::post('/ru/{group}/{user}', [GroupController::class, 'removeUser'])->middleware('group.admin');

    });
});
