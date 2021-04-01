@extends('layouts.base')
@section('title', 'Livres')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/list.css') }}">
@endsection

@section('content')
    <h1 class="title">Liste des livres</h1>
    <div class="list">
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Genre</th>
                    <th scope="col">MAJ</th>
                    <th scope="col">Supp</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($books as $book)
                <tr>
                    <th scope="row">{{ $book->id }}</th>
                    <td><a href="/book/{{ $book->id }}">{{ $book->title }}</a></td>
                    <td>{{ $book->author->name }}</td>
                    <td>
                        @foreach($book->genres as $genre)
                            <p>{{ $genre->name }}</p>
                        @endforeach
                    </td>
                    <td><a class="btn btn-info" href="/updateBook/{{ $book->id }}">U</a></td>
                    <td>
                        <form action="/deleteBook" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $book->id }}">
                            <input type="submit" class="btn btn-danger" value="X">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
