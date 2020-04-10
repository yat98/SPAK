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
                            <i class="mdi mdi-checkbox-multiple-marked"></i>
                        </span> Status Mahasiswa </h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tahun Akademik<i
                                        class="mdi mdi-calendar-text mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ isset($tahunAkademikAktif) ? $tahunAkademikAktif->tahun_akademik.' - '.ucwords($tahunAkademikAktif->semester) : 'Tidak Ada Tahun Akademik Aktif' }}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Mahasiswa<i
                                        class="mdi mdi-account mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countMahasiswa > 0 ? $countMahasiswa.' Mahasiswa' : 'Data Mahasiswa Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Status Mahasiswa<i
                                        class="mdi mdi-checkbox-multiple-marked mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllStatusMahasiswa > 0 ? $countAllStatusMahasiswa.' Status Mahasiswa' : 'Data Status Mahasiswa Kosong' }}
                                </h2>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <h4>Data Status Mahasiswa</h4>
                                    </div>
                                    <div class="col-12 col-md-8 text-right mt-4 mt-md-0 mt-lg-0">
                                        <a href="{{ url('admin/status-mahasiswa/create')}}"
                                            class="btn-sm btn btn-info btn-tambah">+
                                            Tambah Status Mahasiswa</a>
                                        <a href="{{ url('admin/status-mahasiswa/import-status-mahasiswa')}}"
                                            class="btn-sm btn btn-success btn-tambah mt-3 mt-md-0 mt-lg-0 btn-margin">
                                            <i class="mdi mdi-file-excel btn-icon-prepend"></i>
                                            Import Data Status Mahasiswa</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        {{ Form::open(['url'=>'admin/status-mahasiswa/search','method'=>'GET']) }}
                                        <div class="form-row">
                                            <div class="col-sm-12 col-md-4 mt-1">
                                                {{ Form::select('keywords',$mahasiswa,(request()->get('keywords') != null) ? request()->get('keywords'):null,['class'=>'form-control search','placeholder'=> 'Cari mahasiswa...']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-3 mt-1">
                                                {{ Form::select('tahun_akademik',$tahunAkademik,(request()->get('tahun_akademik') != null) ? request()->get('tahun_akademik'):null,['class'=>'search btn-margin form-control','placeholder'=> '-- Pilih Tahun Akademik --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-2 mt-1">
                                                {{ Form::select('status',['aktif'=>'Aktif','non aktif'=>'Non Aktif','cuti'=>'Cuti','drop out'=>'Drop Out','lulus'=>'Lulus','keluar'=>'Keluar'],(request()->get('status') != null) ? request()->get('status'):null,['class'=>'search btn-margin form-control','placeholder'=> '-- Pilih Status --']) }}
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
                                @if ($countStatusMahasiswa > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> NIM</th>
                                                <th> Nama</th>
                                                <th> Tahun Akademik</th>
                                                <th> Status Aktif</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($statusMahasiswaList as $statusMahasiswa)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($statusMahasiswaList->currentPage() - 1) }}</td>
                                                <td> {{ $statusMahasiswa->nim  }}</td>
                                                <td> {{ ucwords($statusMahasiswa->nama)  }}</td>
                                                <td> {{ $statusMahasiswa->tahun_akademik.' - '.ucwords($statusMahasiswa->semester)  }}</td>
                                                <td>
                                                    @if ($statusMahasiswa->status == 'aktif')
                                                        <label class="badge badge-gradient-info">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @elseif($statusMahasiswa->status == 'lulus')
                                                        <label class="badge badge-gradient-success">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @elseif($statusMahasiswa->status == 'drop out' || $statusMahasiswa->status == 'keluar')
                                                        <label class="badge badge-gradient-danger">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @elseif($statusMahasiswa->status == 'cuti')
                                                        <label class="badge badge-gradient-warning">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @else
                                                        <label class="badge badge-gradient-dark">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $statusMahasiswa->created_at->diffForHumans() }}</td>
                                                <td>{{ $statusMahasiswa->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('admin/status-mahasiswa/'.$statusMahasiswa->id_tahun_akademik.'/'.$statusMahasiswa->nim.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['StatusMahasiswaController@destroy'],'class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('id_tahun_akademik',$statusMahasiswa->id_tahun_akademik)}}
                                                    {{ Form::hidden('nim',$statusMahasiswa->nim)}}
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
                                        {{ $statusMahasiswaList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Status Mahasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Silahkan mengisi data status mahasiswa terlebih dahulu.' }}
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
@endsection