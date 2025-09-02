@extends('layouts.app')

@section('title', 'Tambah Data Status Pekerjaan')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Data Status Pekerjaan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('occupation.store') }}" method="POST">
                @include('pages.occupation._form', ['tombol' => 'Tambah'])
            </form>
        </div>
    </div>
@endsection
