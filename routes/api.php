<?php

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Auth\ApiAuthController;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// methods ohne auth
Route::group(['middleware' => ['api', 'cors']], function() {
    Route::post('auth/login', 'Auth\ApiAuthController@login');

    Route::get('books', 'BookController@index');
    Route::get('book/{isbn}', 'BookController@findByISBN');
    Route::get('book/checkisbn/{isbn}', 'BookController@checkISBN');
    Route::get('books/search/{searchTerm}', 'BookController@findBySearchTerm');

    // Tax info
    Route::get('tax/{adress_id}', 'OrderController@getTax');

});


// methods with auth - shop user
Route::group(['middleware' => ['api', 'cors', 'jwt-auth']], function() {
    // order book
    Route::post('cart', 'OrderController@order');

    // list my orders
    Route::get('myOrders', 'OrderController@ordersByUser');

    // order detail
    Route::get('order/{id}', 'OrderController@orderDetail');

    // login - logout
    Route::post('auth/logout', 'Auth\ApiAuthController@logout');
    Route::get('auth/user', 'Auth\ApiAuthController@getCurrentAuthenticatedUser');
});

// methods with auth - admin rights
Route::group(['middleware' => ['api', 'cors', 'jwt-auth', 'check-admin']], function() {
    // update book
    Route::put('book/{isbn}', 'BookController@update');

    // delete book
    Route::delete('book/{isbn}', 'BookController@delete');

    // insert book
    Route::post('book', 'BookController@save');

    // update state
    Route::put('order/{id}', 'OrderController@updateState');

    // list all orders
    Route::get('orders', 'OrderController@index');

    // list orders by state
    //Route::get('orders/{stateName}', 'OrderController@listByState'); // TODO funktioniert noch nicht

    // administration area
    Route::get('administration');
});

