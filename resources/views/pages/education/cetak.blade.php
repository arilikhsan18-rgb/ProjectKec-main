<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Warga</title>
    {{-- Ini adalah style inline agar tampilan tetap rapi saat di-convert ke PDF --}}
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid #999;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 14px;
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Data Warga</h1>
        <h2>Berdasarkan Status Kependidikan</h2>
        {{-- Menampilkan tanggal saat ini secara dinamis --}}
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Status Kependudukan</th>
                <th style="width: 20%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @if (count($educations) > 0)
                @foreach ($educations as $education)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $education->sekolah }}</td>
                    <td>{{ $education->jumlah }} Warga</td>
                </tr>
                @endforeach
                {{-- Baris untuk menampilkan total --}}
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Total Jumlah Warga</td>
                    <td>{{ $total_jumlah }} Warga</td>
                </tr>
            @else
                <tr>
                    <td colspan="3" class="no-data">
                        Tidak ada data yang tersedia.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
