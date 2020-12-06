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
                        </span> Surat Keterangan Bebas Perlengkapan</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Keterangan Bebas Perlengkapan<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuan > 0 ? $countAllPengajuan.' Surat' : 'Data Surat Kosong' }}
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
                                        <h4>Surat Keterangan Bebas Perlengkapan</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('mahasiswa/surat-keterangan-bebas-perlengkapan/pengajuan/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">
                                            <i class="mdi mdi mdi-plus btn-icon-prepend"></i>
                                            Tambah Pengajuan</a>
                                    </div>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan bebas perlengkapan belum ada.' }}
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
                <h5 class="modal-title" id="exampleModalLabel">Progress Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-progress-content'></div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratPerlengkapan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-perlengkapan-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
    <script>
        let link = "{{ url('mahasiswa/surat-keterangan-bebas-perlengkapan/pengajuan') }}";
        let linkSurat = "{{ url('mahasiswa/surat-keterangan-bebas-perlengkapan') }}";
        
        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
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
                                let action = `<a href="${link+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${link+'/'+row.id}" class="dropdown-item pengajuan-surat-perlengkapan-detail" data-toggle="modal" data-target="#suratPerlengkapan">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSurat+'/'+row.id}" class="dropdown-item btn-surat-perlengkapan-detail" data-toggle="modal" data-target="#suratPerlengkapan">
                                                Detail</a>`;
                                }

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
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('mahasiswa/surat-keterangan-bebas-perlengkapan/pengajuan/all') }}',
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
            "order": [[ 2, 'desc' ]],
        });
    </script>
@endsection