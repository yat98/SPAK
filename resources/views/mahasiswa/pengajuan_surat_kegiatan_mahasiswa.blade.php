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
                            <i class="mdi mdi-file-document-box "></i>
                        </span> Surat Kegiatan Mahasiswa</h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Kegiatan Mahasiswa <i class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanKegiatan > 0 ? $countAllPengajuanKegiatan.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
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
                                        <h4>Surat Kegiatan Mahasiswa</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa/create')}}" class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Buat Pengajuan Surat Kegiatan Mahasiswa</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPengajuanSurat > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Kegiatan</th>
                                                <th> Ormawa</th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanKegiatanList as $pengajuanKegiatan)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanKegiatanList->currentPage() - 1)  }}</td>
                                                <td> {{ $pengajuanKegiatan->nama_kegiatan }}</td>
                                                <td> {{ $pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                                                <td>
                                                @if($pengajuanKegiatan->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @elseif($pengajuanKegiatan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @elseif($pengajuanKegiatan->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @else
                                                    <label class="badge badge-gradient-success">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @endif
                                                </td>
                                                <td> {{ $pengajuanKegiatan->keterangan }}</td>
                                                <td> {{ $pengajuanKegiatan->created_at->diffForHumans() }}</td>
                                                <td> {{ $pengajuanKegiatan->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa/'.$pengajuanKegiatan->id.'/progress') }}" class=" btn btn-outline-info btn-sm btn-surat-kegiatan-mahasiswa-progress" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi mdi-information btn-icon-prepend"></i>
                                                        Lihat Progress Surat</a>

                                                    @if($pengajuanKegiatan->status == 'selesai')
                                                    <a href="{{ url('mahasiswa/surat-kegiatan-mahasiswa/'.$pengajuanKegiatan->id) }}" class="btn btn-outline-info btn-sm">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                    @else
                                                     <a href="{{ url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa/'.$pengajuanKegiatan->id) }}" class="btn btn-outline-info btn-sm">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>    
                                                    @endif
                                                    
                                                    @if($pengajuanKegiatan->status == 'selesai' && $pengajuanKegiatan->jumlah_cetak <= 2) <a href="{{ url('mahasiswa/surat-kegiatan-mahasiswa/'.$pengajuanKegiatan->id.'/cetak') }}" class="btn btn-info btn-sm">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    @endif

                                                     @if($pengajuanKegiatan->status == 'diajukan')
                                                     <a href="{{ url('mahasiswa/pengajuan/surat-kegiatan-mahasiswa/'.$pengajuanKegiatan->id.'/edit') }}" class="text-dark btn btn-warning btn-sm">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                     @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $pengajuanKegiatanList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Kegiatan Mahasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Pengajuan surat kegiatan mahasiswa belum ada.' }}
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Progress Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-progress-content'>
                

            </div>
        </div>
    </div>
</div>
@endsection
