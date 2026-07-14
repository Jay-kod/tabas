@extends('errors.layout')

@section('code', '500')
@section('heading', 'Server error')
@section('message', 'Something went wrong while processing the request. The issue has been logged for review.')

@section('actions')
    <a class="button primary" href="{{ url()->current() }}">Retry request</a>
    <a class="button secondary" href="{{ url('/') }}">Return home</a>
@endsection
