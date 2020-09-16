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
            margin-top:55px;
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
            <p class="m-0"><b><span class="underline">SURAT KETERANGAN AKTIF KULIAH</span></b></p>
            <p class="m-0"><b><i>Nomor: <span style="display:inline-block;width:20px;height:auto"></span>/{{$suratKeterangan->nomor_surat}}/{{$suratKeterangan->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/{{$suratKeterangan->created_at->year}}</i></b></p>
        </div>
        <div class="content">
            <p class="m-0">Yang bertanda tangan dibawah ini Dekan Fakultas Teknik Universitas Negeri Gorontalo dengan ini menerangkan kepada :</p>
            <table class="m-0">
                <tr>
                    <td class="data-table">Nama</td>
                    <td>: {{$suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama}}</td>
                </tr>
                <tr>
                    <td class="data-table">NIM</td>
                    <td>: {{$suratKeterangan->pengajuanSuratKeterangan->nim}}</td>
                </tr>
                <tr>
                    <td class="data-table">Program Studi</td>
                    <td>: {{$suratKeterangan->pengajuanSuratKeterangan->mahasiswa->prodi->nama_prodi}}</td>
                </tr>
                <tr>
                    <td class="data-table">Jurusan</td>
                    <td>: {{$suratKeterangan->pengajuanSuratKeterangan->mahasiswa->prodi->jurusan->nama_jurusan}}</td>
                </tr>
            </table>
            <p class="m-0">Adalah benar yang bersangkutan terdaftar sebagai <b><i class="underline">Mahasiswa Semester {{ucwords($suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->semester)}} Tahun Akademik {{$suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik}}</i></b> serta aktif mengikuti kegiatan akademik pada Fakultas Teknik Universitas Negeri Gorontalo.</p>
            <p class="m-0">Demikian surat keterangan ini di buat dan diberikan kepada yang bersangkutan untuk dipergunakan sebagaimana mestinya.</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>{{$suratKeterangan->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratKeterangan->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratKeterangan->user->jabatan == 'wd3')
                        <p class="m-0"><b>Wakil Dekan III</b></p>
                    @elseif($suratKeterangan->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

              
                <p class="m-0 tanda-tangan-margin">
                    @if($suratKeterangan->pengajuanSuratKeterangan->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratKeterangan->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratKeterangan->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{substr($suratKeterangan->user->nip,0,8)}} {{substr($suratKeterangan->user->nip,8,6)}} {{substr($suratKeterangan->user->nip,14,1)}} {{substr($suratKeterangan->user->nip,15,3)}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:105px">
            <?= $qrCode ?>
        </div>
   </div>
</body>
</html>