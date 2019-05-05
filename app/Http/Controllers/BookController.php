<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

use App\Book;
use App\Image;
use App\Author;

use Exception;
use Psy\Util\Json;


class BookController extends Controller
{
    public function index() {
        $books = Book::with(['authors', 'images', 'user'])->get();
        return $books;
    }

    public function findByISBN (string $isbn) {
        $book = Book::where('isbn', $isbn)
                ->with(['authors', 'images', 'user'])
                ->first();
        return $book;
    }

    public function checkISBN (string $isbn)  {
        $book = Book::where('isbn', $isbn)->first();
        return $book != null ? response()->json('book with ' . $isbn . ' exists', 200)
            : response()->json('book with ' . $isbn . ' does NOT exist', 404);
    }

    public function findBySearchTerm (string $searchTerm) {
        $books = Book::with(['authors', 'images', 'user'])
            ->where('title', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('subtitle', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')

            /* search term in authors relation */
            ->orWhereHas('authors', function($query) use ($searchTerm)  {
                  $query->where('firstName', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('lastName', 'LIKE', '%' . $searchTerm . '%');
            })
            ->get();
        return $books;
    }

    public function show($book) {
        $book = Book::with('authors', 'images')->find($book);
        dd($book);
        return view('books.show', compact('book'));
    }

    /**
     * create new book
     */
    public function save (Request $request) : JsonResponse {

        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {

            $book = Book::create($request->all());

            // @ToDo: images & authors
            // save images
            if ($request['images'] && is_array($request['images'])) {
                foreach ($request['images'] as $img) {
                    $image = Image::firstOrNew([
                        'url' => $img['url'],
                        'title' => $img['title']
                        ]
                    );
                    // assign image to book
                    $book->images()->save($image);
                }
            }

            // save authors
            if ($request['authors'] && is_array($request['authors'])) {
                foreach ($request['authors'] as $auth) {
                    $author = Author::firstOrNew([
                            'firstName' => $auth['firstName'],
                            'lastName' =>  $auth['lastName'],
                        ]
                    );
                    // assign image to book
                    $book->authors()->save($author);
                }
            }


            DB::commit();
            return response()->json($book, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('saving book failed' . $e->getMessage(), 420);
        }
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return JsonResponse
     */
    public function update(Request $request, string $isbn) : JsonResponse
    {

        DB::beginTransaction();
        try {
            $book = Book::with(['authors', 'images', 'user'])
                ->where('isbn', $isbn)->first();
            if ($book != null) {
                $request = $this->parseRequest($request);
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
                $book->authors()->sync($ids);
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

    public function delete(string $isbn) : JsonResponse {
        $book = Book::where('isbn', $isbn)->first();
        if ($book != null) {
            try{
                $book->delete();
            }
            catch (Exception $e){
                return response()->json('cannot delete book which has already been ordered: ' . $e->getMessage(), 403);
            }
        }
        else {
            throw new \Exception("book couldn't be deleted - does not exist");
        }
        return response()->json('book ' . $isbn . ' deleted', 200);
    }

    private function parseRequest (Request $request) : Request {
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }
}
