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
            @php
                $kode = explode('/',$suratSurvei->kodeSurat->kode_surat);
            @endphp
            <table style="padding:0px 14px;line-height:1;margin-top:10px">
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>B/{{$suratSurvei->nomor_surat}}/{{$kode[0].'.1/'.$kode[1]}}/{{$suratSurvei->created_at->year}}</td>
                    <td class="text-right">{{$suratSurvei->created_at->isoFormat('D MMMM Y')}}</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td colspan="2">Permohonan Survey</td>
                </tr>
            </table> 
             <p class="m-0"style="margin-top:20px; padding-left: 15px;padding-right: 15px;">
               <b>
                    Yth. <br>
                    {{ $suratSurvei->pengajuanSuratPermohonanSurvei->kepada }} <br>
                    Di Gorontalo
               </b>
             </p>
            <div class="content mt-1">

            <p class="m-0"style="margin-top:20px">
                Untuk menunjang pelaksanaan perkuliahan pada mata kuliah <b>{{ $suratSurvei->pengajuanSuratPermohonanSurvei->mata_kuliah }} Program Studi {{ $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->prodi->strata }} {{ $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->prodi->nama_prodi }} {{ $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->prodi->jurusan->nama_jurusan }} Fakultas Teknik Universitas Negeri Gorontalo</b>, maka dengan ini kami sangat mengharapkan kesediaan kiranya dapat memberikan informasi berupa {{ $suratSurvei->pengajuanSuratPermohonanSurvei->data_survei }} yang ada di kantor Bapak/Ibu
            </p>
            <p class="m-0">
                nama mahasiswa sebagai berikut :
            </p>
            <p class="m-0">
                <b>
                    <table class="m-0">
                        <tr>
                            <td class="data-table" style="padding-left:45px">Nama</td>
                            <td>: {{$suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->nama}}</td>
                        </tr>
                        <tr>
                            <td class="data-table" style="padding-left:45px">NIM</td>
                            <td>: {{$suratSurvei->pengajuanSuratPermohonanSurvei->nim}}</td>
                        </tr>
                        <tr>
                            <td class="data-table" style="padding-left:45px">Jurusan</td>
                            <td>: {{ $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->prodi->jurusan->nama_jurusan }}</td>
                        </tr>
                        <tr>
                            <td class="data-table" style="padding-left:45px">Prodi</td>
                            <td>: {{ $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->prodi->strata }}.{{ $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->prodi->nama_prodi }}</td>
                        </tr>
                    </table>
                </b>
            </p>
            <p class="m-0">
                Demikian surat ini disampaikan, atas kerjasamanya diucapkan terima kasih.
            </p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>Wakil Dekan 1 Bidang Akademik,</b></p>
                <p class="m-0 tanda-tangan-margin">
                    <img class="tanda-tangan" src="{{$suratSurvei->user->tanda_tangan}}">
                </p>
                <p class="m-0"><b>{{$suratSurvei->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{substr($suratSurvei->user->nip,0,8)}} {{substr($suratSurvei->user->nip,8,6)}} {{substr($suratSurvei->user->nip,14,1)}} {{substr($suratSurvei->user->nip,15,3)}}</b></p>
            </div>
        </div>
   </div>
</body>
</html>