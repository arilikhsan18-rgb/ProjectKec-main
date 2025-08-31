@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        {{-- Bagian card-header tetap sama --}}
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="m-0 font-weight-bold text-primary">Data Ruangan {{ $selectedRoom->name ?? 'SEMUA RUANGAN' }}</h4>
                <div>
                    {{-- Tombol Tambah --}}
                    {{-- INI KODE YANG BENAR --}}
                    <a href="{{ route('inventaris.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </a>
                    {{-- Tombol Export --}}
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('inventaris.pdf', request()->query()) }}"><i class="fas fa-file-pdf text-danger"></i> Export ke PDF</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel text-success"></i> Export ke Excel</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="text-center align-middle" style="background-color: #f2f2f2;">
                        <tr>
                            <th rowspan="2">No Urut</th>
                            <th rowspan="2">Nama Barang / Jenis Barang</th>
                            <th rowspan="2">Merk / Model</th>
                            <th rowspan="2">Bahan</th>
                            <th rowspan="2">Tahun Pembelian</th>
                            <th rowspan="2">No. Kode Barang</th>
                            <th rowspan="2">Jumlah</th>
                            <th rowspan="2">Harga (Rp)</th>
                            <th colspan="3">Keadaan Barang</th>
                            <th rowspan="2">Keterangan</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Baik (B)</th>
                            <th>Kurang Baik (KB)</th>
                            <th>Rusak Berat (RB)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventaris as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->merk_model ?? '-' }}</td>
                                <td>{{ $item->bahan ?? '-' }}</td>
                                <td class="text-center">{{ $item->tahun_pembelian }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td class="text-center">{{ $item->jumlah }}</td>
                                <td class="text-end">{{ number_format($item->harga_perolehan, 0, ',', '.') }}</td>
                                <td class="text-center">@if($item->kondisi == 'B') ✓ @endif</td>
                                <td class="text-center">@if($item->kondisi == 'KB') ✓ @endif</td>
                                <td class="text-center">@if($item->kondisi == 'RB') ✓ @endif</td>
                                <td>{{ $item->keterangan }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        {{-- Tombol Pindah Ruangan --}}
                                        <button type="button" class="btn btn-info btn-sm mr-1" data-bs-toggle="modal" data-bs-target="#pindahModal" data-id="{{ $item->id }}" data-nama="{{ $item->nama_barang }}">
                                            <i class="fas fa-people-carry"></i>
                                        </button>
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-warning btn-sm mr-1">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        {{-- Tombol Hapus --}}
                                        <button type="button" class="btn btn-danger btn-sm mr-1" data-bs-toggle="modal" data-bs-target="#hapusModal" data-id="{{ $item->id }}" data-nama="{{ $item->nama_barang }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Tidak ada data inventaris ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pindah Ruangan -->
<div class="modal fade" id="pindahModal" tabindex="-1" aria-labelledby="pindahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pindahModalLabel">Pindahkan Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pindahForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Pindahkan barang <strong id="namaBarangPindah"></strong> ke ruangan:</p>
                    <div class="mb-3">
                        <label for="ruangan_id" class="form-label">Ruangan Tujuan</label>
                        {{-- Pastikan variabel $ruangans dikirim dari controller --}}
                        <select class="form-select" name="ruangan_id" id="ruangan_id" required>
                            <option value="" disabled selected>-- Pilih Ruangan --</option>
                            @foreach ($rooms as $ruangan)
                                <option value="{{ $ruangan->id }}">{{ $ruangan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Pindahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus data <strong id="namaBarangHapus"></strong>?
      </div>
      <div class="modal-footer">
        <form id="hapusForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk modal hapus
    const hapusModal = document.getElementById('hapusModal');
    hapusModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-id');
        const itemNama = button.getAttribute('data-nama');
        
        const modalBodyNama = hapusModal.querySelector('#namaBarangHapus');
        const hapusForm = hapusModal.querySelector('#hapusForm');
        
        modalBodyNama.textContent = itemNama;
        hapusForm.action = `/inventaris/${itemId}`; // URL disesuaikan dengan route Anda
    });

    // Script untuk modal pindah ruangan
    const pindahModal = document.getElementById('pindahModal');
    pindahModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-id');
        const itemNama = button.getAttribute('data-nama');
        
        const modalBodyNama = pindahModal.querySelector('#namaBarangPindah');
        const pindahForm = pindahModal.querySelector('#pindahForm');
        
        modalBodyNama.textContent = itemNama;
        pindahForm.action = `/inventaris/${itemId}/pindah`; // URL untuk action pindah
    });
</script>
@endpush
