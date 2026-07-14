@extends('errors.layout')

@section('code', '403')
@section('heading', 'Access denied')
@section('message', 'Your account does not have permission to access this area.')

@section('actions')
    <a class="button primary" href="{{ url('/') }}">Return home</a>
    <a class="button secondary" href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}">Go back</a>
@endsection
