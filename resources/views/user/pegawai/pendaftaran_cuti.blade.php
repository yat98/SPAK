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
                            <i class="mdi mdi-playlist-check"></i>
                        </span> Pendaftaran Cuti </h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Pendaftaran Cuti<i
                                        class="mdi mdi-playlist-check menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanCuti > 0 ? $countAllPengajuanCuti.' Pengajuan Pendaftaran Cuti' : 'Pengajuan Pendaftaran Cuti Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-dark card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pendaftaran Cuti <i
                                        class="mdi mdi-playlist-check menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPendaftaranCuti > 0 ? $countAllPendaftaranCuti.' Pendaftaran Cuti' : 'Pendaftaran Cuti Kosong' }}
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
                                        <h4>Pengajuan Pendaftaran Cuti</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPengajuanCuti > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Tahun Akademik</th>
                                                <th> Alasan Cuti</th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanCutiList as $pengajuanCuti)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanCutiList->currentPage() - 1) }}</td>
                                                <td> {{ $pengajuanCuti->mahasiswa->nama }}</td>
                                                <td> {{ $pengajuanCuti->waktuCuti->tahunAkademik->tahun_akademik }} - {{ ucwords($pengajuanCuti->waktuCuti->tahunAkademik->semester) }}</td>
                                                <td> {{ $pengajuanCuti->alasan_cuti }}</td>
                                                <td> 
                                                    @if ($pengajuanCuti->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuanCuti->status) }}
                                                    </label>
                                                    @elseif($pengajuanCuti->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuanCuti->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $pengajuanCuti->keterangan }}</td>
                                                <td>
                                                    <a href="{{ url('pegawai/pendaftaran-cuti/'.$pengajuanCuti->id) }}" class="btn btn-outline-info btn-sm btn-pendaftaran-cuti-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-playlist-check btn-icon-prepend"></i>
                                                        Detail
                                                    </a>

                                                    @if ($pengajuanCuti->status == 'diajukan')
                                                    {{ Form::open(['method'=>'PATCH','action'=>['PendaftaranCutiController@terima',$pengajuanCuti->id],'class'=>'d-inline-block']) }}
                                                    <button type="submit" class="btn btn-info btn-sm btn-terima">
                                                        <i class="mdi mdi mdi-check btn-icon-prepend"></i>
                                                        Terima
                                                    </button>
                                                    {{ Form::close() }}

                                                    {{ Form::open(['method'=>'PATCH','action'=>['PendaftaranCutiController@tolak',$pengajuanCuti->id],'class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('keterangan','-',['id'=>'keterangan_surat']) }}
                                                    <button type="submit" class="btn btn-danger btn-sm btn-tolak">
                                                        <i class="mdi mdi mdi-close btn-icon-prepend"></i>
                                                        Tolak
                                                    </button>
                                                    {{ Form::close() }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $pengajuanCutiList->appends(['page' => $pengajuanCutiList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Pendaftaran Cuti Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Belum ada pengajuan pendaftaran cuti.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Pendaftaran Cuti</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('pegawai/pendaftaran-cuti/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Pendaftaran Cuti</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        {{ Form::open(['url'=>'pegawai/pendaftaran-cuti/search','method'=>'get']) }}
                                        <div class="form-row">
                                            <div class="col-sm-4 col-md-4 mt-1">
                                                {{ Form::select('keywords',$mahasiswa,(request()->get('keywords') != null) ? request()->get('keywords'):null,['class'=>'form-control search','placeholder'=> 'Cari mahasiswa...']) }}
                                            </div>
                                              <div class="col-sm-4 col-md-4 mt-1">
                                            {{ Form::select('waktu_cuti',$waktuCuti,(request()->get('waktu_cuti') != null) ? request()->get('waktu_cuti'):null,['class'=>'form-control search','placeholder'=> 'Cari waktu cuti...']) }}
                                            </div> 
                                            <div class="col-sm-12 col-md">
                                                <button class="btn btn-success btn-sm btn-tambah" type="submit">
                                                    <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                @if ($countPendaftaranCuti > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Tahun Akademik</th>
                                                <th> Alasan Cuti</th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pendaftaranCutiList as $pendaftaranCuti)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pendaftaranCutiList->currentPage() - 1) }}</td>
                                                <td> {{ $pendaftaranCuti->mahasiswa->nama }}</td>
                                                <td> {{ $pendaftaranCuti->waktuCuti->tahunAkademik->tahun_akademik }} - {{ ucwords($pendaftaranCuti->waktuCuti->tahunAkademik->semester) }}</td>
                                                <td> {{ $pendaftaranCuti->alasan_cuti }}</td>
                                                <td> 
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($pendaftaranCuti->status) }}
                                                    </label>
                                                </td>
                                                 <td> {{ $pendaftaranCuti->keterangan }}</td>
                                                <td>
                                                    <a href="{{ url('pegawai/pendaftaran-cuti/'.$pendaftaranCuti->id) }}" class="btn btn-outline-info btn-sm btn-pendaftaran-cuti-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-playlist-check btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                    <a href="{{ url('pegawai/pendaftaran-cuti/'.$pendaftaranCuti->id.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['PendaftaranCutiController@destroy',$pendaftaranCuti->id],'class'=>'d-inline-block']) }}
                                                    <button type="submit" class="btn btn-danger btn-sm sweet-delete">
                                                        <i class="mdi mdi-delete-forever btn-icon-prepend"></i>
                                                        Hapus
                                                    </button>
                                                    {{ Form::close() }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $pengajuanCutiList->appends(['page_pengajuan' => $pendaftaranCutiList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pendaftaran Cuti Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi pendaftaran cuti terlebih dahulu.' }}
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
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='pendaftaran-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection