<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Http\Resources\GenreCollection;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *      path="/genres",
     *      operationId="GetAllGenres",
     *      tags={"genre"},
     *     security={{"bearerAuth":{}}},
     *
     *      summary="Get List Of Genres",
     *      description="Returns all Genres.",
     *
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
     * ),
     */

    public function index()
    {
        $r = Genre::paginate(10);
        if (count($r)) {
            return new GenreCollection($r);
        }
        return response()->json('No genre found for this request', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *      path="/genres",
     *      operationId="Newgenre",
     *      tags={"genre"},
     *     security={{"bearerAuth":{}}},
     *
     *      summary="New genre",
     *      description="Put a new genre in the database.",
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="name of the new genre",
     *          required=true
     *      ),
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
    public function store(GenreRequest $request)
    {
        $newGenre = Genre::addGenre($request->all());
        return response()->json($newGenre, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/genres/{id}",
     *     operationId="GetAGenre",
     *     tags={"genre"},
     *     security={{"bearerAuth":{}}},
     *
     *     summary="Get A Genre",
     *     description="Get A Genre",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Genre id",
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
     *      response=404,
     *      description="not found"
     *  ),
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
        $book = Genre::find($id);
        return ($book) ? new GenreResource($book) : response()->json('Invalid genre', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Patch(
     *      path="/genres/{id}",
     *      operationId="UpdateGenre",
     *      tags={"genre"},
     *     security={{"bearerAuth":{}}},
     *
     *      summary="Update Genre",
     *      description="Update a genre in the database.",
     *
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Genre id",
     *         required=true
     *     ),
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="name",
     *          required=true
     *      ),
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
    public function update(GenreRequest $request, int $id)
    {
        $genre= Genre::find($id);
        if($genre){
            $updatedGenre = Genre::updateGenre($genre, $request->all());
            return response()->json($updatedGenre, 200);
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
     *     path="/genre/{id}",
     *     operationId="DeleteAGenre",
     *     tags={"genre"},
     *     security={{"bearerAuth":{}}},
     *
     *     summary="Delete A Genre",
     *     description="Delete A Genre",
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
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     *  )
     */
    public function destroy(int $id)
    {
        $genre= Genre::find($id);
        if($genre){
            $genre->books()->detach();
            $genre->delete();

            return response()->json('', 204);
        }
        return response()->json('Genre not found', 404);
    }
}
