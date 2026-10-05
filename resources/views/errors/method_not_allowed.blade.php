@extends('layouts.app') {{-- ganti sesuai layout kamu --}}

@section('title', 'Method Not Allowed')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4 text-danger">405 - Method Not Allowed</h1>
    <p class="lead">The request method is not allowed. Please go back and try again.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Back to Previous Page</a>
</div>
@endsection