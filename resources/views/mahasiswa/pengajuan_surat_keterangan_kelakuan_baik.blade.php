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
                        </span> Pengajuan Surat Keterangan Kelakuan Baik</h3>
                </div>
                <div class="row">
                    <div class="col-md-5 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Keterangan Kelakuan Baik<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuan > 0 ? $countAllPengajuan.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Pengajuan Surat Keterangan Kelakuan Baik</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('mahasiswa/pengajuan/surat-keterangan-kelakuan-baik/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Buat Pengajuan Surat Keterangan Kelakuan Baik</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPengajuanSuratKeterangan > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama </th>
                                                <th> Jenis Surat</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanSuratKeteranganList as $pengajuanSuratKeterangan)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                                                <td> {{ ucwords($pengajuanSuratKeterangan->jenis_surat)  }}</td>
                                                <td>
                                                    @if ($pengajuanSuratKeterangan->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuanSuratKeterangan->status) }}
                                                    </label>
                                                    @elseif($pengajuanSuratKeterangan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuanSuratKeterangan->status) }}
                                                    </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($pengajuanSuratKeterangan->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $pengajuanSuratKeterangan->created_at->diffForHumans() }}</td>
                                                <td> {{ $pengajuanSuratKeterangan->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-keterangan-kelakuan-baik/'.$pengajuanSuratKeterangan->id.'/progress') }}" class="btn-surat-progress btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi mdi-information btn-icon-prepend"></i>
                                                        Lihat Progress Surat</a>

                                                    @if ($pengajuanSuratKeterangan->status == 'selesai' && $pengajuanSuratKeterangan->suratKeterangan->jumlah_cetak <= 2)
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-keterangan-kelakuan-baik/'.$pengajuanSuratKeterangan->suratKeterangan->id_pengajuan_surat_keterangan.'/cetak') }}" class="btn btn-info btn-sm">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Keterangan Kelakuan Baik Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Pengajuan surat keterangan kelakuan baik belum ada.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Progress Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-progress-content'></div>
        </div>
    </div>
</div>
@endsection