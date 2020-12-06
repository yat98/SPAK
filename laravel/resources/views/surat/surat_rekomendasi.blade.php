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
            <p class="m-0"><b><span class="underline">SURAT REKOMENDASI</span></b></p>
            <p class="m-0"><b><i>Nomor: <span style="display:inline-block;width:20px;height:auto"></span>{{$suratRekomendasi->nomor_surat}}/{{$suratRekomendasi->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratRekomendasi->created_at->year}}</i></b></p>
        </div>
        <div class="content">
            <p class="m-0">Dekan Fakultas Teknik Universitas Negeri Gorontalo memberikan Rekomendasi kepada nama-nama di bawah ini yaitu:</p>
            @if($suratRekomendasi->pengajuanSuratRekomendasi->mahasiswa->count() > 0)
                    <table class="m-0 text-center table table-margin">
                    <tr class="table">
                        <th class="table">NO</th>
                        <th class="table">NAMA</th>
                        <th class="table">NIM</th>
                        <th class="table">PROGRAM STUDI</th>
                    </tr>
                    @foreach($suratRekomendasi->pengajuanSuratRekomendasi->mahasiswa as $mahasiswa)
                        <tr class="table">
                            <td class="table">{{ $loop->iteration }}</td>
                            <td class="table">{{ $mahasiswa->nama }}</td>
                            <td class="table">{{ $mahasiswa->nim }}</td>
                            <td class="table">{{ $mahasiswa->prodi->strata }} - {{ $mahasiswa->prodi->nama_prodi }}</td>
                        </tr>
                    @endforeach
                    </table>
            @endif
            <p class="m-0">
                Untuk mengikuti pelaksaaan <b>{{ ucwords($suratRekomendasi->pengajuanSuratRekomendasi->nama_kegiatan) }}</b>, pada tanggal
                @if($suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->equalTo($suratRekomendasi->pengajuanSuratRekomendasi->tanggal_akhir_kegiatan))
                    {{$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isoFormat('D MMMM Y')}}
                @elseif($suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isSameMonth($suratRekomendasi->pengajuanSuratRekomendasi->tanggal_akhir_kegiatan))
                    {{$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isoFormat('D').'-'.$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_akhir_kegiatan->isoFormat('D').' '.$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isoFormat('MMMM Y')}}
                @else
                    {{$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' - '.$suratRekomendasi->pengajuanSuratRekomendasi->tanggal_akhir_kegiatan->isoFormat('D MMMM Y')}}
                @endif
                di {{ $suratRekomendasi->pengajuanSuratRekomendasi->tempat_kegiatan}}.
            </p>
           <p class="m-0">Demikian surat rekomendasi ini di buat untuk digunakan seperlunya.</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>Gorontalo, {{$suratRekomendasi->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratRekomendasi->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    @if($suratRekomendasi->user->jabatan == 'wd3')
                        <p class="m-0"><b>Wakil Dekan Bidang Kemahasiswaan dan Alumni,</b></p>
                    @elseif($suratRekomendasi->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                    <p class="m-0"><b>a.n Dekan,</b></p>                    
                @endif
                <p class="m-0 tanda-tangan-margin">
                    @if($suratRekomendasi->pengajuanSuratRekomendasi->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratRekomendasi->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratRekomendasi->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratRekomendasi->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:105px">
            <?= $qrCode ?>
        </div>
   </div>
</body>
</html>