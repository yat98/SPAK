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
                        </span> Surat Tugas</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tanda Tangan Surat Tugas <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanSuratTugas > 0 ? $countAllPengajuanSuratTugas.' Tanda Tangan Surat Tugas' : 'Tanda Tangan Surat Rekomendasi Kosong' }}
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
                                <h4 class="font-weight-normal mb-3">Surat Tugas <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratTugas > 0 ? $countAllSuratTugas.' Surat Tugas' : 'Surat Tugas Kosong' }}
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
                                        <h4>Tanda Tangan Surat Tugas</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPengajuanSuratTugas > 0)
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
                                            @foreach ($pengajuanSuratTugasList as $suratTugas)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanSuratTugasList->currentPage() - 1)  }}</td>
                                                <td> {{ 'B/'.$suratTugas->nomor_surat.'/'.$suratTugas->kodeSurat->kode_surat.'/'.$suratTugas->created_at->format('Y') }}</td>
                                                <td> {{ $suratTugas->nama_kegiatan }}</td>
                                                <td> 
                                                @if($suratTugas->status == 'menunggu tanda tangan')
                                                <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratTugas->status) }}</td></label>
                                                @else
                                                <label class="badge badge-gradient-info">{{ ucwords($suratTugas->status) }}</td></label>
                                                @endif
                                                <td> {{ $suratTugas->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratTugas->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-tugas/'.$suratTugas->id) }}" class="btn btn-outline-info btn-sm btn-surat-tugas-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                     @if ($suratTugas->status == 'menunggu tanda tangan' && $suratTugas->nip == Session::get('nip'))
                                                    {{ Form::open(['url'=>'pimpinan/surat-tugas/pengajuan/tanda-tangan','class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('id',$suratTugas->id)}}
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
                                        {{ $pengajuanSuratTugasList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Tanda Tangan Surat Tugas Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Tanda tangan surat tugas belum ada.' }}
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
                                        <h4>Surat Tugas</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-12">
                                        {{ Form::open(['url'=>'pimpinan/surat-tugas/search','method'=>'GET']) }}
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
                                @if ($countSuratTugas > 0)
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
                                            @foreach ($suratTugasList as $suratTugas)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($suratTugasList->currentPage() - 1)  }}</td>
                                                <td> {{ 'B/'.$suratTugas->nomor_surat.'/'.$suratTugas->kodeSurat->kode_surat.'/'.$suratTugas->created_at->format('Y') }}</td>
                                                <td> {{ $suratTugas->nama_kegiatan }}</td>
                                                <td>                                                
                                                <label class="badge badge-gradient-info">{{ ucwords($suratTugas->status) }}</td></label>
                                                <td> {{ $suratTugas->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratTugas->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-tugas/'.$suratTugas->id) }}" class="btn btn-outline-info btn-sm btn-surat-tugas-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $suratTugasList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Surat Tugas Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi surat tugas terlebih dahulu.' }}
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
            <div class="modal-body" id='surat-tugas-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
@endsection