@extends('layouts.base')

@section('title', 'Accueil')


@section('css')
<link rel="stylesheet" href="{{ asset('/css/home.css') }}">
@endsection

@section('content')
<img src="{{ asset('/img/library.png') }}" alt="">
@endsection
