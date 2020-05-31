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
                            <i class="mdi mdi mdi-border-color"></i>
                        </span> Tanda Tangan </h3>
                </div>
                <div class="row">
                    <div class="col-md-7 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <div class="clearfix">
                            <h4 class="card-title float-left">Tanda Tangan</h4>
                          </div>
                          <hr class="mb-4">
                          <div class="row">
                            <div class="col text-center">
                                @if(!empty($user->tanda_tangan))
                                <div class="border-danger p-3 mt-5">
                                    <img src="{{ $user->tanda_tangan }}">

                                </div>
                                @else
                                <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                <h4 class="display-4 mt-3">
                                    {{ (Session::has('search-title')) ? Session::get('search-title') : ' Tanda Tangan Kosong!' }}
                                </h4>
                                <p class="text-muted">
                                    {{ (Session::has('search')) ? Session::get('search') : ' Silahkan menambahkan tanda tangan terlebih dahulu.' }}
                                </p>
                                @endif
                            </div>
                        </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-5 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                            <div class="clearfix">
                                <h4 class="card-title float-left">Tambah Tanda Tangan</h4>
                            </div>
                            <hr class="mb-4">
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div id="signature-pad" class="signature-pad">
                                        <div class="signature-pad--body">
                                            <canvas class="canvas"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-5 text-right">
                                    <a href="#" class="btn btn-success" id="simpan">Simpan</a>
                                    <a href="#" class="btn btn-danger" id="reset">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layout.footer')
    </div>
</div>
@endsection