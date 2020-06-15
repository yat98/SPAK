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
        <h2 class="text-center" style="margin-bottom:30px">Laporan Surat Keluar <br> Subbagian Pendidikan Dan Pengajaran Tahun {{ $tahun }}</h2>
        <p style="margin-bottom:10px">1. Surat Keterangan Aktif Kuliah</p>
         <table class="table">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Nama Mahasiswa</th>
                    <th class="table"> IPK</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratLulusList as $suratLulus)
                <tr class="table">
                    @php
                        $kode = explode('/',$suratLulus->kodeSurat->kode_surat);
                    @endphp
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ 'B/'.$suratLulus->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratLulus->created_at->year }}</td>
                    <td class="table"> {{ $suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->nama }}</td>
                    <td class="table"> {{ $suratLulus->pengajuanSuratKeteranganLulus->ipk }}</td>
                    <td class="table"> {{ $suratLulus->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">2. Surat Permohonan Pengambilan Material</p>
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
                @foreach ($suratMaterialList as $suratMaterial)
                <tr class="table">
                    @php
                        $kode = explode('/',$suratMaterial->kodeSurat->kode_surat);
                    @endphp
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ 'B/'.$suratMaterial->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratMaterial->created_at->year }}</td>
                    <td class="table"> {{ $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->nama_kegiatan }}</td>
                    <td class="table"> {{ $suratMaterial->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">3. Surat Permohonan Survei</p>
        <table class="table">
            <thead>
                <tr>
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Nama Mahasiswa</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratSurveiList as $suratSurvei)
                <tr>
                    @php
                        $kode = explode('/',$suratSurvei->kodeSurat->kode_surat);
                    @endphp
                    <td class="table"> {{ $loop->iteration  }}</td>
                    <td class="table"> {{ 'B/'.$suratSurvei->nomor_surat.'/'.$kode[0].'.1/'.$kode[1].'/'.$suratSurvei->created_at->year }}</td>
                    <td class="table"> {{ $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->nama }}</td>
                    <td class="table"> {{ $suratSurvei->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">4. Surat Rekomendasi Penelitian</p>
        <table class="table">
            <thead>
                <tr class="table">
                    <th class="table"> No. </th>
                    <th class="table"> Nomor Surat</th>
                    <th class="table"> Nama Mahasiswa</th>
                    <th class="table"> Judul</th>
                    <th class="table"> Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratPenelitianList as $suratPenelitian)
                <tr>
                    @php
                        $kode = explode('/',$suratPenelitian->kodeSurat->kode_surat);
                    @endphp
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ 'B/'.$suratPenelitian->nomor_surat.'/'.$kode[0].'.1/'.$kode[1].'/'.$suratPenelitian->created_at->year }}</td>
                    <td class="table"> {{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->nama }}</td>
                    <td class="table"> {{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->nama }}</td>
                    <td class="table"> {{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->judul }}</td>
                    <td class="table"> {{ $suratPenelitian->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-bottom:10px;margin-top:10px">5. Surat Permohonan Pengambilan Data Awal</p>
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
                @foreach ($suratDataAwalList as $suratDataAwal)
                <tr class="table">
                    @php
                        $kode = explode('/',$suratDataAwal->kodeSurat->kode_surat);
                    @endphp
                    <td class="table"> {{ $loop->iteration }}</td>
                    <td class="table"> {{ 'B/'.$suratDataAwal->nomor_surat.'/'.$kode[0].'.1/'.$kode[1].'/'.$suratDataAwal->created_at->year }}</td>
                    <td class="table"> {{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->nama }}</td>
                    <td class="table"> {{ $suratDataAwal->created_at->isoFormat('D MMMM Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>