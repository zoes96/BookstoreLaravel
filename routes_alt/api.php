<?php

use Illuminate\Http\Request;

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

// schon automatisch da
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// ------------ Route groups -------------- //

// methods without auth
Route::group(['middleware' => ['api', 'cors']], function () {
    //login
    Route::post('auth/login', 'Auth\ApiAuthController@login');

    // read
    Route::get('books', 'BookController@index');
    Route::get('book/{isbn}', 'BookController@findByISBN');
    Route::get('book/checkisbn/{isbn}', 'BookController@checkISBN');
    Route::get('books/search/{searchTerm}', 'BookController@findBySearchTerm');
});

// methods with auth
Route::group(['middleware' => ['api', 'cors', 'jwt-auth']], function () {
    // insert
    Route::post('book', 'BookController@save');

    // update
    Route::put('book/{isbn}', 'BookController@update');

    // delete
    Route::delete('book/{isbn}', 'BookController@delete');

    // logout
    Route::post('auth/logout', 'Auth\ApiAuthController@logout');

    // user
    Route::post('auth/user', 'Auth\ApiAuthController@getCurrentAuthenticationUser');
});

