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
            margin-top:5px;
        }
        .data-table{
            width: 160px;
        }
        .signature{
            line-height: 1.3;
            margin-top: 60px;
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
        li{
            line-height:1.8;
            padding-top:-5px;
        }
    </style>
</head>
<body>
   <div class="container">
        @include('surat.kop_surat')
        <div class="border"></div>
        <div class="text-center mt-2">
            <p class="m-0" style="font-size:23px;margin-bottom:5px;margin-top:10px"><b><span class="underline">SURAT KETERANGAN BEBAS PERPUSTAKAAN</span></b></p>
            <p class="m-0"><b><i>Nomor: <span style="display:inline-block;width:20px;height:auto"></span>{{$suratPerpustakaan->nomor_surat}}/{{$suratPerpustakaan->kode_surat}}/{{$suratPerpustakaan->created_at->year}}</i></b></p>
        </div>
        <div class="content">
            <p class="m-0">Kepala Perpustakaan Fakultas Teknik Universitas Negeri Gorontalo menerangkan bahwa:</p>
            <table class="m-0">
                <tr>
                    <td class="data-table" >Nama</td>
                    <td>: {{$suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->mahasiswa->nama}}</td>
                </tr>
                <tr>
                    <td class="data-table">NIM</td>
                    <td>: {{$suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->nim}}</td>
                </tr>
                <tr>
                    <td class="data-table">No. KTA</td>
                    <td>:  {{$suratPerpustakaan->nokta}}</td>
                </tr>
                <tr>
                    <td class="data-table">Jurusan</td>
                    <td>: {{$suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->mahasiswa->prodi->jurusan->nama_jurusan}}</td>
                </tr>
                <tr>
                    <td class="data-table" rowspan="2" style="vertical-align:top;">Alamat</td>
                    <td>: {{$suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->alamat}}</td>
                </tr>
                <tr>
                    <td>&nbsp; {{$suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->telp}}</td>
                </tr>
            </table>
        </div>
        <div class="content">
            <p class="m-0">
                <?= $suratPerpustakaan->kewajiban ?>
            </p>
            <p class="m-0">
                Untuk itu kepada yang bersangkutan dinyatakan telah bebas dari segala kewajiban perpustakaan Fakultas Teknik Universitas Negeri Gorontalo  dan berhak mendapatkan surat keterangan ini.
            </p>
             <p class="m-0">
               Demikian surat keterangan ini dibuat, untuk dapat dipergunakan seperlunya dan atas kerja sama yang baik diucapkan terima kasih
            </p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>{{$suratPerpustakaan->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratPerpustakaan->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratPerpustakaan->user->jabatan == 'wd1')
                        <p class="m-0"><b>Wakil Dekan Bidang Akademik,</b></p>
                    @elseif($suratPerpustakaan->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratPerpustakaan->pengajuanSuratKeteranganBebasPerpustakaan->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratPerpustakaan->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratPerpustakaan->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratPerpustakaan->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:90px">
            <?= $qrCode ?>
        </div>
    </div>
</body>
</html>