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
                                        <h4>Surat Kegiatan Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Nomor Surat</th>
                                            <td>{{ $suratKegiatan->nomor_surat }}/{{ $suratKegiatan->kodeSurat->kode_surat }}/{{ $suratKegiatan->created_at->year }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nomor Surat Permohonan</th>
                                            <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->nomor_surat_permohonan_kegiatan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kegiatan</th>
                                            <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan }}</td>
                                        </tr>
                                         <tr>
                                            <th>Diajukan Oleh</th>
                                            <td>{{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ditandatangai Oleh</th>
                                            <td>{{ $suratKegiatan->user->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Cetak</th>
                                            <td>{{ $suratKegiatan->jumlah_cetak }}</td>
                                        </tr>
                                         <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status) }}</td></label>
                                                @elseif($pengajuanSuratKegiatanMahasiswa->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status) }}</td></label>
                                                @elseif($pengajuanSuratKegiatanMahasiswa->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status) }}</td></label>
                                                @else
                                                    <label class="badge badge-gradient-success">{{ ucwords($suratKegiatan->pengajuanSuratKegiatanMahasiswa->status) }}</td></label>
                                                @endif
                                            </td>
                                        </tr>
                                         <tr>
                                            <th>Menimbang</th>
                                            <td><?= $suratKegiatan->menimbang ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mengingat</th>
                                            <td><?= $suratKegiatan->mengingat ?></td>
                                        </tr>
                                        <tr>
                                            <th>Memperhatikan</th>
                                            <td><?= $suratKegiatan->memperhatikan ?></td>
                                        </tr>
                                         <tr>
                                            <th>Menetapkan</th>
                                            <td><?= $suratKegiatan->menetapkan ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kesatu</th>
                                            <td><?= $suratKegiatan->kesatu ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kedua</th>
                                            <td><?= $suratKegiatan->kedua ?></td>
                                        </tr>
                                        <tr>
                                            <th>Ketiga</th>
                                            <td><?= $suratKegiatan->ketiga ?></td>
                                        </tr>
                                        <tr>
                                            <th>Keempat</th>
                                            <td><?= $suratKegiatan->keempat ?></td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Permohonan Kegiatan</th>
                                            <td>
                                                <a href="{{ url('upload_surat_permohonan_kegiatan/'.$suratKegiatan->pengajuanSuratKegiatanMahasiswa->file_surat_permohonan_kegiatan) }}" class="btn btn-info btn-sm" data-lightbox="{{ explode('.',$suratKegiatan->pengajuanSuratKegiatanMahasiswa->file_surat_permohonan_kegiatan)[0] }}">
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
                                        {{ ($suratKegiatan->pengajuanSuratKegiatanMahasiswa->lampiran_panitia) }}
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