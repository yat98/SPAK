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
                        </span> Surat Pengantar Beasiswa</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Pengantar Beasiswa <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratBeasiswa > 0 ? $countAllSuratBeasiswa.' Surat Pengantar Beasiswa' : 'Surat Pengantar Beasiswa Kosong' }}
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
                                        <h4>Surat Pengantar Beasiswa</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('pegawai/surat-pengantar-beasiswa/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Surat Pengantar Beasiswa</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-12">
                                        {{ Form::open(['url'=>'pegawai/surat-pengantar-beasiswa/search','method'=>'GET']) }}
                                        <div class="form-row">
                                            <div class="col-sm-12 col-md-6 mt-1">
                                                {{ Form::select('keywords',$nomorSurat,(request()->get('keywords') != null) ? request()->get('keywords'):null,['class'=>'form-control search','placeholder'=> '-- Pilih Nomor Surat --']) }}
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
                                @if ($countSuratBeasiswa > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratBeasiswaList as $suratBeasiswa)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($suratBeasiswaList->currentPage() - 1)  }}</td>
                                                <td> {{ 'B/'.$suratBeasiswa->nomor_surat.'/'.$suratBeasiswa->kodeSurat->kode_surat.'/'.$suratBeasiswa->created_at->format('Y') }}</td>
                                                <td> 
                                                @if($suratBeasiswa->status == 'menunggu tanda tangan')
                                                <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratBeasiswa->status) }}</td></label>
                                                @else
                                                <label class="badge badge-gradient-info">{{ ucwords($suratBeasiswa->status) }}</td></label>
                                                @endif
                                                <td>
                                                    <a href="{{ url('pegawai/surat-pengantar-beasiswa/'.$suratBeasiswa->id) }}" class="btn btn-outline-info btn-sm btn-surat-pengantar-beasiswa-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                    @if($suratBeasiswa->status == 'selesai')
                                                    <a href="{{ url('pegawai/surat-pengantar-beasiswa/'.$suratBeasiswa->id.'/cetak') }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    @endif
                                                    <a href="{{ url('pegawai/surat-pengantar-beasiswa/'.$suratBeasiswa->id.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['SuratPengantarBeasiswaController@destroy',$suratBeasiswa->id],'class'=>'d-inline-block']) }}
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
                                        {{ $suratBeasiswaList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat pengantar beasiswa terlebih dahulu.' }}
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-pengantar-beasiswa-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection