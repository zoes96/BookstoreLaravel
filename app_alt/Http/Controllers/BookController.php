<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Image;
use App\Book;

class BookController extends Controller
{
    public function index(){
        //$books = Book::all();
        //return view ('books.index', compact('books')); // compact macht assoziatives Array (Kurzform von 'books' => $books; Sicherstellung, dass beide Seiten gleich heißen)

        // MIT REST
        $books = Book::with(['authors', 'images', 'user'])->get();
        return $books;
    }

    public function show($book){
        $book = Book::with(['authors', 'images'])->find($book); // with gibt liest auch die Relationen aus (braucht aber viel länger zum Laden)
        dd($book); // debug Ausgabe - deaktivieren, wenn show.blade.php fertig ist!
        return view ('books.show', compact('book'));
    }

    public function findByISBN(string $isbn){
        $book = Book::where('isbn', $isbn)
            ->with(['authors', 'images', 'user'])
            ->first();
        return $book;
    }

    public function checkISBN(string $isbn){
        $book = Book::where('isbn', $isbn)
            ->first();
        // check + exception über HTTP response codes
        return $book != null ? response()->json('book with '.$isbn.' exists', 200)
            : response()->json('book with '.$isbn.' does NOT exist', 404);
    }

    public function findBySearchTerm(string $searchTerm){
        $books = Book::with(['authors', 'images', 'user'])
            ->where('title', 'LIKE', '%'.$searchTerm.'%')
            ->orWhere('subtitle', 'LIKE', '%'.$searchTerm.'%')
            ->orWhere('description', 'LIKE', '%'.$searchTerm.'%')
            // autoren durchlaufen
            ->orWhereHas('authors', function ($query) use ($searchTerm){ // use statement, um Variable searchTerm in der geschlossenen Funktion zur Verfügung zu stellen
                $query->where('firstName', 'LIKE', '%'.$searchTerm.'%')
                    ->orWhere('lastName', 'LIKE', '%'.$searchTerm.'%');
            })
            ->get();
        return $books;
    }

    public function save(Request $request) : JsonResponse{
        $request = $this->parseRequest($request);

        // Transaction, damit im Falle eines Fehlers alles wieder zurückgesetzt wird (keine "halben" Sachen)
        DB::beginTransaction();
        try{
            $book = Book::create($request->all()); // im Request müssen schon alle Key-Values korrekt mitkommen

            //save images
            if($request['images'] && is_array($request['images'])){
                foreach ($request['images'] as $img){
                    // image auslesen
                    $image = Image::firstOrNew([ //Funktion von Laravel, die überprüft ob Bild schon vorhanden ist und führt je nachdem insert oder update aus
                        'url' => $img['url'],
                        'title' => $img['title']
                    ]);
                    // assign image to book
                    $book->images()->save($image);
                }
            }

            //save authors
            if($request['authors'] && is_array($request['authors'])){
                foreach ($request['authors'] as $aut){
                    // image auslesen
                    $author = Author::firstOrNew([ //Funktion von Laravel, die überprüft ob Autor schon vorhanden ist und führt je nachdem insert oder update aus
                        'firstName' => $aut['firstName'],
                        'lastName' => $aut['lastName']
                    ]);
                    // assign image to book
                    $book->authors()->save($author);
                }
            }


            DB::commit();
            return response()->json($book, 201);
        }
        catch (\Exception $e){
            DB::rollBack();
            return response()->json('saving book failed: '.$e->getMessage(), 420);
        }
    }

    public function update(Request $request, string $isbn) : JsonResponse
    {
        DB::beginTransaction();
        try {
            // Buch mit allem über ISBN aus Datenbank holen
            $book = Book::with(['authors', 'images', 'user'])
                ->where('isbn', $isbn)->first();
            // wenn Buch gefunden
            if ($book != null) {
                $request = $this->parseRequest($request); // für date
                $book->update($request->all());

                //delete all old images
                $book->images()->delete();
                // save images
                if (isset($request['images']) && is_array($request['images'])) {
                    foreach ($request['images'] as $img) {
                        $image = Image::firstOrNew(['url' => $img['url'], 'title' => $img['title']]);
                        $book->images()->save($image);
                    }
                }

                //update authors
                $ids = [];
                if (isset($request['authors']) && is_array($request['authors'])) {
                    foreach ($request['authors'] as $auth) {
                        array_push($ids, $auth['id']);
                    }
                }
                $book->authors()->sync($ids); // wenn Autor noch nicht exisrtiert wird er neu angelegt, wenn er schon existiert wird er über die ID synchronisiert
                $book->save();

            }
            DB::commit();
            $book1 = Book::with(['authors', 'images', 'user'])
                ->where('isbn', $isbn)->first();
            // return a vaild http response
            return response()->json($book1, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating book failed: " . $e->getMessage(), 420);
        }
    }

    public function delete (string $isbn) : JsonResponse{
        $book = Book::where('isbn', $isbn)->first();
        // wenn Buch gefunden
        if ($book != null)
            $book->delete();
        else throw new \Exception("book couldn't be deleted - does not exist");
        return response()->json('book '.$isbn.' deleted', 200);
    }


    // Hilfsmethode, damit Datum richtig gelesen werden kann
    private function parseRequest(Request $request) : Request{
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }
}
