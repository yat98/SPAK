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
                            <i class="mdi mdi-bell-outline"></i>
                        </span> Notifikasi </h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <h4>Notifikasi</h4>
                                    </div>
                                    <div class="col-12 col-md-8 text-right mt-4 mt-md-0 mt-lg-0">
                                        {{ Form::open(['url'=>'mahasiswa/notifikasi/allread','class'=>'d-inline-block btn-tambah']) }}
                                        <button type="submit" class="btn-sm btn btn-tambah mt-3 mt-md-0 mt-lg-0 btn-margin btn-outline-dark">
                                            <i class="mdi mdi-check btn-icon-prepend"></i>
                                            Tandai Semua Dilihat</button>
                                        {{ Form::close() }}
                                        
                                        {{ Form::open(['url'=>'mahasiswa/notifikasi/alldelete','class'=>'d-inline-block btn-tambah']) }}
                                        <button type="submit" class="sweet-delete btn-sm btn btn-danger btn-tambah mt-3 mt-md-0 mt-lg-0 btn-margin">
                                            <i class="mdi mdi-delete-forever btn-icon-prepend"></i>
                                            Hapus Semua Notifikasi</button>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllNotifikasi > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Judul</th>
                                                <th> Isi</th>
                                                <th> Status</th>
                                                <th> Tanggal Notifikasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($notifikasiList as $ntfksi)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($notifikasiList->currentPage() - 1) }}</td>
                                                <td> <a href="{{ url($ntfksi->link_notifikasi) }}" class="text-dark">{{ $ntfksi->judul_notifikasi  }}</a></td>
                                                <td> {{ $ntfksi->isi_notifikasi  }}</td>
                                                <td>
                                                    @if ($ntfksi->status == 'dilihat')
                                                    <label class="badge badge-gradient-success">{{ ucwords($ntfksi->status) }}</label>
                                                    @else
                                                    <label class="badge badge-gradient-info">{{ ucwords($ntfksi->status) }}</label>
                                                    @endif 
                                                </td>
                                                <td> {{ $ntfksi->created_at->isoFormat('D MMMM Y H:mm:ss') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $notifikasiList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_notifikasi.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-4">
                                            Notifikasi Kosong!
                                        </h4>
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