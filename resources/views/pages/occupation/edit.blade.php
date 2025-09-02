@extends('layouts.app')

@section('title', 'Edit Data Status Pekerjaan')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data Status Pekerjaan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('occupation.update', $occupation) }}" method="POST">
                @method('PUT')
                @include('pages.occupation._form', ['tombol' => 'Update'])
            </form>
        </div>
    </div>
@endsection
