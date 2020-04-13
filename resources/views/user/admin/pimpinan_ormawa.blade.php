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
                            <i class="mdi mdi mdi-account-multiple"></i>
                        </span> Pimpinan Ormawa </h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-deep-space-sea text-white card-img-holder">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Pimpinan Ormawa <i
                                        class="mdi mdi-account-multiple mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPimpinanOrmawa > 0 ? $countAllPimpinanOrmawa.' Pimpinan Ormawa' : 'Data Pimpinan Ormawa Kosong' }}
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
                                        <h4>Data Pimpinan Ormawa</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('admin/pimpinan-ormawa/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Pimpinan Ormawa</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-12">
                                        {{ Form::open(['url'=>'admin/pimpinan-ormawa/search','method'=>'get']) }}
                                        <div class="form-row">
                                            <div class="col-sm-12 col-md-3 mt-1">
                                                {{ Form::select('keywords',$mahasiswa,(request()->get('keywords') != null) ? request()->get('keywords'):null,['class'=>'form-control search','placeholder'=> 'Cari mahasiswa...']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-2 mt-1">
                                                {{ Form::select('jurusan',$jurusan,(request()->get('jurusan') != null) ? request()->get('jurusan'):null,['class'=>'btn-margin form-control search','placeholder'=> '-- Pilih Jurusan --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-3 mt-1">
                                                {{ Form::select('ormawa',$ormawaList,(request()->get('ormawa') != null) ? request()->get('ormawa'):null,['class'=>'btn-margin form-control search','placeholder'=> '-- Pilih Ormawa --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-2 mt-1">
                                                {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],(request()->get('status_aktif') != null) ? request()->get('status_aktif'):null,['class'=>'form-control search','placeholder'=> '-- Pilih Status Aktif --']) }}
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
                                @if ($countPimpinanOrmawa > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> NIM</th>
                                                <th> Nama</th>
                                                <th> Jurusan</th>
                                                <th> Jabatan</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pimpinanOrmawaList as $pimpinanOrmawa)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pimpinanOrmawaList->currentPage() - 1) }}</td>
                                                <td> {{ $pimpinanOrmawa->nim  }}</td>
                                                <td> {{ $pimpinanOrmawa->mahasiswa->nama  }}</td>
                                                <td> {{ $pimpinanOrmawa->mahasiswa->prodi->jurusan->nama_jurusan }}</td>
                                                <td> {{ ucwords($pimpinanOrmawa->jabatan)  }}</td>
                                                <td>
                                                    @if ($pimpinanOrmawa->status_aktif == 'aktif')
                                                    <label
                                                        class="badge badge-gradient-info">{{ ucwords($pimpinanOrmawa->status_aktif) }}</label>
                                                    @else
                                                    <label
                                                        class="badge badge-gradient-dark">{{ ucwords($pimpinanOrmawa->status_aktif) }}</label>
                                                    @endif
                                                </td>
                                                <td> {{ $pimpinanOrmawa->created_at->diffForHumans() }}</td>
                                                <td> {{ $pimpinanOrmawa->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('admin/pimpinan-ormawa/'.$pimpinanOrmawa->nim.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['PimpinanOrmawaController@destroy',$pimpinanOrmawa->nim],'class'=>'d-inline-block']) }}
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
                                        {{ $pimpinanOrmawaList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Pimpinan Ormawa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data pimpinan ormawa terlebih dahulu.' }}
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