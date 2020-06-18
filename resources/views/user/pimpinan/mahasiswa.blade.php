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
                            <i class="mdi mdi-account"></i>
                        </span> Mahasiswa</h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Mahasiswa<i
                                        class="mdi mdi-account mdi-24px float-right"></i>
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
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        {{ Form::open(['url'=>'pimpinan/mahasiswa/search','method'=>'GET']) }}
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
                                                <button class="btn btn-success btn-tambah" type="submit">
                                                    <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                    Cari
                                                </button>
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
                                                <th> NIM</th>
                                                <th> Nama</th>
                                                <th> Jurusan</th>
                                                <th> Angkatan</th>
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
                                                <td> {{ $mahasiswa->angkatan  }}</td>
                                                <td>
                                                    <a href="{{ url('pimpinan/mahasiswa/'.$mahasiswa->nim) }}"
                                                        class="btn btn-outline-info btn-sm">
                                                        <i class="mdi mdi-account btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Mahasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Silahkan mengisi data mahasiswa terlebih dahulu.' }}
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