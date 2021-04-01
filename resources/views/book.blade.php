@extends('layouts.base')
@section('title', 'Livre')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/book.css') }}">
@endsection

@section('content')
    <h1 class="title">Détail du livre</h1>
    <div id="card">
        <div class="card border-dark mb-3" style="max-width: 18rem;">
            <div class="card-header">{{ $book->title }}</div>
            <div class="card-body text-dark flex">
                <p>Auteur :</p>
                <p class="card-text">{{ $book->author->name }}</p>
            </div>
            <div class="card-body text-dark flex">
                <p>Genre :</p>
                <p class="card-text">{{ $book->genre }}</p>
            </div>
            <div class="card-body text-dark flex">
                <p>Année de publication :</p>
                <p class="card-text">{{ $book->publication_year }}</p>
            </div>
            <div class="card-body text-dark flex">
                <p>Nombre de pages :</p>
                <p class="card-text">{{ $book->pages_nb }}</p>
            </div>
            <div class="card-body text-dark">
                <p>Résumé :</p>
                <p class="card-text">{{ $book->description }}</p>
            </div>
        </div>
    </div>
@endsection
