<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{ asset('image/logo/logo_ung.png') }}" type="image">
    <title>SPAK | Sistem Pengelolaan Administrasi Kemahasiswaan</title>
    <style>
        body{
            font-size: 16px;
        }
        .logo{
            height:90px;
            width: 90px;
        }
        .container{
            margin: 0 auto;
            width: 690px;
            padding-left: 10px;
            padding-left: 10px;
        }
        .kop-title{
            font-size: 18px;
            overflow: hidden;
        }
        .kop-title-address{
            font-size: 14px;
        }
        .text-center{
            text-align: center;
        }
         .text-right{
            text-align: right;
        }
        .m-0{
            margin: 0;
        }
        table{
            width: 100%;
        }
        .table{
            border-collapse:collapse;
            border-spacing:0;
            border:1px solid black;
        }
        .table-margin{
            margin-bottom: 10px;
            margin-top: 10px;
        }
        .border{
            height: 1px;
            margin-top:5px;
            border-top: 3px solid black;
            border-bottom: 1px solid black;
        }
        .mt-3{
            margin-top: 30px;
        }
        .mt-1{
            margin-top: 10px;
        }
        .underline{
            padding-bottom: 1px;
            border-bottom: 2px solid black;
        }
        .content{
            padding-left: 15px;
            padding-right: 15px;
            line-height: 1.6;
            text-align: justify;
            margin-top:10px;
        }
        .data-table{
            width: 160px;
        }
        .signature{
            line-height: 1.3;
            margin-top: 20px;
            margin-right: 30px;
        }
        .signature-content{
            float: right;
        }
        .tanda-tangan-margin{
            margin-left:-25px;
        }
        .tanda-tangan{
            width: 230px;
            height: 150px;
        }
        p{
            margin:0;
        }
        .tahapan-table{
            line-height:1.1;
        }
    </style>
