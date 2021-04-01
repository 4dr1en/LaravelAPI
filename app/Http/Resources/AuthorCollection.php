<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        /*$return = [];
        foreach ($this as $author) {
            array_push($return,[
                'id' => $author->id,
                'name' => $author->name,
            ]);
        }
        return  $return;*/
    }
}
