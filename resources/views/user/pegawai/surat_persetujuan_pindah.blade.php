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
                        </span> Surat Persetujuan Pindah </h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Persetujuan Pindah<i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanSuratPersetujuanPindah > 0 ? $countAllPengajuanSuratPersetujuanPindah.' Pengajuan Surat Persetujuan Pindah' : 'Pengajuan Surat Persetujuan Pindah Kosong' }}
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
                                <h4 class="font-weight-normal mb-3">Surat Persetujuan Pindah <i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratPersetujuanPindah > 0 ? $countAllSuratPersetujuanPindah.' Surat Persetujuan Pindah' : 'Surat Persetujuan Pindah Kosong' }}
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
                                        <h4>Pengajuan Surat Persetujuan Pindah</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPengajuanSuratPersetujuanPindah > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanSuratPersetujuanList as $pengajuanSuratPersetujuan)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanSuratPersetujuanList->currentPage() - 1) }}</td>
                                                <td> {{ $pengajuanSuratPersetujuan->mahasiswa->nama }}</td>
                                                <td> 
                                                    @if ($pengajuanSuratPersetujuan->status == 'diajukan' || $pengajuanSuratPersetujuan->status == 'menunggu tanda tangan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuanSuratPersetujuan->status) }}
                                                    </label>
                                                    @elseif($pengajuanSuratPersetujuan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuanSuratPersetujuan->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $pengajuanSuratPersetujuan->keterangan }}</td>
                                                <td>
                                                    <a href="{{ url('pegawai/detail/mahasiswa/'.$pengajuanSuratPersetujuan->nim) }}" class="btn-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-account btn-icon-prepend"></i>
                                                        Detail</a>
                                                     <a href="{{ url('pegawai/surat-persetujuan-pindah/pengajuan/'.$pengajuanSuratPersetujuan->id) }}" class="btn-pengajuan-pindah btn btn-outline-info btn-sm" data-toggle="modal" data-target="#persetujuanDetail">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail Pengajuan</a>
                                                        
                                                    @if ($pengajuanSuratPersetujuan->status == 'diajukan')
                                                    <a href="{{ url('pegawai/surat-persetujuan-pindah/pengajuan/'.$pengajuanSuratPersetujuan->id.'/create') }}" class="btn btn-sm btn-info">
                                                        <i class="mdi mdi mdi-plus btn-icon-prepend"></i>
                                                        Buat Surat
                                                    </a>

                                                    {{ Form::open(['url'=>'pegawai/surat-persetujuan-pindah/pengajuan/tolak-pengajuan/'.$pengajuanSuratPersetujuan->id,'class'=>'d-inline-block','method'=>'PATCH']) }}
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
                                        {{ $pengajuanSuratPersetujuanList->appends(['page' => $suratPersetujuanPindahList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Persetujuan Pindah Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Belum ada pengajuan surat persetujuan pindah.' }}
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
                                        <h4>Data Surat Persetujuan Pindah</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('pegawai/surat-persetujuan-pindah/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Surat Persetujuan Pindah</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        {{ Form::open(['url'=>'pegawai/surat-persetujuan-pindah/search','method'=>'get']) }}
                                        <div class="form-row">
                                            <div class="col-sm-4 col-md-4 mt-1">
                                                {{ Form::select('keywords',$nomorSurat,(request()->get('keywords') != null) ? request()->get('keywords'):null,['class'=>'form-control search','placeholder'=> 'Cari kode surat...']) }}
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
                                @if ($countSuratPersetujuanPindah > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratPersetujuanPindahList as $suratPersetujuanPindah)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($suratPersetujuanPindahList->currentPage() - 1) }}</td>
                                                <td> {{ 'B/'.$suratPersetujuanPindah->nomor_surat.'/'.$suratPersetujuanPindah->kodeSurat->kode_surat.'/'.$suratPersetujuanPindah->created_at->year }}</td>
                                                <td> {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nama }}</td>
                                                <td> 
                                                    @if($suratPersetujuanPindah->status == 'menunggu tanda tangan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($suratPersetujuanPindah->status) }}
                                                    </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratPersetujuanPindah->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('pegawai/surat-persetujuan-pindah/'.$suratPersetujuanPindah->id_pengajuan_persetujuan_pindah) }}" class="btn-surat-pindah-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#pindahDetail">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
                                                    @if($suratPersetujuanPindah->status == 'selesai')
                                                    <a href="{{ url('pegawai/surat-persetujuan-pindah/'.$suratPersetujuanPindah->id_pengajuan_persetujuan_pindah.'/cetak') }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>  
                                                    @endif
                                                    <a href="{{ url('pegawai/surat-persetujuan-pindah/'.$suratPersetujuanPindah->id_pengajuan_persetujuan_pindah.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['SuratPersetujuanPindahController@destroy',$suratPersetujuanPindah->id_pengajuan_persetujuan_pindah],'class'=>'d-inline-block']) }}
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
                                        {{ $suratPersetujuanPindahList->appends(['page_pengajuan' => $pengajuanSuratPersetujuanList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Persetujuan Pindah Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat persetujuan pindah terlebih dahulu.' }}
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
<div class="modal fade" id="persetujuanDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='persetujuan-pindah-detail-content'>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pindahDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-pindah-detail-content'>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection