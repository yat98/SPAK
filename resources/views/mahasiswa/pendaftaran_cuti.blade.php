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
                        </span> Pendaftaran Cuti</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-dark card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pendaftaran Cuti <i
                                        class="mdi mdi-playlist-check mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countPendaftaranCuti > 0 ? $countPendaftaranCuti.' Pendaftaran Cuti' : 'Pendaftaran Cuti Kosong' }}
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
                                        <h4>Pendaftaran Cuti</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('mahasiswa/pendaftaran-cuti/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Buat Pendaftaran Cuti</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPendaftaranCuti > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama </th>
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
                                                <td> {{ $loop->iteration + $perPage * ($pendaftaranCutiList->currentPage() - 1)  }}</td>
                                                <td> {{ $pendaftaranCuti->mahasiswa->nama }}</td>
                                                <td> {{ $pendaftaranCuti->waktuCuti->tahunAkademik->tahun_akademik }} - {{ ucwords($pendaftaranCuti->waktuCuti->tahunAkademik->semester) }}</td>
                                                <td> {{ $pendaftaranCuti->alasan_cuti }}</td>
                                                <td> 
                                                @if($pendaftaranCuti->status == 'diajukan')
                                                <label class="badge badge-gradient-warning text-dark">{{ ucwords($pendaftaranCuti->status) }}</td></label>
                                                @elseif($pendaftaranCuti->status == 'ditolak')
                                                <label class="badge badge-gradient-danger">{{ ucwords($pendaftaranCuti->status) }}</td></label>
                                                @else
                                                <label class="badge badge-gradient-info">{{ ucwords($pendaftaranCuti->status) }}</td></label>
                                                @endif
                                                <td> {{ $pendaftaranCuti->keterangan }}</td>
                                                <td>
                                                    <a href="{{ url('mahasiswa/pendaftaran-cuti/'.$pendaftaranCuti->id) }}" class="btn btn-outline-info btn-sm btn-pendaftaran-cuti-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-playlist-check btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                    @if($pendaftaranCuti->status == 'diajukan')
                                                      <a href="{{ url('mahasiswa/pendaftaran-cuti/'.$pendaftaranCuti->id.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
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
                                        {{ $pendaftaranCutiList->links() }}
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
                                            {{ (Session::has('search')) ? Session::get('search') : 'Pendaftaran cuti belum ada.' }}
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