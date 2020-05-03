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
            line-height: 1.4;
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
            <p class="m-0"><b><span class="underline">SURAT TUGAS</span></b></p>
            <p class="m-0"><b><i>Nomor : B/{{$suratTugas->nomor_surat}}/{{$suratTugas->kodeSurat->kode_surat}}/{{$suratTugas->created_at->year}}</i></b></p>
        </div>
        <div class="content">
            <p class="m-0">Dekan Fakultas Teknik Universitas Negeri Gorontalo menugaskan kepada :</p>
            @if($suratTugas->mahasiswa->count() > 0)
                    <table class="m-0 text-center table table-margin">
                    <tr class="table">
                        <th class="table">NO</th>
                        <th class="table">NAMA</th>
                        <th class="table">NIM</th>
                        <th class="table">PROGRAM STUDI</th>
                        <th class="table">JURUSAN</th>
                    </tr>
                    @foreach($suratTugas->mahasiswa as $mahasiswa)
                        <tr class="table">
                            <td class="table">{{ $loop->iteration }}</td>
                            <td class="table">{{ $mahasiswa->nama }}</td>
                            <td class="table">{{ $mahasiswa->nim }}</td>
                            <td class="table">{{ $mahasiswa->prodi->strata }} - {{ $mahasiswa->prodi->nama_prodi }}</td>
                            <td class="table">{{ $mahasiswa->prodi->jurusan->nama_jurusan }}</td>
                        </tr>
                    @endforeach
                    </table>
            @endif
            <p class="m-0">
                Untuk mengikuti pelaksaaan {{ strtolower($suratTugas->jenis_kegiatan) }} <b>"{{ ucwords($suratTugas->nama_kegiatan) }}"</b>, yang akan dilaksanakan pada tanggal
                @if($suratTugas->tanggal_awal_kegiatan->equalTo($suratTugas->tanggal_akhir_kegiatan))
                    {{$suratTugas->tanggal_awal_kegiatan->isoFormat('D MMMM Y')}}
                @elseif($suratTugas->tanggal_awal_kegiatan->isSameMonth($suratTugas->tanggal_akhir_kegiatan))
                    {{$suratTugas->tanggal_awal_kegiatan->isoFormat('D').' - '.$suratTugas->tanggal_akhir_kegiatan->isoFormat('D').' '.$suratTugas->tanggal_awal_kegiatan->isoFormat('MMMM Y')}}
                @else
                    {{$suratTugas->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' - '.$suratTugas->tanggal_akhir_kegiatan->isoFormat('D MMMM Y')}}
                @endif
                di {{ $suratTugas->tempat_kegiatan}}.
            </p>
           <p class="m-0">Demikian surat tugas ini di berikan kepada yang bersangkutan untuk dilaksanakan sebagaimana mestinya.</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>Gorontalo, {{$suratTugas->created_at->isoFormat('D MMMM Y')}}</b></p>
                <p class="m-0"><b>Dekan,</b></p>
                <p class="m-0 tanda-tangan-margin">
                    <img class="tanda-tangan" src="{{$suratTugas->user->tanda_tangan}}">
                </p>
                <p class="m-0"><b>{{$suratTugas->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{substr($suratTugas->user->nip,0,8)}} {{substr($suratTugas->user->nip,8,6)}} {{substr($suratTugas->user->nip,14,1)}} {{substr($suratTugas->user->nip,15,3)}}</b></p>
            </div>
        </div>
   </div>
</body>
</html>