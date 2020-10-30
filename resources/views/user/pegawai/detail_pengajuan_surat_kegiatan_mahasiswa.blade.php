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
                                            <td>{{ $pengajuanKegiatan->nomor_surat_permohonan_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kegiatan</th>
                                            <td>{{ $pengajuanKegiatan->nama_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Ormawa</th>
                                            <td>{{ $pengajuanKegiatan->ormawa->nama }}</td>
                                        </tr>
                                        @if($pengajuanKegiatan->nim == null)
                                            <tr>
                                                <th>Diajukan Oleh</th>
                                                <td>{{ $pengajuanKegiatan->operator->nama }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th>Diajukan Oleh</th>
                                                <td>{{ $pengajuanKegiatan->mahasiswa->nama }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($pengajuanKegiatan->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @elseif($pengajuanKegiatan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @else
                                                    @php
                                                        switch($pengajuanKegiatan->status){
                                                            case 'disposisi wd1':
                                                                $pengajuanKegiatan->status = 'Disposisi WD1';
                                                                break;
                                                            case 'disposisi wd2':
                                                                $pengajuanKegiatan->status = 'Disposisi WD2';
                                                                break;
                                                            case 'disposisi wd3':
                                                                $pengajuanKegiatan->status = 'Disposisi WD3';
                                                                break;
                                                            default:
                                                                $pengajuanKegiatan->status = ucwords($pengajuanKegiatan->status);
                                                                break;
                                                        }
                                                    @endphp
                                                    <label class="badge badge-gradient-warning text-dark">{{ $pengajuanKegiatan->status }}</label>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>{{ $pengajuanKegiatan->keterangan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>{{ $pengajuanKegiatan->created_at->isoFormat('D MMMM Y HH:mm:ss') }}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Permohonan Kegiatan</th>
                                            <td>
                                                <a href="{{ url('upload_surat_permohonan_kegiatan/'.$pengajuanKegiatan->file_surat_permohonan_kegiatan) }}" class="btn btn-info btn-sm" data-lightbox="{{ explode('.',$pengajuanKegiatan->file_surat_permohonan_kegiatan)[0] }}">
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
                                        {{ ($pengajuanKegiatan->lampiran_panitia) }}
                                    </textarea>
                                    <table class="mt-4">
                                        <th>
                                            File Proposal Kegiatan
                                        </th>
                                    </table>
                                    <embed src="{{ url('upload_proposal_kegiatan/'.$pengajuanKegiatan->file_proposal_kegiatan) }}" class="mt-2 d-block"  frameborder="0" height="800px" width="100%">
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