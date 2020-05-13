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
        <div class="content mt-1">
            @php
                $kode = explode('/',$suratCuti->kodeSurat->kode_surat);
            @endphp
            <table>
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>{{$suratCuti->nomor_surat}}/{{$kode[0].'.4/'.$kode[1]}}/{{$suratCuti->created_at->year}}</td>
                    <td class="text-right">{{$suratCuti->created_at->isoFormat('D MMMM Y')}}</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td colspan="2">Pengantar Cuti Akademik</td>
                </tr>
            </table> 
             <p class="m-0"style="margin-top:20px">
                Yth. <br>
                Kabag Kemahasiswaan BAKP <br>
                Universitas Negeri  Gorontalo
             </p>
            <p class="m-0"style="margin-top:20px">Menindaklanjuti edaran tentang Kalender Akademik, maka dengan ini kami kirimkan pengantar <i>Cuti Akademik</i> Mahasiswa Fakultas Teknik UNG pada Semester {{ ucwords($suratCuti->waktuCuti->tahunAkademik->semester) }} Tahun Akademik {{ $suratCuti->waktuCuti->tahunAkademik->tahun_akademik }} yaitu sebagai berikut:</p>
            @if($suratCuti->waktuCuti->pendaftaranCuti->count() > 0)
                    <table class="m-0 text-center table table-margin">
                    <tr class="table">
                        <td class="table">NO</td>
                        <td class="table">NAMA</td>
                        <td class="table">NIM</td>
                        <td class="table">PRODI</td>
                        <td class="table">KET.</td>
                    </tr>
                    @foreach($suratCuti->waktuCuti->pendaftaranCuti as $pendaftaranCuti)
                        @if($pendaftaranCuti->status == 'diterima')
                            <tr class="table">
                                <td class="table">{{ $loop->iteration }}</td>
                                <td class="table">{{ $pendaftaranCuti->mahasiswa->nama }}</td>
                                <td class="table">{{ $pendaftaranCuti->nim }}</td>
                                <td class="table">{{ $pendaftaranCuti->mahasiswa->prodi->strata }} - {{ $pendaftaranCuti->mahasiswa->prodi->nama_prodi }}</td>
                                <td class="table">{{ $pendaftaranCuti->alasan_cuti }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </table>
            @endif
            <p class="m-0">Atas perhatian serta kerjasama yang baik disampaikan terima kasih</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>a.n Wakil Dekan III,</b></p>
                <p class="m-0"><b>Kasubag Kemahasiswaan,</b></p>
                <p class="m-0 tanda-tangan-margin">
                    <img class="tanda-tangan" src="{{$suratCuti->user->tanda_tangan}}">
                </p>
                <p class="m-0"><b>{{$suratCuti->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{substr($suratCuti->user->nip,0,8)}} {{substr($suratCuti->user->nip,8,6)}} {{substr($suratCuti->user->nip,14,1)}} {{substr($suratCuti->user->nip,15,3)}}</b></p>
            </div>
        </div>
   </div>
</body>
</html>