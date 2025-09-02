@extends('layouts.app')

@section('title', 'Edit Data Status Pendidikan')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data Status Pendidikan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('education.update', $education) }}" method="POST">
                @method('PUT')
                @include('pages.education._form', ['tombol' => 'Update'])
            </form>
        </div>
    </div>
@endsection
