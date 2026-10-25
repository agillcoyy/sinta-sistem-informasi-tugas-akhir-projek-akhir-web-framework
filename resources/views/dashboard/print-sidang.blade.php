<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Sidang TA - {{ $sidang->tugasAkhir->mahasiswa->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            padding: 30px;
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
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 30px;
        }
        .metadata-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .metadata-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .metadata-table td.label {
            width: 30%;
            font-weight: bold;
        }
        .metadata-table td.separator {
            width: 3%;
        }
        .score-box {
            border: 2px solid #000;
            padding: 20px;
            margin-bottom: 30px;
            background-color: #fafafa;
            border-radius: 5px;
        }
        .score-row {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        .score-item {
            display: flex;
            flex-direction: column;
        }
        .score-value {
            font-size: 24pt;
            font-weight: bold;
            margin-top: 5px;
        }
        .notes-section {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 40px;
            min-height: 80px;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-space {
            height: 75px;
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
            font-size: 10pt;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Berita Acara</button>
    </div>

    <!-- Header Dokumen Kampus -->
    <div class="header">
        <h3>Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi</h3>
        <h2>Politeknik Negeri Padang</h2>
        <p>Alamat: Kampus Politeknik Negeri Padang, Limau Manis, Padang, Sumatera Barat</p>
    </div>

    <div class="title">
        Berita Acara Ujian Sidang Tugas Akhir
    </div>

    <p>Pada hari ini <strong>{{ date('l', strtotime($sidang->tanggal_sidang)) }}</strong>, tanggal <strong>{{ date('d F Y', strtotime($sidang->tanggal_sidang)) }}</strong>, telah dilaksanakan Ujian Sidang Tugas Akhir bagi mahasiswa berikut:</p>

    <!-- Informasi Mahasiswa -->
    <table class="metadata-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="separator">:</td>
            <td><strong>{{ $sidang->tugasAkhir->mahasiswa->nama_lengkap }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIM (Nomor Induk)</td>
            <td class="separator">:</td>
            <td>{{ $sidang->tugasAkhir->mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td class="label">Program Studi / Jurusan</td>
            <td class="separator">:</td>
            <td>{{ $sidang->tugasAkhir->mahasiswa->program_studi }} / Teknologi Informasi</td>
        </tr>
        <tr>
            <td class="label">Judul Tugas Akhir</td>
            <td class="separator">:</td>
            <td>"{{ $sidang->tugasAkhir->judul }}"</td>
        </tr>
        <tr>
            <td class="label">Dosen Pembimbing</td>
            <td class="separator">:</td>
            <td>{{ $sidang->tugasAkhir->dosen->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">Dosen Penguji Utama</td>
            <td class="separator">:</td>
            <td>{{ $sidang->dosenPenguji->nama_lengkap }}</td>
        </tr>
    </table>

    <p>Berdasarkan penilaian yang komprehensif, dewan penguji memutuskan nilai hasil ujian sebagai berikut:</p>

    <!-- Kotak Hasil Nilai & Kelulusan -->
    <div class="score-box">
        <div class="score-row">
            <div class="score-item">
                <span style="font-size: 10pt; text-transform: uppercase; font-weight: bold; color: #555;">Nilai Angka</span>
                <span class="score-value">{{ $sidang->nilai_angka }}</span>
            </div>
            <div class="score-item">
                <span style="font-size: 10pt; text-transform: uppercase; font-weight: bold; color: #555;">Nilai Huruf</span>
                <span class="score-value">{{ $sidang->nilai_huruf }}</span>
            </div>
            <div class="score-item">
                <span style="font-size: 10pt; text-transform: uppercase; font-weight: bold; color: #555;">Hasil Kelulusan</span>
                <span class="score-value" style="color: {{ $sidang->status_kelulusan === 'lulus' ? 'green' : 'red' }}">
                    {{ strtoupper($sidang->status_kelulusan) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Catatan Revisi / Masukan Penguji -->
    <div class="notes-section">
        <strong>Catatan / Saran Perbaikan dari Penguji:</strong>
        <p style="margin-top: 5px; font-style: italic;">{{ $sidang->catatan ?? 'Tidak ada catatan khusus.' }}</p>
    </div>

    <p>Demikian berita acara ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>

    <!-- Tanda Tangan Pembimbing & Penguji -->
    <div class="signature-section" style="margin-top: 50px;">
        <div class="signature-box">
            <p>Dosen Pembimbing,</p>
            <div class="signature-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">{{ $sidang->tugasAkhir->dosen->nama_lengkap }}</p>
            <p>NIP. {{ $sidang->tugasAkhir->dosen->nip }}</p>
        </div>
        <div class="signature-box">
            <p>Dosen Penguji,</p>
            <div class="signature-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">{{ $sidang->dosenPenguji->nama_lengkap }}</p>
            <p>NIP. {{ $sidang->dosenPenguji->nip }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
