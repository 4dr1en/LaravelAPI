<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Resources\light\BookLightCollection;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *      path="/books",
     *      operationId="GetAllBooks",
     *      tags={"book"},
     *     security={{"bearerAuth":{}}},
     *
     *      summary="Get List Of Book",
     *      description="Returns all books.",
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="make a search in book's title",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          in="query",
     *          description="sort results with the book's title or the number of pages [title|pages]",
     *          required=false
     *     ),
     *     @OA\Parameter(
     *          name="filterGenre",
     *          in="query",
     *          description="filter results with genre id",
     *          required=false
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *      response=401,
     *      description="Unauthenticated",
     * ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     * ),
     */
    public function index(Request $request)
    {
        $path = '/api/books?';
        $books = new Book;

        if ($request->has('filterGenre')) {
            $genre = $request->filterGenre;
            $books = $books->whereHas('genres', function ($q) use ($genre) {
                $q->where('genres.id', '=', $genre);
            });
            $path .= 'filterGenre=' . $genre . '&';
        }
        if ($request->has('search')) {
            $search = $request->search;
            $books = $books->where('title', 'like', '%' . $search . '%');
            $path .= 'search=' . $search . '&';
        }
        if ($request->has('sort')) {
            if ($request->sort == 'title') {
                $books = $books->orderBy('title', 'asc');
                $path .= 'sort=title';
            } elseif ($request->sort == 'pages') {
                $books = $books->orderByRaw('pages_nb * 1 asc');
                $path .= 'sort=pages';
            }
        }

        $r = $books->paginate(10)->withPath($path);
        if (count($r)) {
            return new BookLightCollection($r);
        }
        return response()->json('No books found for this request', 404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *      path="/books/search",
     *      operationId="SearchBooks",
     *      tags={"book"},
     *      security={{"bearerAuth":{}}},
     *
     *      summary="Get List Of Book",
     *      description="Returns all books whith search in the title or the description.",
     *      @OA\Parameter(
     *          name="title",
     *          in="query",
     *          description="make a search in book's title",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          in="query",
     *          description="make a search in book's description",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *          mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *      response=401,
     *      description="Unauthenticated",
     * ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     * ),
     */
    public function search(Request $request)
    {
        if ($request->has('title')) {
            $books = Book::where('title', 'like', '%' . $request->title . '%')->get();
        } elseif ($request->has('description')) {
            $books = Book::where('description', 'like', '%' . $request->description . '%')->get();
        }
        if (count($books)) {
            return new BookLightCollection($books);
        }

        return response()->json('No books found for this request', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *      path="/books",
     *      operationId="NewBook",
     *      tags={"book"},
     *      security={{"bearerAuth":{}}},
     *
     *      summary="New Book",
     *      description="Put a new book in the database.",
     *      @OA\Parameter(
     *          name="title",
     *          in="query",
     *          description="title",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          in="query",
     *          description="description",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="author_id",
     *          in="query",
     *          description="author_id",
     *          required=true
     *     ),
     *     @OA\Parameter(
     *          name="publication_year",
     *          in="query",
     *          description="publication_year",
     *          required=true
     *     ),
     *     @OA\Parameter(
     *          name="pages_nb",
     *          in="query",
     *          description="pages_nb",
     *          required=true
     *     ),
     *     @OA\Parameter(
     *          name="genres",
     *          in="query",
     *          description="genres in json",
     *          required=true,
     *          example= "{id: [2, 4]}"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *      response=401,
     *      description="Unauthenticated",
     * ),
     * @OA\Response(
     *      response=422,
     *      description="Bad Request"
     * ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     * ),
     */
    public function store(BookRequest $request)
    {
        $newBook = Book::addBook($request->all());
        return response()->json($newBook, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/books/{id}",
     *     operationId="GetABook",
     *     tags={"book"},
     *     security={{"bearerAuth":{}}},
     *
     *     summary="Get A Book",
     *     description="Get A Book",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Book id",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     * ),
     *  )
     */
    public function show(int $id)
    {
        $book = Book::find($id);
        return ($book) ? new BookResource($book) : response()->json('Invalid book', 404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Patch(
     *      path="/books/{id}",
     *      operationId="UpdateBook",
     *      tags={"book"},
     *      security={{"bearerAuth":{}}},
     *
     *      summary="Update Book",
     *      description="Update a book in the database.",
     *      @OA\Parameter(
     *          name="title",
     *          in="query",
     *          description="title",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="id",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          in="query",
     *          description="description",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="author_id",
     *          in="query",
     *          description="author_id",
     *          required=true
     *     ),
     *     @OA\Parameter(
     *          name="publication_year",
     *          in="query",
     *          description="publication_year",
     *          required=true
     *     ),
     *     @OA\Parameter(
     *          name="pages_nb",
     *          in="query",
     *          description="pages_nb",
     *          required=true
     *     ),
     *     @OA\Parameter(
     *          name="genres",
     *          in="query",
     *          description="genres in json",
     *          example= "{id: [2, 4]}",
     *          required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *      response=401,
     *      description="Unauthenticated",
     * ),
     * @OA\Response(
     *      response=422,
     *      description="Bad Request"
     * ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     * ),
     */
    public function update(BookRequest $request, int $id)
    {
        $book = Book::find($id);
        if ($book) {
            $updatedBook = Book::updateBook($book, $request);

            return response()->json($updatedBook, 200);
        }

        return response()->json('This book doesn\'t exist', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *     path="/books/{id}",
     *     operationId="DeleteAnBooks",
     *     tags={"book"},
     *     security={{"bearerAuth":{}}},
     *
     *     summary="Delete A Book",
     *     description="Delete A Book",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Book id",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     * ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     *  )
     */
    public function destroy(int $id)
    {
        $book= Book::find($id);
        if($book){
            $book->genres()->detach();
            $book->delete();

            return response()->json('', 204);
        }
        
        return response()->json('Book not found', 404);
    }
}
