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
            <table style="padding:0px 14px;line-height:1;margin-top:10px">
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td><span style="display:inline-block;width:20px;height:auto"></span>{{$suratPenelitian->nomor_surat}}/{{$suratPenelitian->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratPenelitian->created_at->year}}</td>
                    <td class="text-right">{{$suratPenelitian->created_at->isoFormat('D MMMM Y')}}</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td colspan="2">Rekomendasi Penelitian</td>
                </tr>
            </table> 
             <p class="m-0"style="margin-top:20px; padding-left: 15px;padding-right: 15px;">
               <b>
                    Yth. <br>
                    {{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->kepada }} <br>
                    Di Gorontalo
               </b>
             </p>
            <div class="content mt-1">

            <p class="m-0"style="margin-top:20px">
                Dengan ini kami sampaikan bahwa:
            </p>
            <p class="m-0" style="margin:10px 0">
                <table class="m-0">
                    <tr>
                        <td class="data-table" style="padding-left:45px">Nama</td>
                        <td>: {{$suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->nama}}</td>
                    </tr>
                    <tr>
                        <td class="data-table" style="padding-left:45px">NIM</td>
                        <td>: {{$suratPenelitian->pengajuanSuratRekomendasiPenelitian->nim}}</td>
                    </tr>
                    <tr>
                        <td class="data-table" style="padding-left:45px">Jurusan</td>
                        <td>: {{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->prodi->jurusan->nama_jurusan }}</td>
                    </tr>
                    <tr>
                        <td class="data-table" style="padding-left:45px">Prodi</td>
                        <td>: {{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->prodi->strata }}.{{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->prodi->nama_prodi }}</td>
                    </tr>
                </table>
            </p>
            <p class="m-0">
                Adalah benar-benar mahasiswa Fakultas Teknik Universitas Negeri Gorontalo, yang akan mengadakan penelitian dengan judul:
            </p>
            <p class="m-0" style="padding:8px;text-align:center">
               <b>"{{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->judul }}"</b>
            </p>
             <p class="m-0">
                Sehubungan dengan hal tersebut, kami mohon bantuan kiranya yang bersangkutan diberikan izin/rekomendasi untuk mengadakan penelitian/pengumpulan data dan informasi yang dimaksud, guna penyusunan proposal/skripsi.
            </p>
            <p class="m-0">
                Demikian surat ini disampaikan, atas bantuan dan kerjasamanya yang baik kami ucapkan terima kasih.
            </p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>{{$suratPenelitian->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratPenelitian->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratPenelitian->user->jabatan == 'wd1')
                        <p class="m-0"><b>Wakil Dekan Bidang Akademik,</b></p>
                    @elseif($suratPenelitian->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratPenelitian->pengajuanSuratRekomendasiPenelitian->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratPenelitian->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratPenelitian->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratPenelitian->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:90px">
            <?= $qrCode ?>
        </div>
        <div style="clear:both;margin-top:20px">
            <p>
                <i style="margin-bottom:-14px;display:inline-block;margin-left:19px">
                Tembusan YTH:
                </i>
            </p>
           <p>
            <i>
                <?= $suratPenelitian->tembusan  ?>
            </i>
           </p>
        </div>
   </div>
</body>
</html>