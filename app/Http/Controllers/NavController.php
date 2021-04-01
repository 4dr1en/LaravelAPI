<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;


class NavController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function list()
    {
        $books = Book::all();
        return view('list', ['books' => $books]);
    }

    public function book($id)
    {
        $book = Book::getOne($id);
        return view('book', ['book' => $book]);
    }

    public function add()
    {
        $authors = Author::all()->sortBy('name');
        $genres = Genre::all()->sortBy('name');
        return view('add', ['authors' => $authors, 'genres' => $genres]);
    }

    public function updateBook($id)
    {
        $book = Book::find($id);
        $authors = Author::all()->sortBy('name');
        $genres = Genre::all()->sortBy('name');
        return view('update', ['book' => $book, 'authors' => $authors, 'genres' => $genres]);
    }
}