</head>
<body>
   <div class="container">
        @include('surat.kop_surat')
        <div class="border"></div>
        <h2 class="text-center" style="margin-bottom:30px">Laporan Surat Keluar <br> Subbagian Kemahasiswaan Tahun {{ $tahun }}</h2>
        <p style="margin-bottom:10px">1. Surat Keterangan Aktif Kuliah</p>
        <table class="table text-center">
            <tr class="table">
                <th class="table"> No. </th>
                <th class="table"> Nomor Surat</th>
                <th class="table"> Kode Surat</th>
                <th class="table"> Nama Mahasiswa</th>
                <th class="table"> Semester</th>
                <th class="table"> Dibuat</th>
            </tr>
            <tbody>
                @foreach ($suratKeteranganAktifList as $suratKeteranganAktif)
                    <tr class="table">
                        <td class="table"> {{ $loop->iteration }}</td>
                        <td class="table"> {{ $suratKeteranganAktif->nomor_surat }}</td>
                        <td class="table"> {{ $suratKeteranganAktif->kodeSurat->kode_surat }}</td>
                        <td class="table"> {{ $suratKeteranganAktif->pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                        <td class="table"> {{ $suratKeteranganAktif->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeteranganAktif->pengajuanSuratKeterangan->tahunAkademik->semester) }}</td>
                        <td class="table"> {{ $suratKeteranganAktif->created_at->isoFormat('D MMMM Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">2. Surat Keterangan Kelakuan Baik</p>
        <table class="table text-center">
            <thead>
                <tr>
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Kode Surat</th>
                    <th class="table"> Nama Mahasiswa</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratKeteranganKelakuanList as $suratKeterangan)
                <tr>
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ $suratKeterangan->nomor_surat }}</td>
                    <td class="table"> {{ $suratKeterangan->kodeSurat->kode_surat }}</td>
                    <td class="table"> {{ $suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                    <td class="table"> {{ $suratKeterangan->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">3. Surat Dispensasi</p>
         <table class="table text-center">
            <thead class="table">
                <tr>
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Kode Surat</th>
                    <th class="table"> Nama Kegiatan</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratDispensasiList as $suratDispensasi)
                <tr>
                    <td class="table"> {{ $loop->iteration  }}</td>                    
                    <td class="table"> {{ $suratDispensasi->nomor_surat }}</td>
                    <td class="table"> {{ $suratDispensasi->kodeSurat->kode_surat }}</td>
                    <td class="table"> {{ $suratDispensasi->nama_kegiatan }}</td>
                    <td class="table"> {{ $suratDispensasi->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">4. Surat Rekomendasi</p>
        <table class="table text-center">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Kode Surat</th>
                    <th class="table"> Nama Kegiatan</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratRekomendasiList as $suratRekomendasi)
                <tr>
                    <td class="table"> {{ $loop->iteration  }}</td>
                    <td class="table"> {{ $suratRekomendasi->nomor_surat }}</td>
                    <td class="table"> {{ $suratRekomendasi->kodeSurat->kode_surat }}</td>
                    <td class="table"> {{ $suratRekomendasi->nama_kegiatan }}</td>
                    <td class="table"> {{ $suratRekomendasi->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">5. Surat Tugas</p>
           <table class="table text-center">
                <thead>
                    <tr class="table">
                        <th class="table"> No. </th>
                        <th class="table"> Nomor Surat</th>
                        <th class="table"> Kode Surat</th>
                        <th class="table"> Nama Kegiatan</th>
                        <th class="table"> Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suratTugasList as $suratTugas)
                    <tr>
                        <td class="table"> {{ $loop->iteration  }}</td>
                        <td class="table"> {{ $suratTugas->nomor_surat }}</td>
                        <td class="table"> {{ $suratTugas->kodeSurat->kode_surat }}</td>
                        <td class="table"> {{ $suratTugas->nama_kegiatan }}</td>
                        <td class="table"> {{ $suratTugas->created_at->isoFormat('D MMMM Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        <p style="margin-bottom:10px;margin-top:10px">6. Surat Persetujuan Pindah</p>
         <table class="table text-center">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Kode Surat</th>
                    <th class="table"> Nama Mahasiswa</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratPersetujuanPindahList as $suratPersetujuanPindah)
                <tr>
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ $suratPersetujuanPindah->nomor_surat }}</td>
                    <td class="table"> {{ $suratPersetujuanPindah->kodeSurat->kode_surat }}</td>
                    <td class="table"> {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nama }}</td>
                    <td class="table"> {{ $suratPersetujuanPindah->created_at->isoFormat('D MMMM Y') }}</td>                
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">7. Surat Pengantar Cuti</p>
        <table class="table text-center">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Kode Surat</th>
                    <th class="table"> Tahun Akademik</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratCutiList as $suratCuti)
                <tr>
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ $suratCuti->nomor_surat }}</td>
                    <td class="table"> {{ $suratCuti->kodeSurat->kode_surat }}</td>
                    <td class="table"> {{ $suratCuti->waktuCuti->tahunAkademik->tahun_akademik }} - {{ ucwords($suratCuti->waktuCuti->tahunAkademik->semester) }}</td>
                    <td class="table"> {{ $suratCuti->created_at->isoFormat('D MMMM Y') }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">8. Surat Pengantar Beasiswa</p>
        <table class="table text-center">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Kode Surat</th>
                    <th class="table"> Hal</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratBeasiswaList as $suratBeasiswa)
                <tr>
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ $suratBeasiswa->nomor_surat }}</td>
                    <td class="table"> {{ $suratBeasiswa->kodeSurat->kode_surat }}</td>
                    <td class="table"> {{ $suratBeasiswa->hal }}</td>
                    <td class="table"> {{ $suratBeasiswa->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">9. Surat Kegiatan Mahasiswa</p>
        <table class="table text-center">
            <thead>
                <tr>
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Kode Surat</th>
                    <th class="table"> Nama Kegiatan</th>
                    <th class="table"> Ormawa</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratKegiatanList as $suratKegiatan)
                <tr>
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ $suratKegiatan->nomor_surat }}</td>
                    <td class="table"> {{ $suratBeasiswa->kodeSurat->kode_surat }}</td>
                    <td class="table"> {{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan }}</td>
                    <td class="table"> {{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                    <td class="table"> {{ $suratKegiatan->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>