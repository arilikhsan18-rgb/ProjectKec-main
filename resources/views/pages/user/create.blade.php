@extends('layouts.app')

@section('content')

{{-- Tombol Kembali --}}
<a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm shadow-sm mb-3">
    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
</a>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Pengguna Baru</h6>
    </div>
    <div class="card-body">

        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="form-group row">
                <div class="col-md-6 mb-3">
                    <label for="name">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 mb-3">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 mb-3">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="role_id">Role <span class="text-danger">*</span></label>
                    <select class="form-control" id="role_id" name="role_id" required>
                        <option value="">-- Pilih Role --</option>
                        {{-- Data $roles dikirim dari Controller --}}
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>
@endsection