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
                            <i class="mdi mdi-home"></i>
                        </span> Dashboard </h3>
                </div>
                @if(isset($waktuCuti))  
                    @if($tgl->greaterThanOrEqualTo($waktuCuti->tanggal_awal_cuti) && $tgl->lessThanOrEqualTo($waktuCuti->tanggal_akhir_cuti)) 
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card mb-2">
                                <div class="card-body text-center">
                                    <h1 class="h1">Waktu Pendaftaran Cuti</h1>
                                    <div class="wrapper my-4">
                                        <ul class="flip-clock-container flip-2">
                                            <li class="flip-item-seconds">{{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->s }}</li>
                                            <li class="flip-item-minutes">{{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->i }}</li>
                                            <li class="flip-item-hours">{{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->h }}</li>
                                            <li class="flip-item-days">{{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->d }}</li>
                                        </ul>
                                    </div>
                                    <p class="text-secondary h4">
                                        Waktu pendaftaran cuti tersisa {{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->d }} Hari {{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->h }} Jam {{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->i }} Menit
                                    </p>
                                </div>    
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($tgl->lessThanOrEqualTo($waktuCuti->tanggal_awal_cuti)) 
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card mb-2 bg-danger text-white">
                                <div class="card-body text-center">
                                    <h1>Pendaftaran Cuti Pada Semester Ini Belum Di Buka</h1>
                                </div>     
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($tgl->greaterThanOrEqualTo($waktuCuti->tanggal_akhir_cuti)) 
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card mb-2 bg-danger text-white">
                                <div class="card-body text-center">
                                    <h1>Pendaftaran Cuti Pada Semester Telah Selesai</h1>
                                </div>     
                            </div>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card mb-2 bg-danger text-white">
                                <div class="card-body text-center">
                                    <h1>Pendaftaran Cuti Pada Semester Ini Belum Di Buka</h1>
                                </div>     
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tahun Akademik<i
                                    class="mdi mdi-calendar-text mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ isset($tahunAkademikAktif) ? $tahunAkademikAktif->tahun_akademik.' - '.ucwords($tahunAkademikAktif->semester) : 'Tidak Ada Tahun Akademik Aktif' }}
                                </h2>
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