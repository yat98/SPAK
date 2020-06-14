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
                        </span> Surat Permohonan Pengambilan Data Awal</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tanda Tangan Surat Permohonan Pengambilan Data Awal <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanSuratDataAwal > 0 ? $countAllPengajuanSuratDataAwal.' Tanda Tangan Surat' : 'Tanda Tangan Surat Kosong' }}
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
                                <h4 class="font-weight-normal mb-3">Surat Permohonan Pengambilan Data Awal <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratDataAwal > 0 ? $countAllSuratDataAwal.' Surat Permohonan Pengambilan Data Awal' : 'Surat Permohonan Pengambilan Data Awal Kosong' }}
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
                                        <h4>Tanda Tangan Surat Permohonan Pengambilan Data Awal</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPengajuanSuratDataAwal > 0)
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
                                            @foreach ($pengajuanSuratDataAwalList as $suratDataAwal)
                                            <tr>
                                                @php
                                                   $kodeSurat = explode('/',$suratDataAwal->suratPermohonanPengambilanDataAwal->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanSuratDataAwalList->currentPage() - 1)  }}</td>
                                                <td> {{ 'B/'.$suratDataAwal->suratPermohonanPengambilanDataAwal->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratDataAwal->suratPermohonanPengambilanDataAwal->created_at->format('Y') }}</td>
                                                <td> {{ $suratDataAwal->mahasiswa->nama }}</td>
                                                <td> 
                                                @if($suratDataAwal->status == 'menunggu tanda tangan')
                                                <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratDataAwal->status) }}</td></label>
                                                @else
                                                <label class="badge badge-gradient-info">{{ ucwords($suratDataAwal->status) }}</td></label>
                                                @endif
                                                <td>
                                                    <a href="{{ url('pimpinan/detail/mahasiswa/'.$suratDataAwal->nim) }}" class="btn-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-account btn-icon-prepend"></i>
                                                        Detail</a>
                                                    <a href="{{ url('pimpinan/surat-permohonan-pengambilan-data-awal/'.$suratDataAwal->id) }}" class="btn btn-outline-info btn-sm btn-surat-data-awal-detail" data-toggle="modal" data-target="#suratDataAwal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                     @if ($suratDataAwal->status == 'menunggu tanda tangan' && $suratDataAwal->suratPermohonanPengambilanDataAwal->nip == Session::get('nip'))
                                                    {{ Form::open(['url'=>'pimpinan/surat-permohonan-pengambilan-data-awal/pengajuan/tanda-tangan','class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('id',$suratDataAwal->id)}}
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
                                        {{ $pengajuanSuratDataAwalList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                           Tanda Tangan Surat Kosong!
                                        </h4>
                                        <p class="text-muted">
                                            Tanda tangan surat permohonan pengambilan data awal belum ada.
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
                                        <h4>Surat Permohonan Pengambilan Data Awal</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-12">
                                        {{ Form::open(['url'=>'pimpinan/surat-permohonan-pengambilan-data-awal/search','method'=>'GET']) }}
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
                                @if ($countsuratDataAwal > 0)
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
                                            @foreach ($suratDataAwalList as $suratDataAwal)
                                            <tr>
                                                @php
                                                    $kodeSurat = explode('/',$suratDataAwal->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration + $perPage * ($suratDataAwalList->currentPage() - 1)  }}</td>
                                                <td> {{ 'B/'.$suratDataAwal->nomor_surat.'/'.$kodeSurat[0].'.1/'.$kodeSurat[1].'/'.$suratDataAwal->created_at->format('Y') }}</td>
                                                <td> {{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->nama }}</td>
                                                <td>                                                
                                                <label class="badge badge-gradient-info">{{ ucwords($suratDataAwal->status) }}</td></label>
                                                <td>
                                                     <a href="{{ url('pimpinan/surat-permohonan-pengambilan-data-awal/'.$suratDataAwal->id_pengajuan) }}" class="btn-surat-data-awal-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratDataAwal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $suratDataAwalList->links() }}
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat permohonan pengambilan data awal terlebih dahulu.' }}
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
<div class="modal fade" id="suratDataAwal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-data-awal-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection