@extends('template')

@section('content')
<div class="container-scroller">
    @include('layout.navbar_top')
    <div class="container-fluid page-body-wrapper">
        @include('layout.navbar_side')
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary text-white mr-2">
                            <i class="mdi mdi-file-document-box"></i>
                        </span> Surat Kegiatan Mahasiswa </h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Detail</h4>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Nomor Surat Permohonan Kegiatan</th>
                                            <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->nomor_surat_permohonan_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kegiatan</th>
                                            <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Ormawa</th>
                                            <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->ormawa->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nomor Surat</th>
                                            <td>{{ $suratKegiatan->nomor_surat }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kode Surat</th>
                                            <td>{{ $suratKegiatan->kodeSurat->kode_surat }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tahun</th>
                                            <td>{{ $suratKegiatan->created_at->isoFormat('Y') }}</td>
                                        </tr>
                                        @if($suratKegiatan->pengajuanSuratKegiatanMahasiswa->nim == null)
                                            <tr>
                                                <th>Diajukan Oleh</th>
                                                <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->operator->nama }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th>Diajukan Oleh</th>
                                                <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->nama }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status) }}</label>
                                                @elseif($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status) }}</label>
                                                @else
                                                    @php
                                                        switch($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status){
                                                            case 'disposisi wd1':
                                                                $suratKegiatan->pengajuanSuratKegiatanMahasiswa->status = 'Disposisi WD1';
                                                                break;
                                                            case 'disposisi wd2':
                                                                $suratKegiatan->pengajuanSuratKegiatanMahasiswa->status = 'Disposisi WD2';
                                                                break;
                                                            case 'disposisi wd3':
                                                                $suratKegiatan->pengajuanSuratKegiatanMahasiswa->status = 'Disposisi WD3';
                                                                break;
                                                            default:
                                                                $suratKegiatan->pengajuanSuratKegiatanMahasiswa->status = ucwords($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status);
                                                                break;
                                                        }
                                                    @endphp
                                                    <label class="badge badge-gradient-warning text-dark">{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->status }}</label>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->keterangan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Di Tandatangani Oleh</th>
                                            <td>{{ $suratKegiatan->user->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Cetak</th>
                                            <td>{{ $suratKegiatan->jumlah_cetak }}</td>
                                        </tr>
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->created_at->isoFormat('D MMMM Y HH:mm:ss') }}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Permohonan Kegiatan</th>
                                            <td>
                                                <a href="{{ url('upload_surat_permohonan_kegiatan/'.$suratKegiatan->pengajuanSuratKegiatanMahasiswa->file_surat_permohonan_kegiatan) }}" class="btn btn-info btn-sm" data-lightbox="{{ explode('.',$suratKegiatan->pengajuanSuratKegiatanMahasiswa->file_surat_permohonan_kegiatan)[0] }}">
                                                    <i class="mdi mdi mdi-eye"></i>
                                                        Lihat File Surat Permohonan Kegiatan
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Lampiran Panitia</th>
                                        </tr>
                                    </table>
                                    <textarea id="froala-editor-disabled">
                                        {{ ($suratKegiatan->pengajuanSuratKegiatanMahasiswa->lampiran_panitia) }}
                                    </textarea>
                                    <table class="mt-4">
                                        <th>
                                            File Proposal Kegiatan
                                        </th>
                                    </table>
                                    <embed src="{{ url('upload_proposal_kegiatan/'.$suratKegiatan->pengajuanSuratKegiatanMahasiswa->file_proposal_kegiatan) }}" class="mt-2 d-block"  frameborder="0" height="800px" width="100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>
@endsection