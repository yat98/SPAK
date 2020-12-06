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
                    <td><span style="display:inline-block;width:20px;height:auto"></span>{{$suratDataAwal->nomor_surat}}/{{$suratDataAwal->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratDataAwal->created_at->year}}</td>
                    <td class="text-right">{{$suratDataAwal->created_at->isoFormat('D MMMM Y')}}</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td colspan="2">Permohonan Pengambilan Data Awal</td>
                </tr>
            </table> 
             <p class="m-0"style="margin-top:20px; padding-left: 15px;padding-right: 15px;">
               <b>
                    Yth. <br>
                    {{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->kepada }} <br>
                    Di Gorontalo
               </b>
             </p>
            <div class="content mt-1">

            <p class="m-0"style="margin-top:20px">
                Sehubungan dengan penyusunan Proposal/Skripsi mahasiswa <b>Jurusan {{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->prodi->jurusan->nama_jurusan }} Program Studi {{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->prodi->strata }}.{{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->prodi->nama_prodi }} Fakultas Teknik UNG</b>, kami mohon bantuan kiranya yang bersangkutan diberikan izin/rekomendasi untuk melakukan observasi awal di {{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->tempat_pengambilan_data }}, guna penyusunan proposal/skripsi. Adapun nama mahasiswa sebagai berikut:
            </p>
            <p class="m-0">
                <b>
                    <table class="m-0">
                        <tr>
                            <td class="data-table" style="padding-left:45px">Nama</td>
                            <td>: {{$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->nama}}</td>
                        </tr>
                        <tr>
                            <td class="data-table" style="padding-left:45px">NIM</td>
                            <td>: {{$suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->nim}}</td>
                        </tr>
                        <tr>
                            <td class="data-table" style="padding-left:45px">Prodi</td>
                            <td>: {{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->prodi->strata }}.{{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->prodi->nama_prodi }}</td>
                        </tr>
                    </table>
                </b>
            </p>
            <p class="m-0">
                Atas perhatian dan kerja sama yang baik diucapkan terima kasih.
            </p>
        </div>
        <div class="signature">
              <div class="signature-content">
                <p class="m-0"><b>{{$suratDataAwal->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratDataAwal->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratDataAwal->user->jabatan == 'wd1')
                        <p class="m-0"><b>Wakil Dekan Bidang Akademik,</b></p>
                    @elseif($suratDataAwal->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratDataAwal->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratDataAwal->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratDataAwal->user->nip}}</b></p>
            </div>
        </div>
         <div class="content" style="padding-top:90px">
            <?= $qrCode ?>
        </div>
   </div>
</body>
</html>