@extends('errors.layout')

@section('code', '503')
@section('heading', 'Service unavailable')
@section('message', 'TABAS is temporarily unavailable. Please try again after the service comes back online.')

@section('actions')
    <a class="button primary" href="{{ url()->current() }}">Refresh page</a>
    <a class="button secondary" href="{{ url('/') }}">Return home</a>
@endsection
