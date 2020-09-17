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
                        </span> Surat Keterangan Aktif Kuliah </h3>
                </div>
                <div class="row">
                    @if(Auth::user()->jabatan == 'kabag tata usaha')   
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-warning card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Verifikasi Surat<i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllVerifikasi > 0 ? $countAllVerifikasi.' Verifikasi Surat' : 'Verifikasi Surat Kosong' }}
                                    </h2>
                                    <h6 class="card-text"></h6>
                                </div>
                            </div>
                        </div>                                         
                    @endif
                    <div class="@if(Auth::user()->jabatan == 'kabag tata usaha') col-md-4 @else col-md-6 @endif stretch-card grid-margin">
                        <div class="card bg-gradient-orange card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tanda Tangan Surat <i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllTandaTangan > 0 ? $countAllTandaTangan.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="@if(Auth::user()->jabatan == 'kabag tata usaha') col-md-4 @else col-md-6 @endif stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Keterangan Aktif Kuliah <i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
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
                    @if(Auth::user()->jabatan == 'kabag tata usaha')
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-12 col-md-6">
                                            <h4>Verifikasi Surat Keterangan Aktif Kuliah</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllVerifikasi > 0)
                                    <div class="table-responsive">
                                        <table class="table display no-warp" id='datatables' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama</th>
                                                    <th> Nomor Surat</th>
                                                    <th> Semester</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th data-priority="3"> Aksi</th>
                                                </tr>
                                            </thead>
                                            {{-- <tbody>
                                                @foreach ($pengajuanSuratKeteranganAktifList as $pengajuanSuratKeteranganAktif)
                                                <tr>
                                                    <td> {{ $loop->iteration + $perPage * ($pengajuanSuratKeteranganAktifList->currentPage() - 1) }}</td>
                                                    <td> {{ $pengajuanSuratKeteranganAktif->mahasiswa->nama }}</td>
                                                    <td> {{ $pengajuanSuratKeteranganAktif->tahunAkademik->tahun_akademik.' - '.ucwords($pengajuanSuratKeteranganAktif->tahunAkademik->semester) }}</td>
                                                    <td> 
                                                        @if ($pengajuanSuratKeteranganAktif->status == 'diajukan')
                                                        <label class="badge badge-gradient-warning text-dark">
                                                            {{ ucwords($pengajuanSuratKeteranganAktif->status) }}
                                                        </label>
                                                        @elseif($pengajuanSuratKeteranganAktif->status == 'ditolak')
                                                        <label class="badge badge-gradient-danger">
                                                            {{ ucwords($pengajuanSuratKeteranganAktif->status) }}
                                                        </label>
                                                        @endif
                                                    </td>
                                                    <td>{{ $pengajuanSuratKeteranganAktif->keterangan }}</td>
                                                    <td>
                                                        <a href="{{ url('pimpinan/detail/mahasiswa/'.$pengajuanSuratKeteranganAktif->nim) }}" class="btn-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                            <i class="mdi mdi-account btn-icon-prepend"></i>
                                                            Detail</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody> --}}
                                        </table>
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col text-center">
                                            <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                            <h4 class="display-4 mt-3">
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Verifikasi Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Verifikasi surat keterangan aktif kuliah belum ada.' }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Tanda Tangan Surat Keterangan Aktif Kuliah</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllTandaTangan > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables1' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama</th>
                                                <th> Nomor Surat</th>
                                                <th> Semester</th>
                                                <th data-priority="2"> Status</th>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Verifikasi Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Verifikasi surat keterangan aktif kuliah belum ada.' }}
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
                                        <h4>Surat Keterangan Aktif Kuliah</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSurat > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables2' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama</th>
                                                <th> Nomor Surat</th>
                                                <th> Semester</th>
                                                <th data-priority="2"> Status</th>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan aktif kuliah belum ada.' }}
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
        let link = "{{ url('pimpinan/surat-keterangan-aktif-kuliah') }}";
        let linkMhs = "{{ url('pimpinan/detail/mahasiswa') }}";

        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nama",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkMhs}/${row.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                            <div class="mb-1">${row.nama}</div>
                                            <span class="text-muted small">NIM. ${row.nim}</span>
                                        </a>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "nomor_surat",
                            "render": function ( data, type, row, meta ) {
                                return `${row.nomor_surat}/${row.kode_surat}`;
                            }
                        },
                        {
                            "targets": 2,
                            "data": "tahun_akademik",
                            "render": function ( data, type, row, meta ) {
                                return `${row.tahun_akademik} - ${row.semester}`;
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
                            "targets": [5,6],
                            "visible": false,
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                 <a href="${link+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#exampleModal">
                                                        Detail</a>
                                                <form action="${link+'/verifikasi'}" method="post">
                                                    <input name="_method" type="hidden" value="PATCH">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="id" type="hidden" value="${row.id_pengajuan}">
                                                    <button type="submit" class="dropdown-item btn-verification">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            </div>
                                        </div>`;
                            },
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                 <a href="${link+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#exampleModal">
                                                        Detail</a>
                                                <form action="${link+'/verifikasi'}" method="post">
                                                    <input name="_method" type="hidden" value="PATCH">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="id" type="hidden" value="${row.id}">
                                                    <button type="submit" class="dropdown-item btn-verification">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            </div>
                                        </div>`;
                            },
                        },
                        {
                            "targets": [5,6],
                            "visible": false,
                        }
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-keterangan-aktif-kuliah/verifikasi/all') }}',
            columns: [{
                    data: 'nama',
                },
                {
                    data: 'nomor_surat',
                },
                {
                    data: 'tahun_akademik',
                },
                {
                    data: 'status',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'nim',
                }, 
                {
                    data: 'semester',
                },
            ],
            pageLength: {{ $perPage }},
            order: [[ 1, 'desc' ]],
        });

        $('#datatables1').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nama",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkMhs}/${row.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                            <div class="mb-1">${row.nama}</div>
                                            <span class="text-muted small">NIM. ${row.nim}</span>
                                        </a>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "nomor_surat",
                            "render": function ( data, type, row, meta ) {
                                return `${row.nomor_surat}/${row.kode_surat}`;
                            }
                        },
                        {
                            "targets": 2,
                            "data": "tahun_akademik",
                            "render": function ( data, type, row, meta ) {
                                return `${row.tahun_akademik} - ${row.semester}`;
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
                            "targets": [5,6],
                            "visible": false,
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${link+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#exampleModal">
                                                    Detail</a>
                                                <form action="${link+'/tanda-tangan'}" method="post">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="id" type="hidden" value="${row.id_pengajuan}">
                                                    <button type="submit" class="dropdown-item simpan-tanda-tangan">
                                                        Tanda Tangan
                                                    </button>
                                                </form>
                                            </div>
                                        </div>`;
                            },
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                 <a href="${link+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#exampleModal">
                                                        Detail</a>
                                                <form action="${link+'/verifikasi'}" method="post">
                                                    <input name="_method" type="hidden" value="PATCH">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="id" type="hidden" value="${row.id}">
                                                    <button type="submit" class="dropdown-item btn-verification">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            </div>
                                        </div>`;
                            },
                        },
                        {
                            "targets": [5,6],
                            "visible": false,
                        }
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-keterangan-aktif-kuliah/tanda-tangan/all') }}',
            columns: [{
                    data: 'nama',
                },
                {
                    data: 'nomor_surat',
                },
                {
                    data: 'tahun_akademik',
                },
                {
                    data: 'status',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'nim',
                }, 
                {
                    data: 'semester',
                },
            ],
            pageLength: {{ $perPage }},
            order: [[ 1, 'desc' ]],
        });

        $('#datatables2').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nama",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkMhs}/${row.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                            <div class="mb-1">${row.nama}</div>
                                            <span class="text-muted small">NIM. ${row.nim}</span>
                                        </a>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "nomor_surat",
                            "render": function ( data, type, row, meta ) {
                                return `${row.nomor_surat}/${row.kode_surat}`;
                            }
                        },
                        {
                            "targets": 2,
                            "data": "tahun_akademik",
                            "render": function ( data, type, row, meta ) {
                                return `${row.tahun_akademik} - ${row.semester}`;
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
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = `<a href="${link+'/'+row.id_pengajuan}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#exampleModal">
                                                Detail</a>
                                              <a href="${link+'/'+row.id_pengajuan+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                            },
                        },
                        {
                            "targets": [5,6],
                            "visible": false,
                        }
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-keterangan-aktif-kuliah/all') }}',
            columns: [{
                    data: 'nama',
                },
                {
                    data: 'nomor_surat',
                },
                {
                    data: 'tahun_akademik',
                },
                {
                    data: 'status',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'nim',
                }, 
                {
                    data: 'semester',
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[1,'desc']],
        });
    </script>
@endsection