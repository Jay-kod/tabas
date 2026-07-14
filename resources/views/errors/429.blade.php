@extends('errors.layout')

@section('code', '429')
@section('heading', 'Too many requests')
@section('message', 'The system is protecting itself from too many repeated requests. Please wait a moment and try again.')

@section('actions')
    <a class="button primary" href="{{ url()->current() }}">Try again</a>
    <a class="button secondary" href="{{ url('/') }}">Return home</a>
@endsection
