<?php

use Illuminate\Database\Seeder;
use App\Book;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // user holen
        $user = App\User::all()->first();

        // authors holen
        $authors = App\Author::all()->pluck("id"); // gibt Array von Author-IDs zurück

        // BOOK 1
        $book = new Book;
        $book->title = 'Herr der Ringe';
        $book->subtitle = 'Die Gefährten';
        $book->isbn = '23456765432';
        $book->rating = 10;
        $book->description = "Erster Teil...";
        $book->currentNetto = 24.99;
        $book->published = new DateTime();
        $book->user()->associate($user); //user zuweisen
        $book->save();

        // authors hinzufügen (book muss vorher schon gespeichert werden, damit id existiert)
        $book->authors()->sync($authors);
        $book->save();

        // IMAGE 1
        $image = new App\Image();
        $image->title = 'Cover 1';
        $image->url = 'https://images-na.ssl-images-amazon.com/images/I/51VKKN0KZEL._SY445_.jpg';
        $image->book()->associate($book);
        $image->save();

        // IMAGE 2
        $image = new App\Image();
        $image->title = 'Cover 2';
        $image->url =
            "https://image.redbull.com/rbcom/052/2017-05-10/a83faa05-d728-43c7-a596-f8cebabf1d8f/0012/0/190/0/998/2422/1950/1/mount-everest.jpg";
        $image->book()->associate($book);
        $image->save();

        // BOOK 2
        $book2 = new Book;
        $book2->title = 'Herr der Ringe';
        $book2->subtitle = 'Die 2 Türme';
        $book2->isbn = '234567653409';
        $book2->rating = 10;
        $book2->description = "Zweiter Teil...";
        $book2->currentNetto = 24.99;
        $book2->published = new DateTime();
        $book2->user()->associate($user); //user zuweisen
        $book2->save();
        // authors hinzufügen (book muss vorher schon gespeichert werden, damit id existiert)
        $book2->authors()->sync($authors);
        $book2->save();

        // IMAGE 3
        $image = new App\Image();
        $image->title = 'Cover';
        $image->url = 'https://images-na.ssl-images-amazon.com/images/I/51VKKN0KZEL._SY445_.jpg';
        $image->book()->associate($book2);
        $image->save();

        // BOOK 3
        $book3 = new Book;
        $book3->title = 'Herr der Ringe';
        $book3->subtitle = 'Die Rückkehr des Königs';
        $book3->isbn = '234565453409';
        $book3->rating = 10;
        $book3->description = "Dritter Teil...";
        $book3->currentNetto = 24.99;
        $book3->published = new DateTime();
        $book3->user()->associate($user); //user zuweisen
        $book3->save();
        // authors hinzufügen (book muss vorher schon gespeichert werden, damit id existiert)
        $book3->authors()->sync($authors);
        $book3->save();

        // IMAGE 1
        $image = new App\Image();
        $image->title = 'Cover';
        $image->url = 'https://images-na.ssl-images-amazon.com/images/I/51VKKN0KZEL._SY445_.jpg';
        $image->book()->associate($book3);
        $image->save();

        // BOOK 4
        $book4 = new Book;
        $book4->title = 'Harry Potter';
        $book4->subtitle = 'Stein der Weisen';
        $book4->isbn = '134567653409';
        $book4->rating = 10;
        $book4->description = "Erster Teil...";
        $book4->currentNetto = 34.99;
        $book4->published = new DateTime();
        $book4->user()->associate($user); //user zuweisen
        $book4->save();

        // IMAGE
        $image = new App\Image();
        $image->title = 'Cover';
        $image->url =
            "https://img.buzzfeed.com/buzzfeed-static/static/2015-11/19/17/enhanced/webdr02/original-grid-image-23059-1447970713-6.jpg?downsize=700:*&output-format=auto&output-quality=auto";
        $image->book()->associate($book4);
        $image->save();

        // BOOK 5
        $book5 = new Book;
        $book5->title = 'Harry Potter';
        $book5->subtitle = 'Die Kammer des Schreckens';
        $book5->isbn = '265467653409';
        $book5->rating = 10;
        $book5->description = "Zweiter Teil...";
        $book5->currentNetto = 34.99;
        $book5->published = new DateTime();
        $book5->user()->associate($user); //user zuweisen
        $book5->save();

        // IMAGE
        $image = new App\Image();
        $image->title = 'Cover';
        $image->url =
            "https://img.buzzfeed.com/buzzfeed-static/static/2015-11/19/17/enhanced/webdr02/original-grid-image-23059-1447970713-6.jpg?downsize=700:*&output-format=auto&output-quality=auto";
        $image->book()->associate($book5);
        $image->save();

        /*
        DB::table('books')->insert([
            'title' => str_random(100),
            'isbn' => '123456789',
            'subtitle'=> str_random(100),
            'rating' => 5,
            'description' => str_random(300),
            'published' => new DateTime(),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        */
/*
        DB::table('books')->insert([
            'title' => str_random(100),
            'isbn' => '754456789',
            'subtitle'=> str_random(100),
            'rating' => 5,
            'description' => str_random(300),
            'published' => new DateTime(),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
*/
    }
}
