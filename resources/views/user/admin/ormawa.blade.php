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
                            <i class="mdi mdi-check-decagram"></i>
                        </span> Ormawa </h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-bloody-mary text-white card-img-holder">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Ormawa <i
                                        class="mdi mdi-check-decagram mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllOrmawa > 0 ? $countAllOrmawa.' Ormawa' : 'Data Ormawa Kosong' }}
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
                                        <h4>Data Ormawa</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('admin/ormawa/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Ormawa</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-10">
                                        {{ Form::open(['url'=>'admin/ormawa/search','method'=>'get']) }}
                                        <div class="form-row">
                                            <div class="col-sm-12 col-md-4">
                                                {{ Form::text('keyword',(request()->get('keyword') != null) ? request()->get('keyword'):null,['placeholder'=>'Cari nama ormawa...','class'=>'form-control']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                {{ Form::select('jurusan',$jurusan,(request()->get('jurusan') != null) ? request()->get('jurusan'):null,['class'=>'btn-margin form-control','placeholder'=> '-- Pilih Jurusan --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <button class="btn btn-success btn-tambah" type="submit">
                                                    <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                @if ($countOrmawa > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama</th>
                                                <th> Nama Jurusan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ormawaList as $ormawa)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($ormawaList->currentPage() - 1) }}</td>
                                                <td> {{ $ormawa->nama  }}</td>
                                                <td> {{ $ormawa->jurusan->nama_jurusan  }}</td>
                                                <td> {{ $ormawa->created_at->diffForHumans() }}</td>
                                                <td> {{ $ormawa->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('admin/ormawa/'.$ormawa->id.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['OrmawaController@destroy',$ormawa->id],'class'=>'d-inline-block']) }}
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
                                        {{ $ormawaList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Ormawa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data ormawa terlebih dahulu.' }}
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