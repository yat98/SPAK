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
                $kode = explode('/',$suratBeasiswa->kodeSurat->kode_surat);
            @endphp
            <table style="padding:0px 14px;line-height:1;margin-top:10px">
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    @if($suratBeasiswa->user->jabatan == 'dekan')
                        <td>B/{{$suratBeasiswa->nomor_surat}}/{{ $suratBeasiswa->kodeSurat->kode_surat }}/{{$suratBeasiswa->created_at->year}}</td>
                    @else
                        <td>B/{{$suratBeasiswa->nomor_surat}}/{{$kode[0].'.3/'.$kode[1]}}/{{$suratBeasiswa->created_at->year}}</td>
                    @endif
                    <td class="text-right">{{$suratBeasiswa->created_at->isoFormat('D MMMM Y')}}</td>
                </tr>
                <tr>
                    <td>Lamp</td>
                    <td>:</td>
                    <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp; Berkas</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>:</td>
                    <td colspan="2"><p><b style="padding-bottom:1px;border-bottom:1px solid black;display:inline-block;line-height:1px;margin-top:16px"><i>{{ $suratBeasiswa->hal }}</i></b></p></td>
                </tr>
            </table> 
             <p class="m-0"style="margin-top:20px; padding-left: 15px;padding-right: 15px;">
                Yth. <br>
                Kabag Kemahasiswaan BAKP <br>
                Universitas Negeri  Gorontalo <br>
                Di- <br>
                <span style="margin-left:50px">Tempat</span>
             </p>
            <div class="content mt-1">

            <p class="m-0"style="margin-top:20px">Berdasarkan Surat tentang {{ $suratBeasiswa->suratMasuk->perihal }} Nomor : {{ $suratBeasiswa->suratMasuk->nomor_surat }} tanggal {{ $suratBeasiswa->suratMasuk->tanggal_surat_masuk->isoFormat('D MMMM Y') }}, maka dengan ini kami kirimkan nama-nama mahasiswa dan berkas calon penerima beasiswa tersebut <b><i>(terlampir).</i></b></p>
            <p class="m-0">Demikian hal ini disampaikan. Atas perhatian kami ucapkan terima kasih.</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                @if($suratBeasiswa->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan,</b></p>
                @else
                    <p class="m-0"><b>Wakil Dekan III,</b></p>
                @endif
                <p class="m-0 tanda-tangan-margin">
                    <img class="tanda-tangan" src="{{$suratBeasiswa->user->tanda_tangan}}">
                </p>
                <p class="m-0"><b>{{$suratBeasiswa->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{substr($suratBeasiswa->user->nip,0,8)}} {{substr($suratBeasiswa->user->nip,8,6)}} {{substr($suratBeasiswa->user->nip,14,1)}} {{substr($suratBeasiswa->user->nip,15,3)}}</b></p>
            </div>
        </div>
         <div style="height:520px"></div>
            @if($suratBeasiswa->mahasiswa->count() > 0)
                    <table class="m-0 text-center table table-margin">
                    <tr class="table">
                        <td class="table">NO</td>
                        <td class="table">NAMA</td>
                        <td class="table">NIM</td>
                        <td class="table">PRODI</td>
                        <td class="table">JURUSAN</td>
                    </tr>
                    @foreach($suratBeasiswa->mahasiswa as $mahasiswa)
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
   </div>
</body>
</html>