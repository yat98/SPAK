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
                            <i class="mdi mdi-playlist-check"></i>
                        </span> Pendaftaran Cuti</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">                                        
                                        Verfikasi Pendaftaran Cuti
                                        <i class="mdi mdi-playlist-check mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllVerifikasi > 0 ? $countAllVerifikasi.' Verifikasi Pengajuan Cuti' : 'Verifikasi Pengajuan Cuti Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-dark card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pendaftaran Cuti <i
                                        class="mdi mdi-playlist-check mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPendaftaran > 0 ? $countAllPendaftaran.' Pendaftaran Cuti' : 'Data Pendaftaran Kosong' }}
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
                                        <h4>
                                            Verfikasi Pendaftaran Cuti
                                        </h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllVerifikasi > 0)
                                 <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama </th>
                                                <th> Tahun Akademik</th>
                                                <th> Alasan Cuti</th>
                                                <th data-priority="2"> Status</th>
                                                <th> Waktu Pendaftaran</th>
                                                <th> Keterangan</th>
                                                <th data-priority="3"> Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Cuti Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data pengajuan pendaftaran cuti belum ada.' }}
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
                                        <h4>Pendaftaran Cuti</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPendaftaran > 0)
                                 <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables1' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama </th>
                                                <th> Tahun Akademik</th>
                                                <th> Alasan Cuti</th>
                                                <th data-priority="2"> Status</th>
                                                <th> Waktu Pendaftaran</th>
                                                <th> Keterangan</th>
                                                <th data-priority="3"> Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pendaftaran Cuti Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data pendaftaran cuti belum ada.' }}
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
            <div class="modal-body" id='pendaftaran-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mahasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='mahasiswa-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
    <script>
        let link = "{{ url('pegawai/pendaftaran-cuti') }}";
        let linkMhs = "{{ url('pegawai/detail/mahasiswa') }}";
        
        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                            <div class="mb-1">${row.mahasiswa.nama}</div>
                                            <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                        </a>`;
                            }
                        },
                        {
                            "targets": 3,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 4,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 6,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let aksi = `<a href="${link+'/'+row.id}" class="dropdown-item btn-pendaftaran-cuti-detail" data-toggle="modal" data-target="#exampleModal">Detail</a>`;
                                @if(Auth::user()->bagian =='front office')
                                    aksi += `<a href="${link+'/'+row.id}/edit" class="dropdown-item">Edit</a>
                                             <form action="${link+'/'+row.id}" method="post">
                                                 <input name="_method" type="hidden" value="DELETE">
                                                 <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                 <button type="submit" class="dropdown-item sweet-delete">
                                                     Hapus
                                                 </button>
                                             </form>`;
                                @else
                                    aksi += `<form action="${link+'/verifikasi/'+row.id}" method="post">
                                                <input name="_method" type="hidden" value="PATCH">
                                                <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                <button type="submit" class="dropdown-item">
                                                    Verifikasi Pendaftaran
                                                </button>
                                             </form>
                                             <form action="${link+'/tolak/'+row.id}" method="post">
                                                <input name="_method" type="hidden" value="PATCH">
                                                <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                <input name="keterangan" type="hidden" value="-" id="keterangan_surat">
                                                <button type="submit" class="dropdown-item btn-tolak">
                                                    Tolak Pendaftaran
                                                </button>
                                             </form>`;
                                @endif
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${aksi}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [7,8,9],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pegawai/pendaftaran-cuti/pendaftaran/all') }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'tahun',
                },
                {
                    data: 'alasan_cuti',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
                {
                    data: 'tahun_akademik.tahun_akademik',
                },
                {
                    data: 'tahun_akademik.semester',
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 4, 'desc' ]],
        });

        $('#datatables1').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                            <div class="mb-1">${row.mahasiswa.nama}</div>
                                            <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                        </a>`;
                            }
                        },
                        {
                            "targets": 3,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 4,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 6,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${link+'/'+row.id}" class="dropdown-item btn-pendaftaran-cuti-detail" data-toggle="modal" data-target="#exampleModal">Detail</a>
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [7,8,9],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pegawai/pendaftaran-cuti/all') }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'tahun',
                },
                {
                    data: 'alasan_cuti',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
                {
                    data: 'tahun_akademik.tahun_akademik',
                },
                {
                    data: 'tahun_akademik.semester',
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 4, 'desc' ]],
        });
    </script>
@endsection