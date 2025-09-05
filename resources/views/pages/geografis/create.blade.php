@extends('layouts.app')

@section('title', 'Tambah Data Geografis')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Geografis</h1>
    <p class="mb-4">Masukkan informasi kondisi geografis untuk wilayah Anda.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Data Geografis</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('geografis.store') }}" method="POST">
                {{-- Memanggil form parsial --}}
                @include('pages.geografis._form', ['tombol' => 'Simpan'])
            </form>
        </div>
    </div>
@endsection