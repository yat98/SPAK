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
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tanda Tangan Surat Pengantar Beasiswa <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanBeasiswa > 0 ? $countAllPengajuanBeasiswa.' Tanda Tangan Surat Pengantar Beasiswa' : 'Tanda Tangan Surat Pengantar Beasiswa Kosong' }}
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
                                        <h4>Tanda Tangan Surat Pengantar Beasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPengajuanBeasiswa > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanBeasiswaList as $pengajuanBeasiswa)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanBeasiswaList->currentPage() - 1)  }}</td>
                                                @if($pengajuanBeasiswa->user->jabatan == 'dekan')
                                                <td> {{ 'B/'.$pengajuanBeasiswa->nomor_surat.'/'.$pengajuanBeasiswa->kodeSurat->kode_surat.'/'.$pengajuanBeasiswa->created_at->format('Y') }}</td>
                                                @else
                                                @php
                                                    $kode = explode('/',$pengajuanBeasiswa->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ 'B/'.$pengajuanBeasiswa->nomor_surat.'/'.$kode[0].'.3/'.$kode[1].'/'.$pengajuanBeasiswa->created_at->format('Y') }}</td>
                                                @endif
                                                <td> 
                                                @if($pengajuanBeasiswa->status == 'menunggu tanda tangan')
                                                <label class="badge badge-gradient-warning text-dark">{{ ucwords($pengajuanBeasiswa->status) }}</td></label>
                                                @else
                                                <label class="badge badge-gradient-info">{{ ucwords($pengajuanBeasiswa->status) }}</td></label>
                                                @endif
                                                <td> {{ $pengajuanBeasiswa->created_at->diffForHumans() }}</td>
                                                <td> {{ $pengajuanBeasiswa->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-pengantar-beasiswa/'.$pengajuanBeasiswa->id) }}" class="btn btn-outline-info btn-sm btn-surat-pengantar-beasiswa-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                     @if ($pengajuanBeasiswa->status == 'menunggu tanda tangan' && $pengajuanBeasiswa->nip == Session::get('nip'))
                                                    {{ Form::open(['url'=>'pimpinan/surat-pengantar-beasiswa/pengajuan/tanda-tangan','class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('id',$pengajuanBeasiswa->id)}}
                                                    <button type="submit" class="btn btn-info btn-sm simpan-tanda-tangan">
                                                        <i class="mdi mdi mdi-border-color btn-icon-prepend"></i>
                                                        Tanda Tangan
                                                    </button>
                                                    {{ Form::close() }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $pengajuanBeasiswaList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Tanda Tangan Surat Pengantar Beasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Tanda tangan surat pengantar beasiswa belum ada.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
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
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-12">
                                        {{ Form::open(['url'=>'pimpinan/surat-pengantar-beasiswa/search','method'=>'GET']) }}
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
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratBeasiswaList as $suratBeasiswa)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($suratBeasiswaList->currentPage() - 1)  }}</td>
                                                 @if($suratBeasiswa->user->jabatan == 'dekan')
                                                <td> {{ 'B/'.$suratBeasiswa->nomor_surat.'/'.$suratBeasiswa->kodeSurat->kode_surat.'/'.$suratBeasiswa->created_at->format('Y') }}</td>
                                                @else
                                                @php
                                                    $kode = explode('/',$suratBeasiswa->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ 'B/'.$suratBeasiswa->nomor_surat.'/'.$kode[0].'.3/'.$kode[1].'/'.$suratBeasiswa->created_at->format('Y') }}</td>
                                                @endif
                                                <td>                                                
                                                <label class="badge badge-gradient-info">{{ ucwords($suratBeasiswa->status) }}</td></label>
                                                <td> {{ $suratBeasiswa->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratBeasiswa->updated_at->diffForHumans() }}</td>
                                                <td>
                                                     <a href="{{ url('pimpinan/surat-pengantar-beasiswa/'.$suratBeasiswa->id) }}" class="btn btn-outline-info btn-sm btn-surat-pengantar-beasiswa-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Surat Pengantar Beasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi surat pengantar beasiswa terlebih dahulu.' }}
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