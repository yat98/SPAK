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
                            <i class="mdi mdi-checkbox-multiple-marked"></i>
                        </span> Status Mahasiswa </h3>
                </div>
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
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
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
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Status Mahasiswa<i
                                        class="mdi mdi-checkbox-multiple-marked mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllStatusMahasiswa > 0 ? $countAllStatusMahasiswa.' Status Mahasiswa' : 'Data Status Mahasiswa Kosong' }}
                                </h2>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <h4>Data Status Mahasiswa</h4>
                                    </div>
                                    <div class="col-12 col-md-8 text-right mt-4 mt-md-0 mt-lg-0">
                                        <a href="{{ url('admin/status-mahasiswa/create')}}"
                                            class="btn-sm btn btn-info btn-tambah">+
                                            Tambah Status Mahasiswa</a>
                                        <a href="{{ url('admin/status-mahasiswa/import-status-mahasiswa')}}"
                                            class="btn-sm btn btn-success btn-tambah mt-3 mt-md-0 mt-lg-0 btn-margin">
                                            <i class="mdi mdi-file-excel btn-icon-prepend"></i>
                                            Import Data Status Mahasiswa</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllStatusMahasiswa > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama</th>
                                                <th> Tahun Akademik</th>
                                                <th> Status Aktif</th>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Status Mahasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Silahkan mengisi data status mahasiswa terlebih dahulu.' }}
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
            <div class="modal-body" id='status-mahasiswa-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('datatables-javascript')
<script>
    let link = "{{ url('admin/status-mahasiswa/') }}";

    let datatables = $('#datatables').DataTable({
        responsive: true,
        columnDefs: [{
                        "targets": 0,
                        "data": "nim",
                        "render": function ( data, type, row, meta ) {
                            return `<a href="${link}/${row.tahun_akademik.id}/${row.nim}" class="status-mahasiswa-detail text-dark" data-toggle="modal" data-target="#exampleModal">
                                        <div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.nim}</span>
                                    </a>`;
                        }
                    },
                    {
                        "targets": 1,
                        "data": "tahun_akademik",
                        "render": function ( data, type, row, meta ) {
                            return row.tahun_akademik.tahun_akademik+' - '+row.tahun_akademik.semester;
                        }
                    },
                    {
                        "targets": [4],
                        "visible": false,
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },
                    {
                        "targets": 2,
                        "data": "status",
                        "render": function( data, type, row, met ){
                            if (row.status == 'Aktif'){
                                return `<label class="badge badge-gradient-info">${row.status}</label>`;
                            }else if(row.status == 'lulus'){
                                return `<label class="badge badge-gradient-success">${row.status}</label>`;
                            }else if(row.status == 'drop out' || row.status == 'keluar'){
                                return `<label class="badge badge-gradient-danger">${row.status}</label>`;
                            }else if(row.status == 'cuti'){
                                return `<label class="badge badge-gradient-warning">${row.status}</label>`;
                            }else{
                                return `<label class="badge badge-gradient-dark">${row.status}</label>`;
                            }
                        }
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
                                            <a href="${link+'/'+row.tahun_akademik.id+'/'+row.nim}/edit" class="dropdown-item">Edit</a>
                                            <form action="${link}" method="post">
                                                <input name="nim" type="hidden" value="${row.nim}">
                                                <input name="id_tahun_akademik" type="hidden" value="${row.tahun_akademik.id}">
                                                <input name="_method" type="hidden" value="DELETE">
                                                <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                <button type="submit" class="dropdown-item sweet-delete">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>`;
                    }
        }],
        autoWidth: false,
        language: bahasa,
        processing: true,
        serverSide: true,
        ajax: '{{ url('admin/status-mahasiswa/all') }}',
        columns: [{
                data: 'mahasiswa.nim',
            },
            {
                data: 'tahun_akademik.tahun_akademik',
            },
            {
                data: 'status',
            },
            {
                data: 'aksi', name: 'aksi', orderable: false, searchable: false
            },
            {
                data: 'tahun_akademik.semester',
            },
            {
                data: 'mahasiswa.nama',
            },
        ],
        "pageLength": {{ $perPage }}
    });   
</script>
@endsection