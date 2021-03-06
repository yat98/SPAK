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
                        </span> Surat Rekomendasi Penelitian</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuan > 0 ? $countAllPengajuan.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
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
                                <h4 class="font-weight-normal mb-3">Surat Rekomendasi Penelitian<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSurat > 0 ? $countAllSurat.' Surat' : 'Data Surat Kosong' }}
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
                                        <h4>Pengajuan Surat Rekomendasi Penelitian</h4>
                                    </div>
                                    @if(Auth::user()->bagian == 'front office')
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('operator/surat-rekomendasi-penelitian/pengajuan/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">
                                            <i class="mdi mdi mdi-plus btn-icon-prepend"></i>
                                            Tambah Pengajuan</a>
                                    </div>
                                    @endif
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPengajuan > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama </th>
                                                <th> Status</th>
                                                <th> Waktu Pengajuan</th>
                                                <th> Keterangan</th>
                                                <th data-priority="2"> Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Pengajuan surat rekomendasi penelitian belum ada.' }}
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
                                        <h4>Surat Rekomendasi Penelitian</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSurat > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables1' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama</th>
                                                <th> Nomor Surat</th>
                                                <th data-priority="2"> Status</th>
                                                <th> Waktu Pengajuan</th>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat rekomendasi penelitian belum ada.' }}
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

@if(Auth::user()->bagian == 'front office')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Progress Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-progress-content'></div>
        </div>
    </div>
</div>
@endif

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

<div class="modal fade" id="suratPenelitian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-penelitian-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
    <script>
        let link = "{{ url('operator/surat-rekomendasi-penelitian/pengajuan') }}";
        let linkSurat = "{{ url('operator/surat-rekomendasi-penelitian') }}";
        let linkMhs = "{{ url('operator/detail/mahasiswa') }}";

        @if(Auth::user()->bagian == 'front office')
            const order = [[ 2, 'desc' ]];
        @else
            const order = [[2 , 'asc'],[ 3, 'desc' ]];
        @endif

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
                            "targets": 1,
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
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                @if(Auth::user()->bagian == 'front office')
                                    return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    <a href="${link+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>
                                                    <a href="${link+'/'+row.id}" class="dropdown-item pengajuan-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">Detail</a>
                                                    <a href="${link+'/'+row.id}/edit" class="dropdown-item">Edit</a>
                                                    <form action="${link+'/'+row.id}" method="post">
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                        <button type="submit" class="dropdown-item sweet-delete">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>`; 
                                @else
                                    let aksi = `<a href="${link+'/'+row.id}" class="dropdown-item pengajuan-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">Detail</a>`;
                                    if(row.status == 'Diajukan'){
                                        aksi += `<a href="${linkSurat+'/create/'+row.id}" class="dropdown-item">Buat Surat</a>
                                                <form action="${link+'/tolak-pengajuan/'+row.id}" method="post">
                                                    <input name="_method" type="hidden" value="PATCH">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="keterangan" type="hidden" value="-" id="keterangan_surat">
                                                    <button type="submit" class="dropdown-item tolak-surat">
                                                        Tolak Pengajuan
                                                    </button>
                                                </form>`;
                                    }else if(row.status == 'Ditolak'){
                                        aksi = `<a href="${link+'/'+row.id}" class="dropdown-item pengajuan-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">Detail</a>`;
                                    }else{
                                        aksi = `<a href="${linkSurat+'/'+row.id}" class="dropdown-item surat-keterangan-detail" data-toggle="modal" data-target="#suratPenelitian">Detail</a>`;
                                    }

                                    return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        ${aksi}
                                                    </div>
                                                </div>`;
                                @endif
                            }
                        },
                        {
                            "targets": [5],
                            "visible": false,
                        }
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/surat-rekomendasi-penelitian/pengajuan/all') }}',
            columns: [{
                    data: 'mahasiswa.nim',
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
            ],
            "pageLength": {{ $perPage }},
            "order": order,
        });

        $('#datatables1').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "mahasiswa.nama",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                            <div class="mb-1">${row.mahasiswa.nama}</div>
                                            <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                        </a>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "surat_rekomendasi_penelitian.nomor_surat",
                            "render": function ( data, type, row, meta ) {
                                return `${row.surat_rekomendasi_penelitian.nomor_surat}/${row.surat_rekomendasi_penelitian.kode_surat.kode_surat}`;
                            }
                        },
                        {
                            "targets": 2,
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
                            "targets": 3,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                @if(Auth::user()->bagian != 'front office')
                                    return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    <a href="${linkSurat+'/'+row.id}" class="dropdown-item btn-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">
                                                            Detail</a>
                                                    <a href="${linkSurat+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                </div>
                                            </div>`;
                                @else
                                    let action = `<a href="${linkSurat+'/'+row.id}" class="dropdown-item btn-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">
                                                    Detail</a>`;

                                    if(row.status == 'Selesai'){
                                        action += `<a href="${linkSurat+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                    }
                                    
                                    return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    ${action}
                                                </div>
                                            </div>`;
                                @endif
                            },
                        },
                        {
                            "targets": [5],
                            "visible": false,
                        }
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/surat-rekomendasi-penelitian/all') }}',
            columns: [{
                    data: 'mahasiswa.nama',
                },
                {
                    data: 'surat_rekomendasi_penelitian.nomor_surat',
                },
                {
                    data: 'status',
                },
                 {
                    data: 'created_at',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'nim',
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[1,'desc']],
        });
    </script>
@endsection 