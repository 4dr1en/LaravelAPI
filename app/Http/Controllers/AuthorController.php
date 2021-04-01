<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorResource;
use App\Http\Resources\light\AuthorLightCollection;
use App\Models\Author;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *      path="/authors",
     *      operationId="getAllAuthors",
     *      tags={"author"},
     *      security={{"bearerAuth":{}}},
     * 
     *
     *      summary="Get List Of Authors",
     *      description="Returns all authorss.",
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="make a search in author's name",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          in="query",
     *          description="sort results with the author's name",
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
     *      response=400,
     *      description="Bad Request"
     * ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     * ),
     */
    public function index(Request $request)
    {
        $path = '/api/authors?';
        $authors = new Author;

        if ($request->has('search')) {
            $search = $request->search;
            $authors = $authors->where('name', 'like', '%' . $search . '%');
            $path .= 'search=' . $search . '&';
        }
        if ($request->has('sort') && $request->sort == 'name') {
            $authors = $authors->orderBy('name', 'asc');
            $path .= 'sort=name';
        }

        $r = $authors->paginate(10)->withPath($path);
        if (count($r)) {
            return new AuthorLightCollection($r);
        }
        return response()->json('No Authors found for this request', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/authors",
     *     operationId="StoreANewAuthors",
     *     tags={"author"},
     *     security={{"bearerAuth":{}}},
     *
     *     summary="Store A New Authors",
     *     description="Store A New Authors",
     *
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="author name",
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
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *  )
     */
    public function store(AuthorRequest $request)
    {
        $newAuthor = Author::addAuthor($request->all());
        return response()->json($newAuthor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/authors/{id}",
     *     operationId="GetAnAuthor",
     *     tags={"author"},
     *     security={{"bearerAuth":{}}},
     *
     *     summary="Get An Authors",
     *     description="Get An Authors",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="author id",
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
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *  )
     */
    public function show(int $id)
    {
        try {
            Author::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json('Invalid author', 404);
        } catch (Exception $ex) {
            return response()->json('Something went wrong', 500);
        }
        return new AuthorResource(Author::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Patch(
     *     path="/authors/{id}",
     *     operationId="UpdateAnAuthor",
     *     tags={"author"},
     *     security={{"bearerAuth":{}}},
     *
     *     summary="Update An Authors",
     *     description="Update An Authors",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="author id",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="author name",
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
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *  )
     */
    public function update(AuthorRequest $request, int $id )
    {
        $author= Author::find($id);
        if($author){
            $updatedAuthor = Author::updateAuthor($author, $request->all());
            return response()->json($updatedAuthor, 200);
        }
        
        return response()->json('This author doesn\'t exist', 404);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Author $author
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Delete(
     *     path="/authors/{id}",
     *     operationId="DeleteAnAuthor",
     *     tags={"author"},
     *     security={{"bearerAuth":{}}},
     *
     *     summary="Delete An Authors",
     *     description="Delete An Authors",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="author id",
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
     *      response=400,
     *      description="Bad Request"
     *   ),
     *  )
     */
    public function destroy(int $id)
    {
        $author= Author::find($id);
        if($author){
            $author->delete();
            return response()->json('', 204);
        }

        return response()->json('author not found', 404);
    }
}
