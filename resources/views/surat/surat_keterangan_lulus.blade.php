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
            height:120px;
            width: 120px;
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
            font-size: 16px;
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
        .border{
            height: 1px;
            margin-top:15px;
            border-top: 3px solid black;
            border-bottom: 1px solid black;
        }
        .mt-3{
            margin-top: 30px;
        }
        .underline{
            padding-bottom: 1px;
            border-bottom: 2px solid black;
        }
        .content{
            padding-left: 28px;
            padding-right: 28px;
            line-height: 1.8;
            text-align: justify;
            margin-top:35px;
        }
        .data-table{
            width: 160px;
        }
        .signature{
            line-height: 1.3;
            margin-top: 80px;
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
    </style>
</head>
<body>
   <div class="container">
        @include('surat.kop_surat')
        <div class="border"></div>
        <div class="text-center mt-3">
            <p class="m-0" style="font-size:23px;margin-bottom:5px"><b><span>SURAT KETERANGAN</span></b></p>
            <p class="m-0"><b><i>Nomor: <span style="display:inline-block;width:20px;height:auto"></span>{{$suratLulus->nomor_surat}}/{{$suratLulus->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratLulus->created_at->year}}</i></b></p>
        </div>
        <div class="content">
            <p class="m-0">Dekan Fakultas Teknik Universitas Negeri Gorontalo, dengan ini menerangkan bahwa:</p>
            <table class="m-0">
                <tr>
                    <td class="data-table" style="padding-left:45px">Nama</td>
                    <td>: {{$suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->nama}}</td>
                </tr>
                <tr>
                    <td class="data-table" style="padding-left:45px">Tempat Lahir</td>
                    <td>: {{$suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->tempat_lahir}}, {{$suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->tanggal_lahir->isoFormat('D MMMM Y')}}</td>
                </tr>
                <tr>
                    <td class="data-table" style="padding-left:45px">NIM</td>
                    <td>: {{$suratLulus->pengajuanSuratKeteranganLulus->nim}}</td>
                </tr>
                <tr>
                    <td class="data-table" style="padding-left:45px">Fakultas</td>
                    <td>: Teknik</td>
                </tr>
                <tr>
                    <td class="data-table" style="padding-left:45px">Program Studi</td>
                    <td>: {{$suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->prodi->nama_prodi}}</td>
                </tr>
            </table>
            <p class="m-0">Benar-benar telah menempuh Ujian Sarjana (S1) pada Fakultas Teknik Universitas Negeri Gorontalo dengan Indeks Prestasi {{ $suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->ipk }} pada tanggal {{ $suratLulus->pengajuanSuratKeteranganLulus->tanggal_wisuda->isoFormat('D MMMM Y') }}, dan  akan diwisuda.</p>
            <p class="m-0">Demikian surat keterangan ini di buat dan diberikan kepada yang bersangkutan untuk dipergunakan sebagaimana mestinya.</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>{{$suratLulus->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratLulus->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratLulus->user->jabatan == 'wd1')
                        <p class="m-0"><b>Wakil Dekan Bidang Akademik,</b></p>
                    @elseif($suratLulus->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratLulus->pengajuanSuratKeteranganLulus->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratLulus->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratLulus->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratLulus->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:90px">
            <?= $qrCode ?>
        </div>
    </div>
</body>
</html>