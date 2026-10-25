<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Kendali Bimbingan - {{ $tugasAkhir->mahasiswa->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            padding: 20px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
            font-size: 10pt;
            font-style: italic;
        }
        .metadata-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .metadata-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .metadata-table td.label {
            width: 25%;
            font-weight: bold;
        }
        .metadata-table td.separator {
            width: 3%;
        }
        .logbook-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .logbook-table th, .logbook-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 11pt;
        }
        .logbook-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .logbook-table td.center {
            text-align: center;
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 40%;
            text-align: center;
        }
        .signature-space {
            height: 70px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        .btn-print {
            padding: 8px 16px;
            background-color: #1e293b;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 10pt;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Dokumen</button>
    </div>

    <!-- Header Dokumen Kampus -->
    <div class="header">
        <h3>Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi</h3>
        <h2>Politeknik Negeri Padang</h2>
        <h3 style="font-weight: bold; margin-top: 10px;">Kartu Kendali Bimbingan Tugas Akhir</h3>
        <p>Alamat: Kampus Politeknik Negeri Padang, Limau Manis, Padang, Sumatera Barat</p>
    </div>

    <!-- Informasi Mahasiswa & TA -->
    <table class="metadata-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="separator">:</td>
            <td>{{ $tugasAkhir->mahasiswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">NIM / Program Studi</td>
            <td class="separator">:</td>
            <td>{{ $tugasAkhir->mahasiswa->nim }} / {{ $tugasAkhir->mahasiswa->program_studi }}</td>
        </tr>
        <tr>
            <td class="label">Judul Tugas Akhir</td>
            <td class="separator">:</td>
            <td>"{{ $tugasAkhir->judul }}"</td>
        </tr>
        <tr>
            <td class="label">Dosen Pembimbing</td>
            <td class="separator">:</td>
            <td>{{ $tugasAkhir->dosen->nama_lengkap }} (NIP: {{ $tugasAkhir->dosen->nip }})</td>
        </tr>
    </table>

    <!-- Tabel Catatan Bimbingan -->
    <table class="logbook-table">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 40%;">Aktivitas / Kegiatan Mahasiswa</th>
                <th style="width: 30%;">Catatan & Arahan Pembimbing</th>
                <th style="width: 10%;">Validasi</th>
            </tr>
        </thead>
        <tbody>
            @if($tugasAkhir->logbooks->count() > 0)
                @foreach($tugasAkhir->logbooks as $index => $log)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td class="center">{{ date('d-m-Y', strtotime($log->tanggal_bimbingan)) }}</td>
                        <td>{{ $log->kegiatan }}</td>
                        <td>{{ $log->catatan_dosen ?? '-' }}</td>
                        <td class="center" style="font-weight: bold; color: {{ $log->status_validasi ? 'green' : 'red' }}">
                            {{ $log->status_validasi ? 'VALID' : 'BELUM' }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="center" style="font-style: italic; color: #888;">Belum ada riwayat bimbingan logbook yang tercatat.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Tanda Tangan Pembimbing -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p>Ketua Jurusan / Kaprodi</p>
            <div class="signature-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">( ________________________ )</p>
            <p>NIP. ______________________</p>
        </div>
        <div class="signature-box">
            <p>Padang, {{ date('d F Y') }}</p>
            <p>Dosen Pembimbing Utama,</p>
            <div class="signature-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">{{ $tugasAkhir->dosen->nama_lengkap }}</p>
            <p>NIP. {{ $tugasAkhir->dosen->nip }}</p>
        </div>
    </div>

    <script>
        // Buka dialog print secara otomatis saat di-load
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
