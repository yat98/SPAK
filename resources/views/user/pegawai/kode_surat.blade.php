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
                            <i class="mdi mdi-format-list-numbered"></i>
                        </span> Kode Surat </h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Kode Surat <i
                                        class="mdi mdi-format-list-numbered mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllKodeSurat > 0 ? $countAllKodeSurat.' Kode Surat' : 'Kode Surat Kosong' }}
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
                                        <h4>Kode Surat</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('pegawai/kode-surat/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Kode Surat</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        {{ Form::open(['url'=>'pegawai/kode-surat/search','method'=>'get']) }}
                                        <div class="form-row">                                              
                                            <div class="col-sm-12 col-md-6">
                                                {{ Form::text('keyword',(request()->get('keyword') != null) ? request()->get('keyword'):null,['placeholder'=>'Cari Kode Surat...','class'=>'form-control']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-3">
                                                {{ Form::select('jenis_surat',$jenisSurat,(request()->get('jenis_surat')!= null) ? request()->get('jenis_surat'):null,['class'=>'form-control','placeholder'=> '-- Pilih Jenis Surat --']) }}
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
                                @if ($countKodeSurat > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Kode Surat</th>
                                                <th> Jenis Surat</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kodeSuratList as $kodeSurat)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($kodeSuratList->currentPage() - 1) }}</td>
                                                <td> {{ $kodeSurat->kode_surat }}</td>
                                                <td> {{ ucwords($kodeSurat->jenis_surat) }}</td>
                                                <td> 
                                                    @if ($kodeSurat->status_aktif == 'aktif')
                                                    <label
                                                        class="badge badge-gradient-info">{{ ucwords($kodeSurat->status_aktif) }}</label>
                                                    @else
                                                    <label
                                                        class="badge badge-gradient-dark">{{ ucwords($kodeSurat->status_aktif) }}</label>
                                                    @endif
                                                </td>
                                                <td> {{ $kodeSurat->created_at->diffForHumans() }}</td>
                                                <td> {{ $kodeSurat->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('pegawai/kode-surat/'.$kodeSurat->id.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['KodeSuratController@destroy',$kodeSurat->id],'class'=>'d-inline-block']) }}
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
                                        {{ $kodeSuratList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Kode Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data kode surat terlebih dahulu.' }}
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