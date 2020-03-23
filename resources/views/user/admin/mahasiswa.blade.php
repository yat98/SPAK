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
                            <i class="mdi mdi-bank"></i>
                        </span> Mahasiswa</h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Jurusan <i
                                        class="mdi mdi-bank mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countJurusan > 0 ? $countJurusan.' Jurusan' : 'Data Jurusan Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Program Studi <i
                                        class="mdi mdi-book-multiple mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countProdi > 0 ? $countProdi.' Program Studi' : 'Data Prodi Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Mahasiswa<i
                                        class="mdi mdi-calendar-text mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countMahasiswa > 0 ? $countMahasiswa.' Mahasiswa' : 'Data Mahasiswa Kosong' }}
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
                                        <h4>Data Mahasiswa</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right mt-4 mt-md-0 mt-lg-0">
                                        <a href="{{ url('admin/mahasiswa/create')}}"
                                            class="btn-sm btn btn-info btn-tambah">+
                                            Tambah Mahasiswa</a>
                                        <a href="{{ url('admin/mahasiswa/create')}}"
                                            class="btn-sm btn btn-success btn-tambah mt-3 mt-md-0 mt-lg-0">+
                                            Export Data Mahasiswa</a>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6">
                                        {{ Form::open(['url'=>'']) }}
                                        <div class="form-group">
                                            <div class="input-group">
                                                {{ Form::text('keyword',null,['placeholder'=>'Cari Mahasiswa...','class'=>'form-control']) }}
                                                <div class="input-group-append">
                                                    <button class="btn btn-sm btn-success" type="submit">
                                                        <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                        Cari
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                @if ($countMahasiswa > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nim</th>
                                                <th> Nama</th>
                                                <th> Angkatan</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mahasiswaList as $mahasiswa)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $mahasiswa->nim  }}</td>
                                                <td> {{ $mahasiswa->nama  }}</td>
                                                <td>{{ $mahasiswa->angkatan}}</td>
                                                <td>
                                                    <a href="{{ url('admin/mahasiswa/'.$mahasiswa->nim.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['MahasiswaController@destroy',$mahasiswa->nim],'class'=>'d-inline-block sweet-delete']) }}
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
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">Data Mahasiswa kosong!</h4>
                                        <p class="text-muted">Silahkan mengisi data mahasiswa terlebih dahulu.
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