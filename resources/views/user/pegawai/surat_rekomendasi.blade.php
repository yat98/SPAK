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
                        </span> Surat Rekomendasi</h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Rekomendasi <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratRekomendasi > 0 ? $countAllSuratRekomendasi.' Surat Rekomendasi' : 'Surat Rekomendasi Kosong' }}
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
                                        <h4>Data Surat Rekomendasi</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('pegawai/surat-rekomendasi/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Surat Rekomendasi</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-12">
                                        {{ Form::open(['url'=>'pegawai/surat-rekomendasi/search','method'=>'GET']) }}
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
                                @if ($countSuratRekomendasi > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama kegiatan</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratRekomendasiList as $suratRekomendasi)
                                             @php
                                                $kode = explode('/',$suratRekomendasi->kodeSurat->kode_surat);
                                            @endphp
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($suratRekomendasiList->currentPage() - 1)  }}</td>
                                                @if($suratRekomendasi->user->jabatan == 'wd3')
                                                    <td> {{ $suratRekomendasi->nomor_surat.'/'.$kode[0].'.3/'.$kode[1].'/'.$suratRekomendasi->created_at->format('Y') }}</td>
                                                @else
                                                    <td> {{ $suratRekomendasi->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratRekomendasi->created_at->format('Y') }}</td>
                                                @endif
                                                <td> {{ $suratRekomendasi->nama_kegiatan }}</td>
                                                <td> 
                                                @if($suratRekomendasi->status == 'menunggu tanda tangan')
                                                <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratRekomendasi->status) }}</td></label>
                                                @else
                                                <label class="badge badge-gradient-info">{{ ucwords($suratRekomendasi->status) }}</td></label>
                                                @endif
                                                <td> {{ $suratRekomendasi->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratRekomendasi->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('pegawai/surat-rekomendasi/'.$suratRekomendasi->id) }}" class="btn btn-outline-info btn-sm btn-surat-rekomendasi-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                    @if($suratRekomendasi->status == 'selesai')
                                                    <a href="{{ url('pegawai/surat-rekomendasi/'.$suratRekomendasi->id.'/cetak') }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    @endif
                                                    <a href="{{ url('pegawai/surat-rekomendasi/'.$suratRekomendasi->id.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['SuratRekomendasiController@destroy',$suratRekomendasi->id],'class'=>'d-inline-block']) }}
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
                                        {{ $suratRekomendasiList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Surat Rekomendasi Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi surat rekomendasi terlebih dahulu.' }}
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
            <div class="modal-body" id='surat-rekomendasi-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
@endsection