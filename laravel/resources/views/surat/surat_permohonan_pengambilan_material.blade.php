@if($suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->nim != null)
    @php
        $strata = $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->mahasiswa->prodi->strata;
        $namaProdi = $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->mahasiswa->prodi->nama_prodi;
        $namaJurusan = $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->mahasiswa->prodi->jurusan->nama_jurusan;
    @endphp
@else
    @php
        $strata = $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok[0]->prodi->strata;
        $namaProdi = $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok[0]->prodi->nama_prodi;
        $namaJurusan = $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok[0]->prodi->jurusan->nama_jurusan;
    @endphp
@endif
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
                    <td><span style="display:inline-block;width:20px;height:auto"></span>{{$suratMaterial->nomor_surat}}/{{$suratMaterial->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratMaterial->created_at->year}}</td>
                    <td class="text-right">{{$suratMaterial->created_at->isoFormat('D MMMM Y')}}</td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td colspan="2"><span style="display:inline-block;width:20px;height:auto"></span>Lembar</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td colspan="2">Permohonan pengambilan material</td>
                </tr>
            </table> 
             <p class="m-0"style="margin-top:20px; padding-left: 15px;padding-right: 15px;">
               <b>
                    Yth. <br>
                    {{ $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->kepada }} <br>
                    Di Tempat
               </b>
             </p>
            <div class="content mt-1">

            <p class="m-0"style="margin-top:20px">
                Sehubungan dengan kegiatan {{ ucwords($suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->nama_kegiatan) }} <b>Program Studi {{ $strata }} - {{ $namaProdi }} Jurusan {{ $namaJurusan }} Fakultas Teknik Universitas Negeri Gorontalo</b>, maka dengan ini kami memohon bantuan Bapak/Ibu untuk diperkenankan mahasiswa kami dapat melakukan pengambilan material. (Nama-nama terlampir).
            </p>
            <p class="m-0">
                Demikian surat ini disampaikan, atas kerjasamanya diucapkan terima kasih.
            </p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>{{$suratMaterial->created_at->isoFormat('D MMMM Y')}}</b></p>
                @if($suratMaterial->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratMaterial->user->jabatan == 'wd1')
                        <p class="m-0"><b>Wakil Dekan Bidang Akademik,</b></p>
                    @elseif($suratMaterial->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratMaterial->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratMaterial->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratMaterial->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:105px">
            <?= $qrCode ?>
        </div>
         <div style="height:350px"></div>
            <p class="m-0">
                <b>Lampiran :</b>
            </p>
            <p class="m-0 text-center">
                <b>{{ $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->nama_kelompok }}</b>
            </p>
            @if($suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok->count() > 0)
                    <table class="m-0 text-center table table-margin">
                    <tr class="table">
                        <th class="table">NO</th>
                        <th class="table">NIM</th>
                        <th class="table">NAMA</th>
                    </tr>
                    @foreach($suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->daftarKelompok as $mahasiswa)
                        <tr class="table">
                            <td class="table">{{ $loop->iteration }}</td>
                            <td class="table">{{ $mahasiswa->nama }}</td>
                            <td class="table">{{ $mahasiswa->nim }}</td>
                        </tr>
                    @endforeach
                    </table>
            @endif
   </div>
</body>
</html>