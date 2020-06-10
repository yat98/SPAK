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
                            <i class="mdi mdi-file-document-box"></i>
                        </span> surat permohonan pengambilan material </h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan surat permohonan pengambilan material<i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanSuratMaterial > 0 ? $countAllPengajuanSuratMaterial.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">surat permohonan pengambilan material <i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratMaterial > 0 ? $countAllSuratMaterial.' Surat Permohonan Pengambilan Material' : 'Surat Permohonan Pengambilan Material Kosong' }}
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
                                        <h4>Pengajuan surat permohonan pengambilan material</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPengajuanSuratMaterial > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Nama Kegiatan</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanSuratMaterialList as $pengajuansuratMaterial)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanSuratMaterialList->currentPage() - 1) }}</td>
                                                <td> {{ $pengajuansuratMaterial->mahasiswa->nama }}</td>
                                                <td> {{ $pengajuansuratMaterial->nama_kegiatan }}</td>
                                                <td> 
                                                    @if ($pengajuansuratMaterial->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuansuratMaterial->status) }}
                                                    </label>
                                                    @elseif($pengajuansuratMaterial->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuansuratMaterial->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('pegawai/detail/mahasiswa/'.$pengajuansuratMaterial->nim) }}" class="btn-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-account btn-icon-prepend"></i>
                                                        Detail</a>

                                                    <a href="{{ url('pegawai/surat-permohonan-pengambilan-material/pengajuan/'.$pengajuansuratMaterial->id) }}" class="btn-pengajuan-surat-material-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratMaterial">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>       

                                                    @if ($pengajuansuratMaterial->status == 'diajukan')
                                                    <a href="{{ url('pegawai/surat-permohonan-pengambilan-material/pengajuan/create/'.$pengajuansuratMaterial->id) }}" class="btn btn-sm btn-info">
                                                        <i class="mdi mdi mdi-plus btn-icon-prepend"></i>
                                                            Buat Surat
                                                    </a>

                                                    {{ Form::open(['url'=>'pegawai/surat-permohonan-pengambilan-material/pengajuan/tolak-pengajuan/'.$pengajuansuratMaterial->id,'class'=>'d-inline-block','method'=>'PATCH']) }}
                                                    {{ Form::hidden('keterangan','-',['id'=>'keterangan_surat']) }}
                                                    <button type="submit" class="btn btn-danger btn-sm tolak-surat">
                                                        <i class="mdi mdi mdi-close btn-icon-prepend"></i>
                                                        Tolak
                                                    </button>
                                                    {{ Form::close() }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $pengajuanSuratMaterialList->appends(['page' => $pengajuanSuratMaterialList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            Pengajuan Surat Kosong!
                                        </h4>
                                        <p class="text-muted">
                                           Pengajuan surat permohonan pengambilan material belum ada.
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Surat permohonan pengambilan material</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('pegawai/surat-permohonan-pengambilan-material/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah surat permohonan pengambilan material</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        {{ Form::open(['url'=>'pegawai/surat-permohonan-pengambilan-material/search','method'=>'get']) }}
                                        <div class="form-row">
                                            <div class="col-sm-4 col-md-4 mt-1">
                                                {{ Form::select('nomor_surat',$nomorSurat,(request()->get('nomor_surat') != null) ? request()->get('nomor_surat'):null,['class'=>'form-control search','placeholder'=> 'Cari kode surat...']) }}
                                            </div>
                                            <div class="col-sm-4 col-md-4 mt-1">
                                                {{ Form::select('keywords',$mahasiswa,(request()->get('keywords') != null) ? request()->get('keywords'):null,['class'=>'form-control search','placeholder'=> 'Cari mahasiswa...']) }}
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
                                @if ($countSuratMaterial > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Kegiatan</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratMaterialList as $suratMaterial)
                                            <tr>
                                                @php
                                                    $kode = explode('/',$suratMaterial->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration + $perPage * ($suratMaterialList->currentPage() - 1) }}</td>
                                                <td> {{ 'B/'.$suratMaterial->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratMaterial->created_at->year }}</td>
                                                <td> {{ $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->nama_kegiatan }}</td>
                                                <td> 
                                                    @if($suratMaterial->status == 'menunggu tanda tangan')
                                                         <label class="badge badge-gradient-warning text-dark">
                                                            {{ ucwords($suratMaterial->status) }}
                                                        </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratMaterial->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('pegawai/surat-permohonan-pengambilan-material/'.$suratMaterial->id_pengajuan) }}" class="btn-surat-material-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratMaterial">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
                                                    @if($suratMaterial->status == 'selesai')
                                                    <a href="{{ url('pegawai/surat-permohonan-pengambilan-material/'.$suratMaterial->id_pengajuan.'/cetak') }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    @endif
                                                    <a href="{{ url('pegawai/surat-permohonan-pengambilan-material/'.$suratMaterial->id_pengajuan.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['SuratPermohonanPengambilanMaterialController@destroy',$suratMaterial->id_pengajuan],'class'=>'d-inline-block']) }}
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
                                        {{ $suratMaterialList->appends(['page_pengajuan' => $pengajuanSuratMaterialList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat permohonan pengambilan material terlebih dahulu.' }}
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
            <div class="modal-body" id='surat-keterangan-aktif-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="suratMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-material-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection