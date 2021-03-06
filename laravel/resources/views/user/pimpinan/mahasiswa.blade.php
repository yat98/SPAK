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
                            <i class="mdi mdi-account"></i>
                        </span> Mahasiswa</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-quepal card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Mahasiswa<i
                                        class="mdi mdi-account mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllMahasiswa > 0 ? $countAllMahasiswa.' Mahasiswa' : 'Data Mahasiswa Kosong' }}
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
                                    <div class="col-12 col-md-4">
                                        <h4>Data Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllMahasiswa > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama</th>
                                                <th> Jurusan</th>
                                                <th> Angkatan</th>
                                                <th data-priority="1"> Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Mahasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data mahasiswa belum ada.' }}
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
@endsection

@section('datatables-javascript')
<script>
    let link = "{{ url('pimpinan/mahasiswa/') }}";

    let datatables = $('#datatables').DataTable({
        responsive: true,
        columnDefs: [{
                        "targets": 0,
                        "data": "nim",
                        "render": function ( data, type, row, meta ) {
                            return `<div class="mb-1">${row.nama}</div>
                                    <span class="text-muted small">NIM. ${row.nim}</span>`;
                        }
                    },{
                        "targets": [4],
                        "visible": false,
                    },
                    {
                        "targets": 3,
                        "data": "aksi",
                        "render": function ( data, type, row, meta ) {
                            return `<div class="d-inline-block">
                                        <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                            <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                        </a>
                                        <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                            <a href="${link+'/'+row.nim}" class="dropdown-item">Detail</a>
                                        </div>
                                    </div>`;
                    }
        }],
        autoWidth: false,
        language: bahasa,
        processing: true,
        serverSide: true,
        ajax: '{{ url('pimpinan/mahasiswa/all') }}',
        columns: [{
                data: 'nim',
            },
            {
                data: 'prodi.jurusan.nama_jurusan',
            },
            {
                data: 'angkatan',
            },
            {
                data: 'aksi', name: 'aksi', orderable: false, searchable: false
            },
            {
                data: 'nama',
            },
        ],
        "pageLength": {{ $perPage }}
    });   
</script>
@endsection