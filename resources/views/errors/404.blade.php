@extends('errors.layout')

@section('code', '404')
@section('heading', 'Page not found')
@section('message', 'The page you requested could not be located in TABAS.')

@section('actions')
    <a class="button primary" href="{{ url('/') }}">Return home</a>
    <a class="button secondary" href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}">Go back</a>
@endsection
