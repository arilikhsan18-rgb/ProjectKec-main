@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Pengguna</h1>
    <a href="{{ route('user.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengguna
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success shadow" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Akun Pengguna</h6>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-12">
                <form action="{{ route('user.index') }}" method="GET" class="w-100">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <label for="limit" class="mr-2 mb-0">Tampilkan</label>
                            <select class="form-control form-control-sm" id="limit" name="limit" onchange="this.form.submit()">
                                <option value="10" @if(request('limit', 10) == 10) selected @endif>10</option>
                                <option value="25" @if(request('limit') == 25) selected @endif>25</option>
                                <option value="50" @if(request('limit') == 50) selected @endif>50</option>
                            </select>
                            <span class="ml-2">entri</span>
                        </div>
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, username, email..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Wilayah (Kel/RW/RT)</th>
                        <th>Role</th>
                        <th>Induk Akun</th>
                        <th>Status</th>
                        <th width="100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            {{ $user->nama_kelurahan ?? '-' }} / 
                            RW {{ $user->nomor_rw ?? '-' }} / 
                            RT {{ $user->nomor_rt ?? '-' }}
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $user->role->name ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $user->parent->name ?? 'Tidak Ada' }}</td>
                        <td>
                            @if($user->status == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-around">
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning mr-1" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn mr-1" 
                                    data-toggle="modal" 
                                    data-target="#confirmationDelete" 
                                    data-id="{{ $user->id }}" 
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            Tidak ada data untuk ditampilkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @include('pages.user.confirmation-delete') 
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{-- REVISI: Menambahkan view 'pagination::bootstrap-4' agar stylingnya benar --}}
            {!! $users->appends(request()->query())->links('pagination::bootstrap-4') !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.delete-btn').on('click', function () {
            let userId = $(this).data('id');
            let url = "{{ url('user') }}/" + userId;
            $('#deleteForm').attr('action', url);
        });
    });
</script>
@endpush