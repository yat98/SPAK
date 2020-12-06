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
        <div class="text-center mt-1">
            <p class="m-0"><b><span class="underline">SURAT PERSETUJUAN PINDAH</span></b></p>
            <p class="m-0"><b><i>Nomor: <span style="display:inline-block;width:20px;height:auto"></span>{{$suratPersetujuanPindah->nomor_surat}}/{{$suratPersetujuanPindah->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratPersetujuanPindah->created_at->year}}</i></b></p>
        </div>
        <div class="content">
            <p class="m-0">Yang bertanda tangan dibawah ini : </p>
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $suratPersetujuanPindah->user->nama }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>:</td>
                    <td>{{ $suratPersetujuanPindah->user->nip }}</td>
                </tr>
                <tr>
                    <td>Pangkat/Golongan</td>
                    <td>:</td>
                    <td>{{ ucwords($suratPersetujuanPindah->user->pangkat) }}, {{ $suratPersetujuanPindah->user->golongan }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ ucwords($suratPersetujuanPindah->user->jabatan) }} Fakultas Teknik</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p class="m-0">Dengan ini menerangkan bahwa :</p>
                    </td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nama }}</td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td>:</td>
                    <td>{{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>:</td>
                    <td>{{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->prodi->strata }} - {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->prodi->nama_prodi }}</td>
                </tr>
                <tr>
                    <td>Jurusan</td>
                    <td>:</td>
                    <td>{{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->prodi->jurusan->nama_jurusan }}</td>
                </tr>
            </table>
            <p class="m-0">Untuk pindah dari Program Studi {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->prodi->strata }} - {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->prodi->nama_prodi }} Jurusan {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->prodi->jurusan->nama_jurusan }} Fakultas Teknik Universitas Negeri Gorontalo ke Program Studi {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->strata }} - {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->nama_prodi }} {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->nama_kampus }}.</p>
            <p class="m-0">Demikian surat persetujuan ini dibuat untuk di pergunakan seperlunya.</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>Gorontalo, {{$suratPersetujuanPindah->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratPersetujuanPindah->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratPersetujuanPindah->user->jabatan == 'wd3')
                        <p class="m-0"><b>Wakil Dekan III</b></p>
                    @elseif($suratPersetujuanPindah->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratPersetujuanPindah->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratPersetujuanPindah->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratPersetujuanPindah->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:60px">
            <?= $qrCode ?>
        </div>
   </div>
</body>
</html>