@extends('layouts.app')

@section('title', 'Tambah Data Kependudukan')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Data Kependudukan</h6>
            <a href="{{ route('resident.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            {{-- Arahkan form ke route yang benar --}}
            <form action="{{ route('resident.store') }}" method="POST">
                
                {{-- 
                    Memanggil form partial yang sudah kita buat di `_form.blade.php`.
                    Kita juga mengirim variabel 'tombol' agar tulisan di tombol submit bisa diubah.
                --}}
                @include('pages.resident._form', ['tombol' => 'Tambah Data'])

            </form>
        </div>
    </div>
@endsection

