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
            font-size: 15px;
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
        ol{
            margin-top:-0.3px
        }
    </style>
</head>
<body>
   <div class="container">
        @include('surat.kop_surat')
        <div class="border"></div>
        <div class="text-center mt-1 underline">
            <p class="m-0">KEPUTUSAN DEKAN FAKULTAS TEKNIK UNIVERSITAS NEGERI GORONTALO</p>
            <p class="m-0" style="margin-top:2px"><i><span style="display:inline-block;width:20px;height:auto"></span>{{$suratKegiatan->nomor_surat}}/{{$suratKegiatan->kodeSurat->kode_surat}}<span style="display:inline-block;width:20px;height:auto"></span>/<span style="display:inline-block;width:50px;height:auto"></span>/{{$suratKegiatan->created_at->year}}</i></p>
            <p style="margin:12px 0px">TENTANG</p>
            <p>PANITIA KEGIATAN {{ strtoupper($suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan) }}</p>
            <p>{{ strtoupper($suratKegiatan->pengajuanSuratKegiatanMahasiswa->ormawa->nama) }}</p>
            <p>FAKULTAS TEKNIK</p>
            <p>UNIVERSITAS NEGERI GORONTALO</p>
        </div>
        <div class="text-center">
            <p>DEKAN FAKULTAS TEKNIK UNIVERSITAS NEGERI GORONTALO</p>
        </div>
        <div class="content">
             <table>
                <tr>
                    <td valign="top">Menimbang</td>
                    <td valign="top">:</td>
                    <td><?= $suratKegiatan->menimbang ?></td>
                </tr>
                <tr>
                    <td valign="top">Mengingat</td>
                    <td valign="top">:</td>
                    <td><?= $suratKegiatan->mengingat ?></td>
                </tr>
                <tr>
                    <td valign="top">Memperhatikan</td>
                    <td valign="top">:</td>
                    <td>
                        <ol>
                            <li style="list-style-type: none;"><?= $suratKegiatan->memperhatikan ?></li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:center;padding-bottom:20px">MEMUTUSKAN</td>
                </tr>
                <tr>
                    <td valign="top">Menetapkan</td>
                    <td valign="top">:</td>
                    <td>
                        <ol>
                            <li style="list-style-type: none"><?= $suratKegiatan->menetapkan ?></li>
                        </ol>
                    </td>
                </tr>
                 <tr>
                    <td valign="top">Kesatu</td>
                    <td valign="top">:</td>
                    <td>
                        <ol>
                            <li style="list-style-type: none"><?= $suratKegiatan->kesatu ?></li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Kedua</td>
                    <td valign="top">:</td>
                    <td>
                        <ol>
                            <li style="list-style-type: none"><?= $suratKegiatan->kedua ?></li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Ketiga</td>
                    <td valign="top">:</td>
                    <td>
                        <ol>
                            <li style="list-style-type: none"><?= $suratKegiatan->ketiga ?></li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Keempat</td>
                    <td valign="top">:</td>
                    <td>
                        <ol>
                            <li style="list-style-type: none"><?= $suratKegiatan->keempat ?></li>
                        </ol>
                    </td>
                </tr>
             </table>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0">Ditetapkan di Gorontalo,</p>
                <p class="m-0">pada tanggal {{ $suratKegiatan->updated_at->isoFormat('D MMMM Y') }},</p>
                <p class="m-0">DEKAN FAKULTAS TEKNIK UNIVERSITAS NEGERI GORONTALO</p>
                @if($suratKegiatan->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan</b></p>
                @else
                    <p class="m-0"><b>a.n Dekan,</b></p>
                    @if($suratKegiatan->user->jabatan == 'wd3')
                        <p class="m-0"><b>Wakil Dekan III</b></p>
                    @elseif($suratKegiatan->user->jabatan == 'kabag tata usaha')
                        <p class="m-0"><b>Kabag TU</b></p>
                    @endif
                @endif

                <p class="m-0 tanda-tangan-margin">
                    @if($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratKegiatan->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratKegiatan->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratKegiatan->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:60px;margin-bottom:15px">
            <?= $qrCode ?>
        </div>
   </div>
   <br style="clear:both">
   <div style="height:200px"></div>
   <div class="container">
        @include('surat.kop_surat')
        <div class="border"></div>
        <div class="content" id="lampiran" style="color:black !important;">
             <?= $suratKegiatan->pengajuanSuratKegiatanMahasiswa->lampiran_panitia ?></p>
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0">Ditetapkan di Gorontalo,</p>
                <p class="m-0">pada tanggal {{ $suratKegiatan->updated_at->isoFormat('D MMMM Y') }},</p>
                <p class="m-0">DEKAN FAKULTAS TEKNIK UNIVERSITAS NEGERI GORONTALO</p>
                <p class="m-0 tanda-tangan-margin">
                     @if($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status == 'selesai')
                        <img class="tanda-tangan" src="{{$suratKegiatan->user->tanda_tangan}}">
                    @else
                        <div class="tanda-tangan"></div>
                    @endif
                </p>
                <p class="m-0"><b>{{$suratKegiatan->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{$suratKegiatan->user->nip}}</b></p>
            </div>
        </div>
        <div class="content" style="padding-top:60px;margin-bottom:15px">
            <?= $qrCode ?>
        </div>
    </div>
    <script>
        let lampiran = document.querySelector('#lampiran p');
        lampiran.setAttribute("style",'margin:0in;margin-bottom:.0001pt;font-size:15px;font-family:"sans-serif";');
    </script>
</body>
</html>