@extends('errors.layout')

@section('code', '401')
@section('heading', 'Unauthorized')
@section('message', 'You need to sign in again before continuing.')

@section('actions')
    <a class="button primary" href="{{ route('login') }}">Go to login</a>
    <a class="button secondary" href="{{ url('/') }}">Return home</a>
@endsection
