@extends('errors.layout')

@section('code', '419')
@section('heading', 'Session expired')
@section('message', 'Your session timed out or the form token expired. Please try again.')

@section('actions')
    <a class="button primary" href="{{ url()->current() }}">Refresh page</a>
    <a class="button secondary" href="{{ url('/') }}">Return home</a>
@endsection
