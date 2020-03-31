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
                                        class="mdi mdi-account-text mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllMahasiswa > 0 ? $countAllMahasiswa.' Mahasiswa' : 'Data Mahasiswa Kosong' }}
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
                                    <div class="col-12 col-md-4">
                                        <h4>Data Mahasiswa</h4>
                                    </div>
                                    <div class="col-12 col-md-8 text-right mt-4 mt-md-0 mt-lg-0">
                                        <a href="{{ url('admin/mahasiswa/create')}}"
                                            class="btn-sm btn btn-info btn-tambah">+
                                            Tambah Mahasiswa</a>
                                        <a href="{{ url('admin/mahasiswa/import-mahasiswa')}}"
                                            class="btn-sm btn btn-success btn-tambah mt-3 mt-md-0 mt-lg-0 btn-margin">
                                            <i class="mdi mdi-file-excel btn-icon-prepend"></i>
                                            Import Data Mahasiswa</a>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        {{ Form::open(['url'=>'admin/mahasiswa/search','method'=>'GET']) }}
                                        <div class="form-row">
                                            <div class="col-sm-12 col-md-6">
                                                {{ Form::text('keyword',(request()->get('keyword') != null) ? request()->get('keyword'):null,['placeholder'=>'Cari Nama Mahasiswa...','class'=>'form-control']) }}
                                            </div>
                                            <div class="col-sm-12 col-md">
                                                {{ Form::select('jurusan',$jurusanList,(request()->get('jurusan') != null) ? request()->get('jurusan'):null,['class'=>'btn-margin form-control','placeholder'=> '-- Pilih Jurusan --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md">
                                                {{ Form::select('prodi',$prodiList,(request()->get('prodi') != null) ? request()->get('prodi'):null,['class'=>'form-control','placeholder'=> '-- Pilih Program Studi --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md mt-xs-2 mt-md-0 btn-margin ">
                                                {{ Form::select('angkatan',$angkatan,(request()->get('angkatan')!= null) ? request()->get('angkatan'):null,['class'=>'form-control','placeholder'=> '-- Pilih Angkatan --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md">
                                                {{ Form::submit('Cari',['class'=>'btn btn-info btn-tambah']) }}
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
                                                <th> Jurusan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mahasiswaList as $mahasiswa)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($mahasiswaList->currentPage() - 1)  }}
                                                </td>
                                                <td> {{ $mahasiswa->nim  }}</td>
                                                <td> {{ ucwords($mahasiswa->nama)  }}</td>
                                                <td>{{ $mahasiswa->prodi->jurusan->nama_jurusan }}</td>
                                                <td>{{ $mahasiswa->prodi->strata}} -
                                                    {{ $mahasiswa->prodi->nama_prodi }}
                                                </td>
                                                <td>{{ $mahasiswa->created_at->diffForHumans() }}</td>
                                                <td>{{ $mahasiswa->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('admin/mahasiswa/'.$mahasiswa->nim) }}"
                                                        class="btn btn-outline-info btn-sm btn-detail"
                                                        data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-account btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                    <a href="{{ url('admin/mahasiswa/'.$mahasiswa->nim.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['MahasiswaController@destroy',$mahasiswa->nim],'class'=>'d-inline-block']) }}
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
                                        {{ $mahasiswaList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data Mahasiswa kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Silahkan mengisi data mahasiswa terlebih dahulu.' }}
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
                <h5 class="modal-title" id="exampleModalLabel">Detail Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='mahasiswa-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection