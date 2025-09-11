@extends('layouts.app')

@section('title', 'Tambah Data Kependudukan (Massal)')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Tambah Data Kependudukan (Massal)</h1>
    <p class="mb-4">Anda dapat menambahkan beberapa data penduduk sekaligus dalam satu formulir. Klik tombol "Tambah Baris Isian" untuk menambahkan form baru.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('penduduk.store') }}" method="POST">
                @csrf
                {{-- Menampilkan Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p><strong>Terjadi kesalahan validasi:</strong></p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Kontainer untuk baris-baris form dinamis --}}
                <div id="form-container">
                    
                    <!-- Baris Form Pertama (Template) -->
                    <div class="form-row-container border rounded p-3 mb-3">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="year">Tahun Lahir</label>
                                {{-- Nama input diubah menjadi array: year[] --}}
                                <input type="number" class="form-control" name="year[]" value="{{ old('year.0') }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="gender">Jenis Kelamin</label>
                                <select class="form-control" name="gender[]" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="laki-laki">Laki-laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="resident">Status Kependudukan</label>
                                <select class="form-control" name="resident[]" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="tetap">Tetap</option>
                                    <option value="pindahan">Pindahan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="religion">Agama</label>
                                <input type="text" class="form-control" name="religion[]" value="{{ old('religion.0') }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="education">Status Pendidikan</label>
                                <select class="form-control" name="education[]" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="belum sekolah">Belum Sekolah</option>
                                    <option value="masih sekolah">Masih Sekolah</option>
                                    <option value="putus sekolah">Putus Sekolah</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="occupation">Status Pekerjaan</label>
                                <select class="form-control" name="occupation[]" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="bekerja">Bekerja</option>
                                    <option value="tidak bekerja">Tidak Bekerja</option>
                                    <option value="usaha">Usaha</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lampid">Jenis Laporan (Lampid) <span class="text-muted">(Opsional)</span></label>
                                <select class="form-control" name="lampid[]">
                                    <option value="">-- Pilih --</option>
                                    <option value="kelahiran">Kelahiran</option>
                                    <option value="kematian">Kematian</option>
                                    <option value="pindah">Pindah</option>
                                    <option value="datang">Datang</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir Baris Form Pertama -->

                </div>

                {{-- Tombol untuk menambah baris dan submit --}}
                <div class="d-flex justify-content-between">
                    <div>
                        <button type="button" id="add-row-btn" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Baris Isian</button>
                    </div>
                    <div>
                        <a href="{{ route('penduduk.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Semua Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formContainer = document.getElementById('form-container');
    const addRowBtn = document.getElementById('add-row-btn');

    // Ambil template dari baris form pertama
    const rowTemplate = formContainer.querySelector('.form-row-container').cloneNode(true);

    // Fungsi untuk menambahkan tombol hapus
    const addRemoveButton = (row) => {
        const removeBtnContainer = document.createElement('div');
        removeBtnContainer.classList.add('text-right', 'mb-2');
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.classList.add('btn', 'btn-danger', 'btn-sm');
        removeBtn.innerHTML = '<i class="fa fa-trash"></i> Hapus Baris Ini';
        
        removeBtn.onclick = function() {
            row.remove();
        };

        removeBtnContainer.appendChild(removeBtn);
        // Sisipkan tombol hapus di bagian atas baris
        row.prepend(removeBtnContainer);
    };

    addRowBtn.addEventListener('click', function () {
        // Buat klon dari template
        const newRow = rowTemplate.cloneNode(true);

        // Kosongkan semua nilai input di baris baru
        newRow.querySelectorAll('input, select').forEach(input => {
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });

        // Tambahkan tombol hapus ke baris baru
        addRemoveButton(newRow);
        
        // Tambahkan baris baru ke dalam kontainer
        formContainer.appendChild(newRow);
    });
});
</script>
@endpush

