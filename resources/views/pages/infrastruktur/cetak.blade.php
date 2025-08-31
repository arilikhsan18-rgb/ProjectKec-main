<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Infrastruktur</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .image-cell img {
            max-width: 100px;
            max-height: 80px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Data Infrastruktur</h1>
        <p>SIMDAWANI - Sistem Informasi Manajemen Data Warga RT/RW</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Alamat</th>
                <th>Ukuran</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($infrastrukturs as $data)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center image-cell">
                        {{-- Penting: Gunakan public_path() untuk gambar di dompdf --}}
                        @if($data->gambar && file_exists(public_path('storage/' . $data->gambar)))
                            <img src="{{ public_path('storage/' . $data->gambar) }}" alt="Gambar Infrastruktur">
                        @else
                            <span>Tidak ada gambar</span>
                        @endif
                    </td>
                    <td>{{ $data->alamat }}</td>
                    <td>{{ $data->ukuran }}</td>
                    <td>{{ $data->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak Ada Data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
