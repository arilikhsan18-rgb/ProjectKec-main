<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Warga</title>
    <style>
        /* Gaya dasar untuk keseluruhan dokumen */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 12px;
        }

        /* Gaya untuk header laporan */
        .report-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .report-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .report-header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }

        /* Gaya untuk setiap section tabel */
        .report-section {
            margin-bottom: 30px;
        }
        .report-section h2 {
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        /* Gaya untuk tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        thead {
            background-color: #f2f2f2;
        }
        th {
            font-weight: bold;
        }

        /* Gaya untuk baris total di akhir tabel */
        tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }

        /* Gaya untuk footer halaman */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="report-header">
        <h1>Laporan Keseluruhan Data Warga</h1>
        <p>Dicetak pada: {{ date('d F Y') }}</p>
    </div>

    <!-- 1. Tabel Status Kependudukan -->
    <div class="report-section">
        <h2>Laporan Berdasarkan Status Kependudukan</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th>Status Kependudukan</th>
                    <th style="width: 20%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($residents as $res)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $res->status_tinggal }}</td>
                    <td>{{ $res->jumlah }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak Ada Data</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total Data</strong></td>
                    <td><strong>{{ $total_residents }} Warga</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- 2. Tabel Tahun Kelahiran -->
    <div class="report-section">
        <h2>Laporan Berdasarkan Tahun Kelahiran</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th>Tahun Kelahiran</th>
                    <th style="width: 20%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($years as $y)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $y->tahun_lahir }}</td>
                    <td>{{ $y->jumlah }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak Ada Data</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total Data</strong></td>
                    <td><strong>{{ $total_years }} Warga</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- 3. Tabel Status Pekerjaan -->
    <div class="report-section">
        <h2>Laporan Berdasarkan Status Pekerjaan</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th>Status Pekerjaan</th>
                    <th style="width: 20%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($occupations as $oc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $oc->pekerjaan }}</td>
                    <td>{{ $oc->jumlah }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak Ada Data</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total Data</strong></td>
                    <td><strong>{{ $total_occupations }} Warga</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- 4. Tabel Status Pendidikan -->
    <div class="report-section">
        <h2>Laporan Berdasarkan Status Pendidikan</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th>Status Pendidikan</th>
                    <th style="width: 20%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($educations as $education)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $education->sekolah }}</td>
                    <td>{{ $education->jumlah }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak Ada Data</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total Data</strong></td>
                    <td><strong>{{ $total_educations }} Warga</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem.
    </div>

</body>
</html>
