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
                        </span> Surat Keterangan Kelakuan Baik </h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Keterangan Kelakuan Baik<i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanSuratKeterangan > 0 ? $countAllPengajuanSuratKeterangan.' Pengajuan Surat Keterangan Kelakuan Baik' : 'Pengajuan Surat Keterangan Kelakuan Baik Kosong' }}
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
                                <h4 class="font-weight-normal mb-3">Surat Keterangan Kelakuan Baik <i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratKeterangan > 0 ? $countAllSuratKeterangan.' Surat Keterangan Kelakuan Baik' : 'Surat Keterangan Kelakuan Baik Kosong' }}
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
                                        <h4>Pengajuan Surat Keterangan Kelakuan Baik</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPengajuanSuratKeterangan > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Semester</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanSuratKeteranganList as $pengajuanSuratKeterangan)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanSuratKeteranganList->currentPage() - 1) }}</td>
                                                <td> {{ $pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                                                <td> {{ $pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($pengajuanSuratKeterangan->tahunAkademik->semester) }}</td>
                                                <td> 
                                                    @if ($pengajuanSuratKeterangan->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuanSuratKeterangan->status) }}
                                                    </label>
                                                    @elseif($pengajuanSuratKeterangan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuanSuratKeterangan->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('pegawai/detail/mahasiswa/'.$pengajuanSuratKeterangan->nim) }}" class="btn-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-account btn-icon-prepend"></i>
                                                        Detail</a>

                                                    @if ($pengajuanSuratKeterangan->status == 'diajukan')
                                                    {{ Form::open(['url'=>'pegawai/surat-keterangan-kelakuan-baik/pengajuan/tanda-tangan','class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('id',$pengajuanSuratKeterangan->id)}}
                                                    <button type="submit" class="btn btn-info btn-sm simpan-tanda-tangan">
                                                        <i class="mdi mdi mdi-border-color btn-icon-prepend"></i>
                                                        Tanda Tangan
                                                    </button>
                                                    {{ Form::close() }}

                                                    {{ Form::open(['url'=>'pegawai/surat-keterangan-kelakuan-baik/pengajuan/tolak-pengajuan/'.$pengajuanSuratKeterangan->id,'class'=>'d-inline-block','method'=>'PATCH']) }}
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
                                        {{ $pengajuanSuratKeteranganList->appends(['page' => $pengajuanSuratKeteranganList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Keterangan Kelakuan Baik Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Belum ada pengajuan surat keterangan kelakuan baik.' }}
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
                                        <h4>Data Surat Keterangan Kelakuan Baik</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('pegawai/surat-keterangan-kelakuan-baik/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Surat Keterangan Kelakuan Baik</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        {{ Form::open(['url'=>'pegawai/surat-keterangan-kelakuan-baik/search','method'=>'get']) }}
                                        <div class="form-row">
                                            <div class="col-sm-4 col-md-4 mt-1">
                                                {{ Form::select('nomor_surat',$nomorSurat,(request()->get('nomor_surat') != null) ? request()->get('nomor_surat'):null,['class'=>'form-control search','placeholder'=> 'Cari kode surat...']) }}
                                            </div>
                                            <div class="col-sm-4 col-md-4 mt-1">
                                                {{ Form::select('keywords',$mahasiswa,(request()->get('keywords') != null) ? request()->get('keywords'):null,['class'=>'form-control search','placeholder'=> 'Cari mahasiswa...']) }}
                                            </div>
                                            <div class="col-sm-4 col-md-2 mt-1">
                                                {{ Form::select('tahun_akademik',$tahunAkademik,(request()->get('tahun_akademik') != null) ? request()->get('tahun_akademik'):null,['class'=>'search btn-margin form-control','placeholder'=> '-- Pilih Tahun Akademik --']) }}
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
                                @if ($countSuratKeterangan > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Semester</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratKeteranganList as $suratKeterangan)
                                            <tr>
                                                @php
                                                    $kode = explode('/',$suratKeterangan->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration + $perPage * ($suratKeteranganList->currentPage() - 1) }}</td>
                                                <td> {{ 'B/'.$suratKeterangan->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratKeterangan->created_at->year }}</td>
                                                <td> {{ $suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                                                <td> {{ $suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->semester) }}</td>
                                                <td> 
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratKeterangan->status) }}
                                                    </label>
                                                </td>
                                                <td>
                                                    <a href="{{ url('pegawai/surat-keterangan-kelakuan-baik/'.$suratKeterangan->id_pengajuan_surat_keterangan) }}" class="btn-surat-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
                                                    <a href="{{ url('pegawai/surat-keterangan-kelakuan-baik/'.$suratKeterangan->id_pengajuan_surat_keterangan.'/cetak') }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    <a href="{{ url('pegawai/surat-keterangan-kelakuan-baik/'.$suratKeterangan->id_pengajuan_surat_keterangan.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['SuratKeteranganKelakuanBaikController@destroy',$suratKeterangan->id_pengajuan_surat_keterangan],'class'=>'d-inline-block']) }}
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
                                        {{ $suratKeteranganList->appends(['page_pengajuan' => $pengajuanSuratKeteranganList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Keterangan Kelakuan Baik Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat keterangan kelakuan baik terlebih dahulu.' }}
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
@endsection