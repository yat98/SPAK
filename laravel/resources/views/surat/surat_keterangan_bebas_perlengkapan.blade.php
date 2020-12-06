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
            margin-top:25px;
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
        <div class="text-center mt-2">
            <p class="m-0" style="font-size:23px;margin-bottom:5px;margin-top:10px;"><b><span class="underline">SURAT KETERANGAN</span></b></p>
            <p class="m-0"><b><i>Nomor: <span style="display:inline-block;width:20px;height:auto"></span>{{$suratPerlengkapan->nomor_surat}}/{{$suratPerlengkapan->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratPerlengkapan->created_at->year}}</i></b></p>
        </div>
        <div class="content">
        <p class="m-0">Yang bertanda tangan di bawah ini:</p>
            <table class="m-0">
                <tr>
                    <td class="data-table">Nama</td>
                    <td>: <b>{{strtoupper($suratPerlengkapan->user->nama)}}</b></td>
                </tr>
                <tr>
                    <td class="data-table">NIP</td>
                    <td>: <b>{{$suratPerlengkapan->user->nip}}</b></td>
                </tr>
                <tr>
                    <td class="data-table" style="vertical-align:top;" rowspan="2">Jabatan</td>
                    <td>:
                        @if($suratPerlengkapan->user->jabatan == 'dekan')
                            Dekan Fakultas Teknik
                        @elseif($suratPerlengkapan->user->jabatan == 'kabag tata usaha')
                            Kabag Tata Usaha Fakultas Teknik
                        @elseif($suratPerlengkapan->user->jabatan == 'kasubag umum & bmn')
                            Kasubag Umum & BMN Fakultas Teknik
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>&nbsp; Universitas Negeri Gorontalo</td>
                </tr>
            </table>
            <p class="m-0">Dengan ini menerangkan kepada :</p>
            <table class="m-0">
                <tr>
                    <td class="data-table">Nama</td>
                    <td>: {{$suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->mahasiswa->nama}}</td>
                </tr>
                <tr>
                    <td class="data-table">NIM</td>
                    <td>: {{$suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->nim}}</td>
                </tr>
                <tr>
                    <td class="data-table">Program Studi</td>
                    <td>: {{$suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->mahasiswa->prodi->strata}}.{{$suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->mahasiswa->prodi->nama_prodi}}</td>
                </tr>
                <tr>
                    <td class="data-table">Jurusan</td>
                    <td>: {{$suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->mahasiswa->prodi->jurusan->nama_jurusan}}</td>
                </tr>
            </table>
            <p class="m-0">Bahwa yang bersangkutan tidak ada peminjaman barang/alat dan lain-lain pada Subbagian umum dan Perlengkapan Fakultas Teknik Universitas Negeri Gorontalo.</p>
            <p class="m-0">Demikian surat keterangan ini di buat untuk dipergunakan seperlunya.</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0">Gorontalo, {{$suratPerlengkapan->created_at->isoFormat('D MMMM Y')}}</p>
                @if($suratPerlengkapan->user->jabatan == 'dekan')
                    <p class="m-0">Dekan</p>
                @else
                    @if($suratPerlengkapan->user->jabatan == 'kasubag umum & bmn')
                        <p class="m-0">a.n Kabag tata Usaha FT</p>
                        <p class="m-0">Kasubag Umum & BMN</p>
                    @elseif($suratPerlengkapan->user->jabatan == 'kabag tata usaha')
                        <p class="m-0">a.n Dekan</p>
                        <p class="m-0">Kabag TU</p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratPerlengkapan->pengajuanSuratKeteranganBebasPerlengkapan->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratPerlengkapan->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratPerlengkapan->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratPerlengkapan->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:90px">
            <?= $qrCode ?>
        </div>
    </div>
</body>
</html>