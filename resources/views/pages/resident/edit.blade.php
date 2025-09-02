@extends('layouts.app')

@section('title', 'Edit Data Kependudukan')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data Kependudukan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('resident.update', $resident) }}" method="POST">
                @method('PUT') {{-- Penting untuk proses update --}}
                
                {{-- Memanggil form partial yang sama, tapi data $resident akan otomatis terisi --}}
                @include('pages.resident._form', ['tombol' => 'Update'])
            </form>
        </div>
    </div>
@endsection
