@extends('layouts.app')

@section('title', 'Edit Data Tahun Kelahiran')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data Tahun Kelahiran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('year.update', $year) }}" method="POST">
                @method('PUT')
                @include('pages.year._form', ['tombol' => 'Update'])
            </form>
        </div>
    </div>
@endsection
