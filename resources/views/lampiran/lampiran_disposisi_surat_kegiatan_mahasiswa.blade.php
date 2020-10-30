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
            margin-top: 20px;
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
            margin-top:35px;
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
        .table{
            border-collapse:collapse;
            border-spacing:0;
            border:1px solid black;
        }
        .px-2{
            padding-left:5px;
            padding-right:5px;
            padding-top:5px;
            padding-bottom:5px;
        }
    </style>
</head>
<body>
   <div class="container">
        @include('surat.kop_surat')
        <div class="border"></div>
        <div class="text-center mt-3">
            <p class="m-0" style="margin-bottom:5px">LEMBAR DISPOSISI</p>
            <p class="m-0">
                UNIVERSITAS NEGERI GORONTALO
            </p>
        </div>
        <table class="m-0 table mt-3">
            <tr>
                <td class="table px-2" colspan="4">No Agenda : {{ $pengajuanKegiatan->disposisiSuratKegiatanMahasiswa->nomor_agenda }}</td>
            </tr>
            <tr>
                <td class="table px-2" colspan="4">Tanggal terima : {{ $pengajuanKegiatan->disposisiSuratKegiatanMahasiswa->tanggal_terima->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="table px-2" colspan="2">Tanggal surat : {{ $pengajuanKegiatan->disposisiSuratKegiatanMahasiswa->tanggal_surat->isoFormat('D MMMM Y') }}</td>
                <td class="table px-2" colspan="2">Nomor surat : {{ $pengajuanKegiatan->nomor_surat_permohonan_kegiatan }}</td>
            </tr>
            <tr>
                <td class="table px-2" colspan="4">Asal : {{ $pengajuanKegiatan->ormawa->nama }}</td>
            </tr>
            <tr>
                <td class="table px-2" colspan="4">Hal : {{ $pengajuanKegiatan->disposisiSuratKegiatanMahasiswa->hal }}</td>
            </tr>
            <tr>
                <td class="px-2 text-center" style="padding-top:15px">
                    <div style="width:100px;margin:0 auto">
                        <div style="display:inline-block;width:30px;height:25px;border:1px solid black;margin-top:10px"></div>
                        <span style="display:inline-block;margin-top:-5px">Rahasia</span>
                    </div>
                </td>
                <td class="px-2 text-center" style="padding-top:15px;border-bottom: none !important">
                    <div style="width:100px;margin:0 auto">
                        <div style="display:inline-block;width:30px;height:25px;border:1px solid black;margin-top:10px"></div>
                        <span style="display:inline-block;margin-top:-5px">Penting</span>
                    </div>
                </td>
                <td class="px-2 text-center" style="padding-top:15px">
                    <div style="width:100px;margin:0 auto">
                        <div style="display:inline-block;width:30px;height:25px;border:1px solid black;margin-top:10px"></div>
                        <span style="display:inline-block;margin-top:-5px">Segera</span>
                    </div>
                </td>
                <td class="px-2 text-center" style="padding-top:15px">
                    <div style="width:100px;margin:0 auto">
                        <div style="display:inline-block;width:30px;height:25px;border:1px solid black;margin-top:10px"></div>
                        <span style="display:inline-block;margin-top:-5px">Biasa</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="table px-2" colspan="2" style="padding-top:5px">Diteruskan kepada : </td>
                <td class="table px-2" colspan="2" style="padding-top:5px">Disposisi : </td>
            </tr>
            @if($daftarDisposisi->count() == 0)
                <tr>
                    <td class="table px-2" colspan="2">
                        <div style="height:250px"></div>
                    </td>
                    <td class="table px-2" colspan="2">
                        <div style="height:250px"></div>
                    </td>
                </tr>
            @else
                @foreach($daftarDisposisi as $value)
                    <tr>
                        <td colspan="2" style="border-right:1px solid black;padding:10px">{{ strtoupper($value->user->jabatan) }} ({{ $value->created_at->format('d/m/y') }})</td>
                        <td colspan="2" style="border-right:1px solid black;padding:10px">{{ $value->catatan }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td class="table px-2" colspan="2" >Keterangan : </td>
                <td class="table px-2" colspan="2" >{{ $pengajuanKegiatan->disposisiSuratKegiatanMahasiswa->keterangan }}</td>
            </tr>
        </table>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0">Dekan,</p>

                <p class="m-0 tanda-tangan-margin">
                    <div class="tanda-tangan"></div>
                </p>
                <p class="m-0">{{$dekan->nama}}</p>
                <p class="m-0">NIP. {{$dekan->nip}}</p>
            </div>
        </div>
    </div>
</body>
</html>