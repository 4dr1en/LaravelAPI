<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /*$return = [];
        foreach ($this as $book) {
            array_push( $return,[
                'id' => $book->id,
                'title' => $book->title,
                'author_id' => $book->author_id,
            ]);
        }
        return  $return;*/
        return parent::toArray($request);
    }
}
