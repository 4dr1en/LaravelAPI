<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\light\GenreLightResource;
use App\Http\Resources\light\AuthorLightResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'author' => new AuthorLightResource($this->author),
            'publication_year' => $this->publication_year,
            'pages_nb' => $this->pages_nb,
            'genres' => GenreLightResource::collection($this->genres)
        ];
    }
}
