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
                        </span> Pengajuan Surat Kegiatan Mahasiswa </h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Pengajuan Surat Kegiatan Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        @php
                                            
                                        @endphp
                                        <tr>
                                            <th>Nomor Surat</th>
                                            <td>{{ $pengajuanKegiatan->nomor_surat_permohonan_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kegiatan</th>
                                            <td>{{ $pengajuanKegiatan->nama_kegiatan }}</td>
                                        </tr>
                                         <tr>
                                            <th>Diajukan Oleh</th>
                                            <td>{{ $pengajuanKegiatan->mahasiswa->nama }}</td>
                                        </tr>
                                         <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($pengajuanKegiatan->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($pengajuanKegiatan->status) }}</td></label>
                                                @elseif($pengajuanKegiatan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($pengajuanKegiatan->status) }}</td></label>
                                                @elseif($pengajuanKegiatan->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($pengajuanKegiatan->status) }}</td></label>
                                                @else
                                                    <label class="badge badge-gradient-success">{{ ucwords($pengajuanKegiatan->status) }}</td></label>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Permohonan Kegiatan</th>
                                            <td>
                                                <a href="{{ url('upload_surat_permohonan_kegiatan/'.$pengajuanKegiatan->file_surat_permohonan_kegiatan) }}" class="btn btn-info btn-sm" data-lightbox="{{ explode('.',$pengajuanKegiatan->file_surat_permohonan_kegiatan)[0] }}">
                                                    <i class="mdi mdi mdi-eye"></i>
                                                        Lihat File Surat Masuk
                                                </a>
                                            </td>
                                        </tr>
                                         <tr>
                                            <th colspan="2">Lampiran Panitia</th>
                                        </tr>
                                    </table>
                                    <textarea id="froala-editor-disabled">
                                        {{ ($pengajuanKegiatan->lampiran_panitia) }}
                                    </textarea>
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