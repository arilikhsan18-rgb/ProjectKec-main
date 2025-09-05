@extends('layouts.app')

@section('title', 'Edit Data Geografis')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Edit Data Geografis</h1>
    <p class="mb-4">Perbarui informasi kondisi geografis untuk wilayah Anda.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data Geografis</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('geografis.update', $geografis->id) }}" method="POST">
                @method('PUT')
                {{-- Memanggil form parsial --}}
                @include('pages.geografis._form', ['tombol' => 'Update'])
            </form>
        </div>
    </div>
@endsection