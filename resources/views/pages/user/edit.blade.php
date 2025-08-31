@extends('layouts.app')

@section('content')

<a href="{{ route('user.index') }}" class="btn btn-secondary btn-sm shadow-sm mb-3">
    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
</a>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Pengguna: {{ $user->name }}</h6>
    </div>
    <div class="card-body">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Method untuk update --}}

            <div class="form-group row">
                <div class="col-md-6 mb-3">
                    <label for="name">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 mb-3">
                    <label for="password">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6 mb-3">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak aktif" {{ old('status', $user->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="role_id">Role <span class="text-danger">*</span></label>
                    <select class="form-control" id="role_id" name="role_id" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Update
            </button>
        </form>
    </div>
</div>
@endsection