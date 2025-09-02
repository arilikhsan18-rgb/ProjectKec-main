@extends('layouts.app')

@section('title', 'Tambah Data Tahun Kelahiran')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Data Tahun Kelahiran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('year.store') }}" method="POST">
                @include('pages.year._form', ['tombol' => 'Tambah'])
            </form>
        </div>
    </div>
@endsection
