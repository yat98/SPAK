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
            line-height: 1;
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
    </style>
</head>
<body>
   <div class="container">
        @include('surat.kop_surat')
        <div class="border"></div>
        <div class="text-center mt-1">
            <p class="m-0"><b><span class="underline">SURAT DISPENSASI</span></b></p>
            @php
                $kode = explode('/',$suratDispensasi->kodeSurat->kode_surat);
            @endphp
             @if($suratDispensasi->user->jabatan == 'dekan')
                <p class="m-0"><b><i>Nomor : {{$suratDispensasi->nomor_surat}}/{{ $suratDispensasi->kodeSurat->kode_surat }}/{{$suratDispensasi->created_at->year}}</i></b></p>
            @else
                <p class="m-0"><b><i>Nomor : {{$suratDispensasi->nomor_surat}}/{{$kode[0].'.3/'.$kode[1]}}/{{$suratDispensasi->created_at->year}}</i></b></p>
            @endif
        </div>
        <div class="content">
            <p class="m-0">Memperhatikan surat dari {{ ucwords($suratDispensasi->suratMasuk->instansi) }} Nomor : {{ $suratDispensasi->suratMasuk->nomor_surat }}, {{ $suratDispensasi->suratMasuk->tanggal_surat_masuk->isoFormat('D MMMM Y') }} perihal <b>{{ $suratDispensasi->suratMasuk->perihal }}</b> maka dengan ini Dekan Fakultas Teknik Memberikan dispensasi kepada:</p>
            @if($suratDispensasi->mahasiswa->count() > 0)
                    <table class="m-0 text-center table table-margin">
                    <tr class="table">
                        <th class="table">No</th>
                        <th class="table">Nama</th>
                        <th class="table">NIM</th>
                        <th class="table">PRODI</th>
                        <th class="table">JURUSAN</th>
                    </tr>
                    @foreach($suratDispensasi->mahasiswa as $mahasiswa)
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
            <p class="m-0">Sebagai peserta pada kegiatan <b>{{ ucwords($suratDispensasi->nama_kegiatan) }}</b>, dengan tahapan pelaksanaan sebagai berikut:</p>
            @if($suratDispensasi->tahapanKegiatanDispensasi->count() > 0)
            <table>
            @foreach($suratDispensasi->tahapanKegiatanDispensasi as $tahapan)
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
            <p class="m-0">Demikian surat keterangan ini di buat dan diberikan kepada yang bersangkutan untuk dipergunakan sebagaimana mestinya.</p>
            {{-- <div style="height:180px"></div> --}}
        </div>
        <div class="signature">
            <div class="signature-content">
                <p class="m-0"><b>Gorontalo, {{$suratDispensasi->created_at->format('d F Y')}}</b></p>
                @if($suratDispensasi->user->jabatan == 'dekan')
                    <p class="m-0"><b>Dekan,</b></p>
                @else
                    <p class="m-0"><b>Wakil Dekan 3,</b></p>
                @endif
                <p class="m-0 tanda-tangan-margin">
                    {{-- <img class="tanda-tangan" src="{{$suratKeterangan->user->tanda_tangan}}"> --}}
                    <img class="tanda-tangan" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAegAAAC7CAYAAACuPQ3hAAAgAElEQVR4Xu1dBxAeRdl+KQLB+FOk9yKEqoQAAUHpRXoEMoCAFAUFlBKkKKCAIiEQjYA0GQ1IZ5DeGUUJnUGQYEBAqUkERAgdgX8ez5vct9+7d3t3e3d79z07kyHku23P7t2z77tvmeWTTz75RFiIABEgAkSACBCBoBCYhQQd1HpwMESACBABIkAE/osACZobgQgQASJABIhAgAiQoANcFA6JCBABIkAEiAAJmnuACBABIkAEiECACJCgA1wUDokIEAEiQASIAAmae4AIEAEiQASIQIAIkKADXBQOiQgQASJABIgACZp7gAgQASJABIhAgAiQoANcFA6JCBABIkAEiAAJmnuACBABIkAEiECACJCgA1wUDokIEAEiQASIAAmae4AIEAEiQASIQIAIkKADXBQOiQgQASJABIgACZp7gAgQASJABIhAgAiQoANcFA6JCBABIkAEiAAJmnuACBABIkAEiECACJCgA1wUDokIEAEiQASIAAmae4AIEAEiQASIQIAIkKADXBQOiQgQASJABIgACZp7gAgQASJABIhAgAiQoANcFA6JCBABIkAEiAAJmnuACBABIkAEiECACJCgA1wUDokIEAEiQASIAAmae4AIEAEiQASIQIAIkKADXBQOiQgQASJABIgACZp7gAgQASJABIhAgAiQoANcFA6JCBABIkAEiAAJmnuACBABIkAEiECACJCgA1wUDokIEAEiQASIAAmae4AIEAEiQASIQIAIkKADXBQOiQgQASJABIgACZp7gAh4QODf/xZ55hmRGTNENtrIQ4NsgggQgYFHgAQ98FuAAJRF4LDDRC64ICLnuOy2m8hzz4msuabImDEiyyxTthfWJwJEYNAQIEEP2opzvt4Q+Mc/RI45RuSyy7Kb3HFHEfzZYQeReefNfp5PEAEiQARI0NwDRKAAAn/4g8jWW4u8+27+ynvvLXLIISJrrJG/LmsQASIwOAiQoAdnrTlTjwiAXB99tFyDhx4q8sMfUqIuhyJrE4HuIkCC7u7acmYVIjDLLH4aB9H/+teUpv2gyVaIQLcQIEF3az05m5oQwD3yG2/0dwYLbkjFUIGPH99rOGYbGtr6/e9J0jUtHbshAq1BgATdmqXiQENCAER81139I9pww4icUeB69fOf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">
                </p>
                <p class="m-0"><b>{{$suratDispensasi->user->nama}}</b></p>
                <p class="m-0"><b>NIP. {{substr($suratDispensasi->user->nip,0,8)}} {{substr($suratDispensasi->user->nip,8,6)}} {{substr($suratDispensasi->user->nip,14,1)}} {{substr($suratDispensasi->user->nip,15,3)}}</b></p>
            </div>
        </div>
   </div>
</body>
</html>