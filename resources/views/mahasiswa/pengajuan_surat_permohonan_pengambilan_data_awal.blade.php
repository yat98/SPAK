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
                        </span> Surat Permohonan Pengambilan Data Awal</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Permohonan Pengambilan Data Awal<i
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
                                        <h4>Pengajuan Surat Permohonan Pengambilan Data Awal</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('mahasiswa/pengajuan/surat-permohonan-pengambilan-data-awal/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Buat Pengajuan Surat Permohonan Pengambilan Data Awal</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPengajuanSuratDataAwal > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama </th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanSuratDataAwalList as $pengajuanSuratDataAwal)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $pengajuanSuratDataAwal->mahasiswa->nama }}</td>
                                                <td>
                                                    @if ($pengajuanSuratDataAwal->status == 'diajukan' || $pengajuanSuratDataAwal->status == 'menunggu tanda tangan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuanSuratDataAwal->status) }}
                                                    </label>
                                                    @elseif($pengajuanSuratDataAwal->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuanSuratDataAwal->status) }}
                                                    </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($pengajuanSuratDataAwal->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $pengajuanSuratDataAwal->keterangan }}</td>
                                                <td>
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-permohonan-pengambilan-data-awal/'.$pengajuanSuratDataAwal->id.'/progress') }}" class="btn-surat-lulus btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi mdi-information btn-icon-prepend"></i>
                                                        Lihat Progress Surat</a>

                                                    @if($pengajuanSuratDataAwal->status == 'selesai' || $pengajuanSuratDataAwal->status == 'menunggu tanda tangan')
                                                        <a href="{{ url('mahasiswa/surat-permohonan-pengambilan-data-awal/'.$pengajuanSuratDataAwal->id) }}" class="btn-surat-lulus-detail  btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratDataAwal">
                                                            <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                            Detail
                                                        </a>
                                                    @else
                                                        <a href="{{ url('mahasiswa/pengajuan/surat-permohonan-pengambilan-data-awal/'.$pengajuanSuratDataAwal->id) }}" class="btn-pengajuan-surat-lulus-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratDataAwal">
                                                            <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                            Detail
                                                        </a>    
                                                    @endif

                                                    @if ($pengajuanSuratDataAwal->status == 'selesai' && $pengajuanSuratDataAwal->suratPermohonanPengambilanDataAwal->jumlah_cetak <= 2)
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-permohonan-pengambilan-data-awal/'.$pengajuanSuratDataAwal->id.'/cetak') }}" class="btn btn-info btn-sm">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    @endif

                                                    @if ($pengajuanSuratDataAwal->status == 'diajukan')
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-permohonan-pengambilan-data-awal/'.$pengajuanSuratDataAwal->id.'/edit') }}" class="btn btn-sm btn-warning text-dark">
                                                            <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                            Edit
                                                    </a>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Pengajuan surat permohonan pengambilan data awal belum ada.' }}
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
<div class="modal fade" id="suratDataAwal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-keterangan-survei-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection