<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public static function addGenre($data)
    {
        $genre = new Genre;
        $genre->name = $data['name'];
        $genre->save();

        return $genre;
    }

    public static function updateGenre($genre, $data)
    {
        $genre->name = $data['name'];
        $genre->save();

        return $genre;
    }

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
