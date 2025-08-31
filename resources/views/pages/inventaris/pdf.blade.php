<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Inventaris Ruangan</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 9px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 4px; text-align: left;}
        .text-center { text-align: center; }
        .align-middle { vertical-align: middle; }
        .header-table { width: 100%; border: none; margin-bottom: 15px; }
        .header-table td { border: none; }
        .header-title { text-align: center; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr><td class="header-title" colspan="2">KARTU INVENTARIS RUANGAN</td></tr>
        <tr><td style="height:10px;"></td></tr>
        <tr>
            <td style="width: 50%;">
                <table style="border: none;">
                    <tr><td style="width: 80px;"><b>KAB/KOTA</b></td><td>: TASIKMALAYA</td></tr>
                    <tr><td><b>PROVINSI</b></td><td>: JAWA BARAT</td></tr>
                    <tr><td><b>SKPD</b></td><td>: KECAMATAN TAWANG</td></tr>
                    <tr><td><b>RUANGAN</b></td><td>: {{ $selectedRoom->nama_ruangan ?? 'SEMUA RUANGAN' }}</td></tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top;">
                 <table style="border: none;">
                    <tr><td style="width: 100px;"><b>NO. KODE LOKASI</b></td><td>: .................................</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead class="text-center">
            <tr>
                <th rowspan="2" class="align-middle">No Urut</th>
                <th rowspan="2" class="align-middle">Nama Barang / Jenis Barang</th>
                <th rowspan="2" class="align-middle">Merk / Model</th>
                <th rowspan="2" class="align-middle">Bahan</th>
                <th rowspan="2" class="align-middle">Tahun Pembelian</th>
                <th rowspan="2" class="align-middle">No. Kode Barang</th>
                <th rowspan="2" class="align-middle">Jumlah Barang</th>
                <th rowspan="2" class="align-middle">Harga Beli / Perolehan (Rp)</th>
                <th colspan="3">Keadaan Barang</th>
                <th rowspan="2" class="align-middle">Keterangan</th>
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
                    <td style="text-align: right;">{{ number_format($item->harga_perolehan, 0, ',', '.') }}</td>
                    
                    {{-- Kolom Kondisi dipisah menjadi tiga --}}
                    <td class="text-center">@if($item->kondisi == 'B') B @endif</td>
                    <td class="text-center">@if($item->kondisi == 'KB') KB @endif</td>
                    <td class="text-center">@if($item->kondisi == 'RB') RB @endif</td>
                    
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>