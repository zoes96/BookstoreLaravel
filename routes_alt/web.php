<?php

use App\Book; // Klasse Book einbinden, um nicht immer "App\Book" verwenden zu müssen

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

// MIT BOOK CONTROLLER

Route::get('/', 'BookController@index');
Route::get('/books', 'BookController@index');
Route::get('/books/{book}', 'BookController@show');



// VOR BOOK CONTROLLER...

//$name = 'Fritz';
//$books = ['Herr der Ringe', 'TB1', 'TB2'];


/*
Route::get('/', function () {
    //return view('welcome');
    //return view ('welcome', ['name' => 'Fritz']);

    //$books = DB::table('books')->get();
    $books = Book::all();
    return view ('books.index', compact('books')); // compact macht assoziatives Array (Kurzform von 'books' => $books; Sicherstellung, dass beide Seiten gleich heißen)
});

Route::get('/books/{id}', function ($id) { // get, um Buch auszulesen, put wäre zum Hinzufügen
    //$book = DB::table('books')->find($id);
    $book = Book::where("id", "=", "$id")->get();
    dd($book); // debug Ausgabe - deaktivieren, wenn show.blade.php fertig ist!
    return view ('books.show', compact('book'));
});

Route::get('/hallo', function () {
    return view ('hallo');
});
*/