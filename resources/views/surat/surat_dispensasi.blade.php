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
            <p class="m-0"><b><span class="underline">SURAT DISPENSASI</span></b></p>
            <p class="m-0"><b><i>Nomor: <span style="display:inline-block;width:20px;height:auto"></span>{{$suratDispensasi->nomor_surat}}/{{$suratDispensasi->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratDispensasi->created_at->year}}</i></b></p>
        </div>
        <div class="content">
            <p class="m-0">Memperhatikan surat dari {{ ucwords($suratDispensasi->pengajuanSuratDispensasi->suratMasuk->instansi) }} Nomor : {{ $suratDispensasi->pengajuanSuratDispensasi->suratMasuk->nomor_surat }}, {{ $suratDispensasi->pengajuanSuratDispensasi->suratMasuk->tanggal_surat_masuk->isoFormat('D MMMM Y') }} perihal <b>{{ $suratDispensasi->pengajuanSuratDispensasi->suratMasuk->perihal }}</b> maka dengan ini Dekan Fakultas Teknik Memberikan dispensasi kepada:</p>
            @if($suratDispensasi->pengajuanSuratDispensasi->mahasiswa->count() > 0)
                <table class="m-0 text-center table table-margin">
                    <tr class="table">
                        <th class="table">NO</th>
                        <th class="table">NAMA</th>
                        <th class="table">NIM</th>
                        <th class="table">PRODI</th>
                        <th class="table">JURUSAN</th>
                    </tr>
                    @foreach($suratDispensasi->pengajuanSuratDispensasi->mahasiswa as $mahasiswa)
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
            <p class="m-0">Sebagai peserta pada kegiatan <b>{{ ucwords($suratDispensasi->pengajuanSuratDispensasi->nama_kegiatan) }}</b>, dengan tahapan pelaksanaan sebagai berikut:</p>
            @if($suratDispensasi->pengajuanSuratDispensasi->tahapanKegiatanDispensasi->count() > 0)
            <table class="tahapan-table">
            @foreach($suratDispensasi->pengajuanSuratDispensasi->tahapanKegiatanDispensasi as $tahapan)
                    <tr valign="top">
                        <td style="width:6%;padding-left:15px">{{$loop->iteration}}. </td>
                        <td colspan="3">{{ $tahapan->tahapan_kegiatan }}</td>
                    </tr>
                    <tr valign="top">
                        <td></td>
                        <td style="width:12%">Tanggal</td>
                        <td style="width:0.3%">:</td>
                        @if($tahapan->tanggal_awal_kegiatan->equalTo($tahapan->tanggal_akhir_kegiatan))
                            <td>{{$tahapan->tanggal_awal_kegiatan->isoFormat('D MMMM Y')}}</td>
                        @elseif($tahapan->tanggal_awal_kegiatan->isSameMonth($tahapan->tanggal_akhir_kegiatan))    
                            <td>{{ $tahapan->tanggal_awal_kegiatan->isoFormat('D').' s/d '.$tahapan->tanggal_akhir_kegiatan->isoFormat('D').' '.$tahapan->tanggal_awal_kegiatan->isoFormat('MMMM Y') }}</td>
                        @else
                            <td>{{$tahapan->tanggal_awal_kegiatan->isoFormat('D MMMM Y').' s/d '.$tahapan->tanggal_akhir_kegiatan->isoFormat('D MMMM Y') }}</td>
                        @endif
                    </tr>
                    <tr valign="top">
                        <td></td>
                        <td>Tempat</td>
                        <td>:</td>
                        <td>{{ $tahapan->tempat_kegiatan }}</td>
                    </tr>
            @endforeach
            </table>
            @endif
            <p class="m-0">Demikian surat dispensasi ini di buat dan diberikan kepada yang bersangkutan untuk digunakan seperlunya.</p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>Gorontalo, {{$suratDispensasi->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratDispensasi->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratDispensasi->user->jabatan == 'wd3')
                        <p class="m-0"><b>Wakil Dekan III</b></p>
                    @elseif($suratDispensasi->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratDispensasi->pengajuanSuratDispensasi->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratDispensasi->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratDispensasi->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratDispensasi->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:105px">
            <?= $qrCode ?>
        </div>
   </div>
</body>
</html>