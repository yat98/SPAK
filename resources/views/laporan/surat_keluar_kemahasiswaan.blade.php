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
                <th class="table"> Nama Mahasiswa</th>
                <th class="table"> Semester</th>
                <th class="table"> Dibuat</th>
            </tr>
            <tbody>
                @foreach ($suratKeteranganAktifList as $suratKeteranganAktif)
                    @php
                        $kode = explode('/',$suratKeteranganAktif->kodeSurat->kode_surat);
                    @endphp
                    <tr class="table">
                        <td class="table"> {{ $loop->iteration }}</td>
                        <td class="table"> {{ 'B/'.$suratKeteranganAktif->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratKeteranganAktif->created_at->year }}</td>
                        <td class="table"> {{ $suratKeteranganAktif->pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                        <td class="table"> {{ $suratKeteranganAktif->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeteranganAktif->pengajuanSuratKeterangan->tahunAkademik->semester) }}</td>
                        <td class="table"> {{ $suratKeteranganAktif->created_at->isoFormat('D MMMM Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">2. Surat Keterangan Kelakuan Baik</p>
        <table class="table">
            <thead>
                <tr>
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Nama Mahasiswa</th>
                    <th class="table"> Semester</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratKeteranganKelakuanList as $suratKeterangan)
                <tr>
                    @php
                        $kode = explode('/',$suratKeterangan->kodeSurat->kode_surat);
                    @endphp
                    <td> {{ $loop->iteration }}</td>
                    <td> {{ 'B/'.$suratKeterangan->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratKeterangan->created_at->year }}</td>
                    <td> {{ $suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                    <td> {{ $suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->semester) }}</td>
                    <td> {{ $suratKeterangan->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">3. Surat Dispensasi</p>
         <table class="table">
            <thead class="table">
                <tr>
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Nama Kegiatan</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratDispensasiList as $suratDispensasi)
                    @php
                    $kode = explode('/',$suratDispensasi->kodeSurat->kode_surat);
                @endphp
                <tr>
                    <td class="table"> {{ $loop->iteration  }}</td>
                    @if($suratDispensasi->user->jabatan == 'dekan')
                        <td class="table"> {{ $suratDispensasi->nomor_surat.'/'.$suratDispensasi->kodeSurat->kode_surat.'/'.$suratDispensasi->created_at->format('Y') }}</td>
                    @else
                        <td class="table"> {{ $suratDispensasi->nomor_surat.'/'.$kode[0].'.3/.'.$kode[1].'/'.$suratDispensasi->created_at->format('Y') }}</td>
                    @endif
                    <td class="table"> {{ $suratDispensasi->nama_kegiatan }}</td>
                    <td class="table"> {{ $suratDispensasi->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">4. Surat Rekomendasi</p>
        <table class="table">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Nama Kegiatan</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratRekomendasiList as $suratRekomendasi)
                    @php
                    $kode = explode('/',$suratRekomendasi->kodeSurat->kode_surat);
                @endphp
                <tr>
                    <td class="table"> {{ $loop->iteration  }}</td>
                    @if($suratRekomendasi->user->jabatan == 'wd3')
                        <td class="table"> {{ $suratRekomendasi->nomor_surat.'/'.$kode[0].'.3/'.$kode[1].'/'.$suratRekomendasi->created_at->format('Y') }}</td>
                    @else
                        <td class="table"> {{ $suratRekomendasi->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratRekomendasi->created_at->format('Y') }}</td>
                    @endif
                    <td class="table"> {{ $suratRekomendasi->nama_kegiatan }}</td>
                    <td class="table"> {{ $suratRekomendasi->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">5. Surat Tugas</p>
           <table class="table">
                <thead>
                    <tr class="table">
                        <th class="table"> No. </th>
                        <th class="table"> Nomor Surat</th>
                        <th class="table"> Nama Kegiatan</th>
                        <th class="table"> Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suratTugasList as $suratTugas)
                    <tr>
                        <td class="table"> {{ $loop->iteration  }}</td>
                        <td class="table"> {{ 'B/'.$suratTugas->nomor_surat.'/'.$suratTugas->kodeSurat->kode_surat.'/'.$suratTugas->created_at->format('Y') }}</td>
                        <td class="table"> {{ $suratTugas->nama_kegiatan }}</td>
                        <td class="table"> {{ $suratTugas->created_at->isoFormat('D MMMM Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        <p style="margin-bottom:10px;margin-top:10px">6. Surat Persetujuan Pindah</p>
         <table class="table">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Nama Mahasiswa</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratPersetujuanPindahList as $suratPersetujuanPindah)
                <tr>
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ 'B/'.$suratPersetujuanPindah->nomor_surat.'/'.$suratPersetujuanPindah->kodeSurat->kode_surat.'/'.$suratPersetujuanPindah->created_at->year }}</td>
                    <td class="table"> {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nama }}</td>                </tr>
                    <td class="table"> {{ $suratPersetujuanPindah->created_at->isoFormat('D MMMM Y') }}</td>                
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">7. Surat Pengantar Cuti</p>
        <table class="table">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Tahun Akademik</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratCutiList as $suratCuti)
                @php
                    $kode = explode('/',$suratCuti->kodeSurat->kode_surat);
                @endphp
                <tr>
                    <td class="table"> {{ $loop->iteration + $perPage * ($suratCutiList->currentPage() - 1)  }}</td>
                    <td class="table"> {{ $suratCuti->nomor_surat.'/'.$kode[0].'.4/.'.$kode[1].'/'.$suratCuti->created_at->format('Y') }}</td>
                    <td class="table"> {{ $suratCuti->waktuCuti->tahunAkademik->tahun_akademik }} - {{ ucwords($suratCuti->waktuCuti->tahunAkademik->semester) }}</td>
                    <td class="table"> {{ $suratCuti->created_at->isoFormat('D MMMM Y') }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">8. Surat Pengantar Beasiswa</p>
        <table class="table">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Hal</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratBeasiswaList as $suratBeasiswa)
                <tr>
                    <td> {{ $loop->iteration + $perPage * ($suratBeasiswaList->currentPage() - 1)  }}</td>
                    <td> {{ 'B/'.$suratBeasiswa->nomor_surat.'/'.$suratBeasiswa->kodeSurat->kode_surat.'/'.$suratBeasiswa->created_at->format('Y') }}</td>
                    <td> {{ $suratBeasiswa->hal }}</td>
                    <td> {{ $suratBeasiswa->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">9. Surat Kegiatan Mahasiswa</p>
        <table class="table">
            <thead>
                <tr>
                    <th> No. </th>
                    <th> Nomor Surat</th>
                    <th> Nama Kegiatan</th>
                    <th> Ormawa</th>
                    <th> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratKegiatanList as $suratKegiatan)
                <tr>
                    <td> {{ $loop->iteration }}</td>
                    <td> {{ $suratKegiatan->nomor_surat.'/'.$suratKegiatan->kodeSurat->kode_surat.'/'.$suratKegiatan->created_at->year }}</td>
                    <td> {{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan }}</td>
                    <td> {{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                    <td> {{ $suratKegiatan->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>