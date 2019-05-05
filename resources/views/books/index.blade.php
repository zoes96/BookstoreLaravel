<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bookstore</title>
    </head>
    <body>
        <h1>Hier ist die Booklist:</h1>
        <ul>
            @foreach ($books as $book)
                <li>
                    <a href="books/{{$book->id}}">{{$book->title}}</a>
                </li>
            @endforeach
            <!-- geht statt foreach auch mit php-->
        </ul>
    </body>
</html>
