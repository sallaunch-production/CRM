@extends('layouts.app') {{-- ganti sesuai layout kamu --}}

@section('title', 'Akses Tidak Diperbolehkan')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4 text-danger">405 - Akses Tidak Diperbolehkan</h1>
    <p class="lead">Metode yang digunakan tidak sesuai. Silakan kembali dan coba lagi dengan cara yang benar.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Kembali ke Halaman Sebelumnya</a>
</div>
@endsection