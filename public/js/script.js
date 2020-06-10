String.prototype.ucwords = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function infoMessage(title, text) {
    Swal.fire({
        icon: 'info',
        title: title,
        text: text,
    });
}

function successMessage(title, text) {
    Swal.fire({
        icon: 'success',
        title: title,
        text: text,
    });
}

function successMessageTimer(title, text) {
    Swal.fire({
        icon: 'success',
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 3500
    });
}

function errorMessage(title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
    });
}

function showProgress() {
    Swal.fire({
        html: 'Sedang melakukan import data...',
        timerProgressBar: true,
        onBeforeOpen: () => {
            Swal.showLoading()
        },
        allowOutsideClick: false,
    })
}

function removePositionPagination(mediaQuery) {
    if (mediaQuery.matches) {
        $('.pagination').removeClass('justify-content-end');
    } else {
        $('.pagination').addClass('justify-content-end mt-5');
    }
}

let mediaQuery = window.matchMedia("(max-width: 767px)");

$('.pagination').addClass('justify-content-end mt-5');

let jenisUser = document.getElementById('jenis_user');
$('#jenis-user').on('click', function () {
    let username = $('#username');
    let value = '';
    switch ($(this).val()) {
        case 'pimpinan':
            value = 'nip'
            break;
        case 'mahasiswa':
            value = 'nim'
            break;
        case 'pegawai':
            value = 'nip'
            break;
    }
    username.attr('placeholder', value.toUpperCase());
});

$('.sweet-delete').on('click', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "Data akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            let form = $(this).parents('form');
            form.submit();
        }
    })
})

$(window).resize(() => {
    removePositionPagination(mediaQuery);
}).trigger('resize');

$('.btn-upload').on('click', showProgress);

$('.btn-detail').on('click', function (e) {
    e.preventDefault();
    $('#mahasiswa-detail-content').empty();
    $('#surat-keterangan-aktif-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let mahasiswa = result;
            let tableStatus = '';
            console.log(mahasiswa);
            
            if(mahasiswa.tahun_akademik.length > 0){
                mahasiswa.tahun_akademik.forEach((status)=>{
                    tableStatus+=`
                        <tr>
                            <td>${status.tahun_akademik} - ${status.semester.ucwords()}<td>
                    `;
                    if (status.pivot.status == 'aktif'){
                        tableStatus+=`<td><label class="badge badge-gradient-info">${status.pivot.status.ucwords()}</label><td></tr>`;
                    }else if(status.pivot.status == 'lulus'){
                        tableStatus+=`<td><label class="badge badge-gradient-success">${status.pivot.status.ucwords()}</label><td></tr>`;
                    }else if(status.pivot.status == 'drop out' || status.pivot.status == 'keluar'){
                        tableStatus+=`<td><label class="badge badge-gradient-danger">${status.pivot.status.ucwords()}</label><td></tr>`;
                    }else if(status.pivot.status == 'cuti'){
                        tableStatus+=`<td><label class="badge badge-gradient-warning text-dark">${status.pivot.status.ucwords()}</label><td></tr>`;
                    }else{
                        tableStatus+=`<td><label class="badge badge-gradient-dark">${status.pivot.status.ucwords()}</label><td></tr>`;
                    }
                });
            }else{
                tableStatus = 'Data Status Mahasiswa Belum Ada';
            }
            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>NIM</th>
                                    <td>${mahasiswa.nim}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>${mahasiswa.nama}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>${(mahasiswa.sex == 'L') ? 'Laki-Laki' : 'Perempuan'}</td>
                                </tr>
                                <tr>
                                    <th>Tempat Tanggal Lahir</th>
                                    <td>${mahasiswa.tempat_lahir}, ${mahasiswa.tanggal_lahir}</td>
                                </tr>
                                <tr>
                                    <th>Angkatan</th>
                                    <td>${mahasiswa.angkatan}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td>${mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                </tr>
                                <tr>
                                    <th>Program Studi</th>
                                    <td>${mahasiswa.prodi.strata+' - '+mahasiswa.prodi.nama_prodi}</td>
                                </tr>
                                <tr>
                                    <th>IPK</th>
                                    <td>${mahasiswa.ipk.toFixed(2)}</td>
                                </tr>
                                <tr>
                                    <th>Status Mahasiswa</th>
                                    <td>
                                       <table>
                                       ${tableStatus}
                                       </table>
                                    </td>
                                </tr>
                            </table>
                        </div>`;
            $('#mahasiswa-detail-content').html(html);
            $('#surat-keterangan-aktif-detail-content').html(html);
            console.log(html);
            
        });
})

$('.btn-password').on('click',function(e){
    e.preventDefault();
    $('.password-group').html(`<label for="password">Password</label>
    <input class="form-control form-control-lg" id="password" name="password" type="password" value="">`)
})

$("#mahasiswa_list").select2();
$("#id_surat_masuk").select2();
$(".select").select2();
$('.search').select2({
    width: '100%'
});

let wrapper = document.getElementById("signature-pad");

if(wrapper){
    let canvas = wrapper.querySelector("canvas");
    let signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'white',
        minWidth: 0.5,
        maxWidth:  3,
        penColor: "blue"
    });
    function resizeCanvas() {
        let ratio =  Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }  

    window.onresize = resizeCanvas;
    resizeCanvas();

    let reset = $("#reset");
    reset.on('click',function(e){
        e.preventDefault();
        signaturePad.clear();
    })

    let simpan = $('#simpan');
    simpan.on('click',function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Yakin?',
            text: "Data akan disimpan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                if (signaturePad.isEmpty()) {
                    errorMessage('Tanda Tangan Kosong!','Gambarkan tanda tangan anda terlebih dahulu.');
                } else {                    
                    document.body.innerHTML += '<form id="form_tanda_tangan" action="'+window.location.href+'" method="post"><input type="hidden" name="tanda_tangan" value="'+signaturePad.toDataURL()+'"></form>';
                    document.getElementById("form_tanda_tangan").submit();
                }
            }
        })
    })
}

$('.btn-surat-detail').on('click', function (e) {
    e.preventDefault();
    $('#surat-keterangan-aktif-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratDetail = result;
            let tahun = suratDetail.created.toString();
            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>NIM</th>
                                    <td>${suratDetail.pengajuan_surat_keterangan.nim}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>${suratDetail.pengajuan_surat_keterangan.mahasiswa.nama}</td>
                                </tr>
                                <tr>
                                    <th>Angkatan</th>
                                    <td>${suratDetail.pengajuan_surat_keterangan.mahasiswa.angkatan}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td>${suratDetail.pengajuan_surat_keterangan.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                </tr>
                                <tr>
                                    <th>Program Studi</th>
                                    <td>${suratDetail.pengajuan_surat_keterangan.mahasiswa.prodi.strata+' - '+suratDetail.pengajuan_surat_keterangan.mahasiswa.prodi.nama_prodi}</td>
                                </tr>
                                <tr>
                                <th>Nomor Surat</th>
                                    <td>B/${suratDetail.nomor_surat}/${suratDetail.kode}/${suratDetail.created_at.toString().slice(0,4)}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <td>${suratDetail.pengajuan_surat_keterangan.jenis_surat.ucwords()}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>${suratDetail.pengajuan_surat_keterangan.status.ucwords()}</td>
                                </tr>
                                <tr>
                                    <th>Di Tandatangani Oleh</th>
                                    <td>${suratDetail.user.nama}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Cetak</th>
                                    <td>${suratDetail.jumlah_cetak}</td>
                                </tr>
                                <tr>
                                    <th>Di Buat</th>
                                    <td>${tahun}</td>
                                </tr>
                            </table>
                        </div>`;
                        console.log(tahun.slice(0,4));
                        
            $('#surat-keterangan-aktif-detail-content').html(html);
        });
})

let tandaTangan = $('.simpan-tanda-tangan');
tandaTangan.on('click',function(e){
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "Surat akan ditandatangani",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tanda tangan',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            if (result.value) {
                let form = $(this).parents('form');
                form.submit();
            }
        }
    })
});

let tolakSurat = $('.tolak-surat');
tolakSurat.on('click', function(e){
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "pengajuan surat akan ditolak",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tolak Surat',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            if (result.value) {
                Swal.fire({
                    title: 'Keterangan',
                    input: 'textarea',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Cancel',
                    inputPlaceholder: 'Keterangan...',
                    inputAttributes: {
                      'aria-label': 'Keterangan...'
                    },
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value.trim() === undefined || value.trim() == null || value.length <= 0) {
                                resolve('Keterangan wajib diisi.')
                            } else {
                                console.log(value);
                                $('#keterangan_surat').val(value);
                                let form = $(this).parents('form');
                                form.submit();
                            }
                        })
                    }
                })
            }
        }
    })
});

$('.btn-surat-progress').on('click', function (e) {
    e.preventDefault();
    $('#surat-progress-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let pengajuanSurat = result;
            if(pengajuanSurat.status == 'selesai'){
                html = `<div class="row">
                        <div class="col-6 text-center">
                            <p class="h6 m-0 mb-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-success"></i>
                                Diajukan
                            </p> 
                            <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                            <div class="bg-gradient-success mx-auto position-progress-round"></div>
                            <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                        </div>
                        <div class="col-6 text-center"></div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 100%" aria-valuenow="100   " aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-center"></div>
                        <div class="col-6 text-center">
                            <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                            <div class="bg-gradient-success mx-auto position-progress-round"></div>
                            <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_selesai}</small></p>
                            <p class="h6 mt-1 text-dark">
                            <i class="mdi mdi-marker-check icon-sm text-success"></i>
                            Selesai</p> 
                        </div>
                    </div>`;
            }else if(pengajuanSurat.status == 'diajukan'){
                html = `<div class="row">
                            <div class="col-6 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-6 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 50%" aria-valuenow="50   " aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-center"></div>
                            <div class="col-6 text-center"></div>
                        </div>`;
            }else if(pengajuanSurat.status == 'ditolak'){
                html = `<div class="row">
                        <div class="col-6 text-center">
                            <p class="h6 m-0 mb-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                Diajukan
                            </p> 
                            <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                            <div class="bg-gradient-danger mx-auto position-progress-round"></div>
                            <div class="bg-gradient-danger mx-auto position-progress-pole"></div>
                        </div>
                        <div class="col-6 text-center"></div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 100%" aria-valuenow="100   " aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-center"></div>
                        <div class="col-6 text-center">
                            <div class="bg-gradient-danger mx-auto position-progress-pole"></div>
                            <div class="bg-gradient-danger mx-auto position-progress-round"></div>
                            <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_ditolak}</small></p>
                            <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-close-circle icon-sm text-danger"></i>
                                Di Tolak
                            </p> 
                        </div>
                    </div>`;
            }
            $('#surat-progress-content').html(html);
        });
});

$('#tanggal_surat_masuk').datetimepicker({
    format:'Y-m-d',
    formatDate:'Y-m-d',
    timepicker:false,
});

$('.tanggal').datetimepicker({
    format:'Y-m-d',
    formatDate:'Y-m-d',
    timepicker:false,
});

 $(document).on('click','.tanggal', function(){
    $(this).datetimepicker({
        format:'Y-m-d',
        formatDate:'Y-m-d',
        timepicker:false,
    }).focus();
 });

$('.btn-surat-masuk-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-masuk-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let suratMasuk = result;
                    let html = `<div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td><b>Nomor Surat</b></td>
                                            <td>${suratMasuk.nomor_surat}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Perihal</b></td>
                                            <td>${suratMasuk.perihal}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Instansi</b></td>
                                            <td>${suratMasuk.instansi}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Tanggal Surat Masuk</b></td>
                                            <td>${suratMasuk.tanggal_surat_masuk}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Di buat</b></td>
                                            <td>${suratMasuk.created}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Di Ubah</b></td>
                                            <td>${suratMasuk.updated}</td>
                                        </tr>
                                        <tr>
                                            <td><b>File Surat</b></td>
                                            <td>
                                                <a href="${suratMasuk.link_file}" class="btn btn-info btn-sm" data-lightbox="${suratMasuk.nama_file}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File Surat Masuk</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>`;
                    $('#surat-masuk-detail-content').html(html);
                });
});

lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'disableScrolling':true
});

$('.btn-tambah-mahasiswa').on('click',function(e){
    e.preventDefault();
    $('.mahasiswa-wrap').append('<div class="form-row mb-3" style="margin-left:1px">'+$('.mahasiswa-select').clone().html()+'</div>');
});

new FroalaEditor('textarea#froala-editor',{
    fontFamily: {
        "Roboto,sans-serif": 'sans-serif',
    },
    fontFamilySelection: true
});

new FroalaEditor('textarea#lampiran_panitia',{
    height:600,
    fontFamily: {
        "serif": 'serif',
    },
    fontFamilySelection: true
});

var editor = new FroalaEditor('textarea#froala-editor-disabled',{
    height:600,
    fontFamily: {
        "Roboto,sans-serif": 'sans-serif',
    },
    fontFamilySelection: true
}, function () {
    editor.edit.off();
    editor.edit.isDisabled();
});

$('.btn-tambah-tahapan').on('click',function(e){
    e.preventDefault();
    let jumlahValue = $('#jumlah_tahapan_field').val();
    $('#jumlah_tahapan_field').val(++jumlahValue);
    $('.tahapan').append(`<div class="form-row copy-tahapan-field mb-3">
                            <div class="col-md-12 mb-1">
                                <input class="form-control" id="tahapan_kegiatan" placeholder="Tahapan kegiatan" name="tahapan_kegiatan[]" type="text">
                            </div>
                            <div class="col-md-6 mb-1">
                                <input class="form-control" id="tempat_kegiatan" placeholder="Tempat kegiatan" name="tempat_kegiatan[]" type="text">
                            </div>
                            <div class="col-md-3">
                                <input class="tanggal form-control" id="tanggal_awal_kegiatan" placeholder="Tanggal Awal Kegiatan" name="tanggal_awal_kegiatan[]" type="text">
                            </div>
                            <div class="col-md-3">
                                <input class="tanggal form-control" id="tanggal_akhir_kegiatan" placeholder="Tanggal Akhir Kegiatan" name="tanggal_akhir_kegiatan[]" type="text">
                            </div>
                        </div>`);
});

$('.btn-surat-dispensasi-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-dispensasi-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let suratDispensasi = result;                    
                    let tableMahasiswa = '';
                    let tableKegiatan = '';
                    let label;
                    if(suratDispensasi.status == 'menunggu tanda tangan'){
                        label = `<label class="badge badge-gradient-warning text-dark">${suratDispensasi.status.ucwords()}</label>`;
                    }else{
                        label = `<label class="badge badge-gradient-info">${suratDispensasi.status.ucwords()}</label>`;
                    }
                    suratDispensasi.mahasiswa.forEach((mahasiswa) => {
                        tableMahasiswa+=`<tr>
                                            <td>${mahasiswa.nim}</td>
                                            <td>${mahasiswa.nama}</td>
                                            <td>${mahasiswa.prodi.strata} - ${mahasiswa.prodi.nama_prodi}</td>
                                            <td>${mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>`;
                    });
                    suratDispensasi.tahapan_kegiatan_dispensasi.forEach((tahapan,key)=>{
                        let nmr = key;
                        tableKegiatan += `<tr>
                                            <td rowspan="3">${++nmr}.</td>
                                            <td colspan="3">${tahapan.tahapan_kegiatan}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>:</td>
                                            <td>${suratDispensasi.tanggal_kegiatan[key]}</td>
                                        </tr>
                                        <tr>
                                            <td>Tempat</td>
                                            <td>:</td>
                                            <td>${tahapan.tempat_kegiatan}</td>
                                        </tr>`;
                    });
                    
                    let html = `<div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th>Nomor Surat</th>
                                                    <td>${suratDispensasi.nomor_surat_dispensasi}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jenis Surat</th>
                                                    <td>Surat Dispensasi</td>
                                                </tr>
                                                <tr>
                                                    <th>Di Ajukan Oleh</th>
                                                    <td>${suratDispensasi.kasubag.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        ${label}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Nama Kegiatan</th>
                                                    <td>${suratDispensasi.nama_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Lihat File Surat Masuk</th>
                                                    <td>
                                                    <a href="${suratDispensasi.link_file}" class="btn btn-info btn-sm" data-lightbox="${suratDispensasi.nama_file}">
                                                        <i class="mdi mdi mdi-eye"></i>
                                                        Lihat File Surat Masuk</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Daftar Mahasiswa</th>
                                                    <td>
                                                        <table class="table">
                                                            <tr>
                                                                <th>NIM</th>
                                                                <th>Nama</th>
                                                                <th>Program Studi</th>
                                                                <th>Jurusan</th>
                                                            </tr>
                                                            ${tableMahasiswa}
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tahapan Kegiatan</th>
                                                    <td>
                                                        <table class="table">
                                                            ${tableKegiatan}
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tanda Tangan</th>
                                                    <td>${suratDispensasi.user.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jumlah Cetak</th>
                                                    <td>${suratDispensasi.jumlah_cetak}</td>
                                                </tr>
                                                <tr>
                                                    <th>Dibuat</th>
                                                    <td>${suratDispensasi.dibuat}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>`;
                    $('#surat-dispensasi-detail-content').html(html);
                });
});

$('.btn-surat-rekomendasi-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-rekomendasi-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let suratRekomendasi = result;                    
                    let tableMahasiswa = '';
                    let label;
                    if(suratRekomendasi.status == 'menunggu tanda tangan'){
                        label = `<label class="badge badge-gradient-warning text-dark">${suratRekomendasi.status.ucwords()}</label>`;
                    }else{
                        label = `<label class="badge badge-gradient-info">${suratRekomendasi.status.ucwords()}</label>`;
                    }
                    suratRekomendasi.mahasiswa.forEach((mahasiswa) => {
                        tableMahasiswa+=`<tr>
                                            <td>${mahasiswa.nim}</td>
                                            <td>${mahasiswa.nama}</td>
                                            <td>${mahasiswa.prodi.strata} - ${mahasiswa.prodi.nama_prodi}</td>
                                            <td>${mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>`;
                    });
                    
                    let html = `<div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th>Nomor Surat</th>
                                                    <td>${suratRekomendasi.nomor_surat_rekomendasi}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jenis Surat</th>
                                                    <td>Surat Rekomendasi</td>
                                                </tr>
                                                <tr>
                                                    <th>Di Ajukan Oleh</th>
                                                    <td>${suratRekomendasi.kasubag.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        ${label}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Nama Kegiatan</th>
                                                    <td>${suratRekomendasi.nama_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Kegiatan</th>
                                                    <td>${suratRekomendasi.tanggal}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tempat Kegiatan</th>
                                                    <td>${suratRekomendasi.tempat_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Daftar Mahasiswa</th>
                                                    <td>
                                                        <table class="table">
                                                            <tr>
                                                                <th>NIM</th>
                                                                <th>Nama</th>
                                                                <th>Program Studi</th>
                                                                <th>Jurusan</th>
                                                            </tr>
                                                            ${tableMahasiswa}
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tanda Tangan</th>
                                                    <td>${suratRekomendasi.user.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jumlah Cetak</th>
                                                    <td>${suratRekomendasi.jumlah_cetak}</td>
                                                </tr>
                                                <tr>
                                                    <th>Dibuat</th>
                                                    <td>${suratRekomendasi.dibuat}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>`;
                    $('#surat-rekomendasi-detail-content').html(html);
                });
});

$('.btn-surat-dispensasi-progress').on('click', function (e) {
    e.preventDefault();
    $('#surat-progress-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let pengajuanSurat = result;
            if(pengajuanSurat.status == 'selesai'){
                html = `<div class="row">
                            <div class="col-6 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-border-color icon-sm text-success"></i>
                                    Menunggu Tanda Tangan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-success mx-auto position-progress-round"></div>
                                <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-6 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 100%" aria-valuenow="100   " aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-center"></div>
                            <div class="col-6 text-center">
                                <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-success mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_selesai}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-success"></i>
                                Selesai</p> 
                            </div>
                        </div>`;
            }else if(pengajuanSurat.status == 'menunggu tanda tangan'){
                html = `<div class="row">
                            <div class="col-6 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-border-color icon-sm text-info"></i>
                                    Menunggu Tanda Tangan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-6 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 50%" aria-valuenow="50   " aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-center"></div>
                            <div class="col-6 text-center"></div>
                        </div>`;
            }
            $('#surat-progress-content').html(html);
        });
});

$('.btn-surat-tugas-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-tugas-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let suratTugas = result;                    
                    let tableMahasiswa = '';
                    let label;
                    if(suratTugas.status == 'menunggu tanda tangan'){
                        label = `<label class="badge badge-gradient-warning text-dark">${suratTugas.status.ucwords()}</label>`;
                    }else{
                        label = `<label class="badge badge-gradient-info">${suratTugas.status.ucwords()}</label>`;
                    }
                    suratTugas.mahasiswa.forEach((mahasiswa) => {
                        tableMahasiswa+=`<tr>
                                            <td>${mahasiswa.nim}</td>
                                            <td>${mahasiswa.nama}</td>
                                            <td>${mahasiswa.prodi.strata} - ${mahasiswa.prodi.nama_prodi}</td>
                                            <td>${mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>`;
                    });
                    
                    let html = `<div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th>Nomor Surat</th>
                                                    <td>${suratTugas.nomor_surat_tugas}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jenis Surat</th>
                                                    <td>Surat Tugas</td>
                                                </tr>
                                                <tr>
                                                    <th>Di Ajukan Oleh</th>
                                                    <td>${suratTugas.kasubag.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        ${label}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Nama Kegiatan</th>
                                                    <td>${suratTugas.nama_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jenis Kegiatan</th>
                                                    <td>${suratTugas.jenis_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Kegiatan</th>
                                                    <td>${suratTugas.tanggal}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tempat Kegiatan</th>
                                                    <td>${suratTugas.tempat_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Daftar Mahasiswa</th>
                                                    <td>
                                                        <table class="table">
                                                            <tr>
                                                                <th>NIM</th>
                                                                <th>Nama</th>
                                                                <th>Program Studi</th>
                                                                <th>Jurusan</th>
                                                            </tr>
                                                            ${tableMahasiswa}
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Tanda Tangan</th>
                                                    <td>${suratTugas.user.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jumlah Cetak</th>
                                                    <td>${suratTugas.jumlah_cetak}</td>
                                                </tr>
                                                <tr>
                                                    <th>Dibuat</th>
                                                    <td>${suratTugas.dibuat}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>`;
                    $('#surat-tugas-detail-content').html(html);
                });
});

$('.btn-pengajuan-pindah').on('click',function(e){
    e.preventDefault();
    $('#persetujuan-pindah-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let pengajuan =result;
                    console.log(pengajuan);
                    
                    let label;
                    if(pengajuan.status == 'menunggu tanda tangan' || pengajuan.status == 'diajukan'){
                        label = `<label class="badge badge-gradient-warning text-dark">${pengajuan.status.ucwords()}</label>`;
                    }else if(pengajuan.status == 'ditolak'){
                        label = `<label class="badge badge-gradient-danger">${pengajuan.status.ucwords()}</label>`;
                    }else{
                        label = `<label class="badge badge-gradient-info">${pengajuan.status.ucwords()}</label>`;
                    }
                    let html = `<div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>NIM</th>
                                            <td>${pengajuan.mahasiswa.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${pengajuan.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kampus</th>
                                            <td>${pengajuan.nama_kampus}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>${pengajuan.strata} - ${pengajuan.nama_prodi}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${pengajuan.keterangan}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Lulus Butuh</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_lulus_butuh}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_lulus_butuh}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Ijazah Terakhir</th>
                                            <td>
                                                <a href="${pengajuan.file_ijazah_terakhir}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_ijazah_terakhir}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>   
                                        <tr>
                                            <th>File Surat Rekomendasi Jurusan</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_rekomendasi_jurusan}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Bebas Perlengkapan Universitas</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_bebas_perlengkapan_universitas}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_bebas_perlengkapan_universitas}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Bebas Perlengkapan Fakultas</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_bebas_perlengkapan_fakultas}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_bebas_perlengkapan_fakultas}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Bebas Perpustakaan Universitas</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_bebas_perpustakaan_universitas}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_bebas_perpustakaan_universitas}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Bebas Perpustakaan Fakultas</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_bebas_perpustakaan_fakultas}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_bebas_perpustakaan_fakultas}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${pengajuan.created_at}</td>
                                        </tr>
                                    </table>
                                </div>`;
                    $('#persetujuan-pindah-detail-content').html(html);
                });
});

$('.btn-pengajuan-pindah-progress').on('click',function(e){
    $('#surat-progress-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let pengajuanSurat = result;
            if(pengajuanSurat.status == 'diajukan'){
                html = `<div class="row">
                            <div class="col-4 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-4 text-center"></div>
                            <div class="col-4 text-center"></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 33.3%" aria-valuenow="33.3" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-center"></div>
                                <div class="col-4 text-center"></div>
                                <div class="col-4 text-center"></div>
                            </div>
                        </div>`;
            }else if(pengajuanSurat.status == 'menunggu tanda tangan'){
                html = `<div class="row">
                            <div class="col-4 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-4 text-center"></div>
                            <div class="col-4 text-center"></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 66.6%" aria-valuenow="66.6   " aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-center"></div>
                                <div class="col-4 text-center">
                                    <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                    <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                    <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_tunggu_tanda_tangan}</small></p>
                                    <p class="h6 mt-1 text-dark">
                                        <i class="mdi mdi-border-color icon-sm text-info"></i>
                                        Menunggu Tanda Tangan
                                    </p> 
                                </div>
                                <div class="col-4 text-center"></div>
                            </div>
                        </div>`;
            }else if(pengajuanSurat.status == 'selesai'){
                html = `<div class="row">
                            <div class="col-4 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-success"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-success mx-auto position-progress-round"></div>
                                <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-4 text-center"></div>
                            <div class="col-4 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-success"></i>
                                    Selesai
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_selesai}</small></p>
                                <div class="bg-gradient-success mx-auto position-progress-round"></div>
                                <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                            </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 100%" aria-valuenow="100   " aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-center"></div>
                                <div class="col-4 text-center">
                                    <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                                    <div class="bg-gradient-success mx-auto position-progress-round"></div>
                                    <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_tunggu_tanda_tangan}</small></p>
                                    <p class="h6 mt-1 text-dark">
                                        <i class="mdi mdi-border-color icon-sm text-success"></i>
                                        Menunggu Tanda Tangan
                                    </p> 
                                </div>
                                <div class="col-4 text-center"></div>
                            </div>
                        </div>`;
            }else if(pengajuanSurat.status == 'ditolak'){
                html = `<div class="row">
                            <div class="col-6 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-danger mx-auto position-progress-round"></div>
                                <div class="bg-gradient-danger mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-6 text-center"></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 100%" aria-valuenow="100   " aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-center"></div>
                                <div class="col-6 text-center">
                                    <div class="bg-gradient-danger mx-auto position-progress-pole"></div>
                                    <div class="bg-gradient-danger mx-auto position-progress-round"></div>
                                    <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_ditolak}</small></p>
                                    <p class="h6 mt-1 text-dark">
                                        <i class="mdi mdi mdi-close-circle icon-sm text-danger"></i>
                                        Di Tolak
                                    </p> 
                                </div>
                            </div>
                        </div>`;
            }
        $('#surat-progress-content').html(html);
    })
});

$('.btn-surat-pindah-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-pindah-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let pengajuan =result;
                    let label;
                    if(pengajuan.pengajuan_surat_persetujuan_pindah.status == 'menunggu tanda tangan' || pengajuan.pengajuan_surat_persetujuan_pindah.status == 'diajukan'){
                        label = `<label class="badge badge-gradient-warning text-dark">${pengajuan.pengajuan_surat_persetujuan_pindah.status.ucwords()}</label>`;
                    }else if(pengajuan.pengajuan_surat_persetujuan_pindah.status == 'ditolak'){
                        label = `<label class="badge badge-gradient-danger">${pengajuan.pengajuan_surat_persetujuan_pindah.status.ucwords()}</label>`;
                    }else{
                        label = `<label class="badge badge-gradient-info">${pengajuan.pengajuan_surat_persetujuan_pindah.status.ucwords()}</label>`;
                    }
                    let html = `<div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Nomor Surat</th>
                                            <td>B/${pengajuan.nomor_surat}/${pengajuan.kode_surat.kode_surat}/${pengajuan.created_at.toString().slice(6,10)}</td>
                                        </tr>
                                        <tr>
                                            <th>NIM</th>
                                            <td>${pengajuan.pengajuan_surat_persetujuan_pindah.mahasiswa.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${pengajuan.pengajuan_surat_persetujuan_pindah.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kampus</th>
                                            <td>${pengajuan.pengajuan_surat_persetujuan_pindah.nama_kampus}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>${pengajuan.pengajuan_surat_persetujuan_pindah.strata} - ${pengajuan.pengajuan_surat_persetujuan_pindah.nama_prodi}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${pengajuan.pengajuan_surat_persetujuan_pindah.keterangan}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Lulus Butuh</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_lulus_butuh}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_lulus_butuh}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Ijazah Terakhir</th>
                                            <td>
                                                <a href="${pengajuan.file_ijazah_terakhir}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_ijazah_terakhir}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>   
                                        <tr>
                                            <th>File Surat Rekomendasi Jurusan</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_rekomendasi_jurusan}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Bebas Perlengkapan Universitas</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_bebas_perlengkapan_universitas}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_bebas_perlengkapan_universitas}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Bebas Perlengkapan Fakultas</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_bebas_perlengkapan_fakultas}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_bebas_perlengkapan_fakultas}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Bebas Perpustakaan Universitas</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_bebas_perpustakaan_universitas}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_bebas_perpustakaan_universitas}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Keterangan Bebas Perpustakaan Fakultas</th>
                                            <td>
                                                <a href="${pengajuan.file_surat_keterangan_bebas_perpustakaan_fakultas}" class="btn btn-info btn-sm" data-lightbox="${pengajuan.nama_file_surat_keterangan_bebas_perpustakaan_fakultas}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Cetak</th>
                                            <td>${pengajuan.jumlah_cetak}</td>
                                        </tr>
                                        <tr>
                                            <th>Di Tandatangani Oleh</th>
                                            <td>${pengajuan.user.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${pengajuan.created_at}</td>
                                        </tr>
                                    </table>
                                </div>`;                    
                    $('#surat-pindah-detail-content').html(html);
                });
});

$('.btn-ubah-file-pindah').on('click',function(e){
    e.preventDefault();
    let form = $(this).parents('.form-row').siblings();
    $(this).toggleClass('btn-warning');
    $(this).toggleClass('btn-danger');
    
    if($(this).hasClass('btn-warning')){
        $(this).html('Ubah File');
    }else{
        $(this).html('Batalkan');
    }
    form.toggleClass('d-none').addClass('mt-3 mb-5');
});

$('.btn-pendaftaran-cuti-detail').on('click',function(e){
    e.preventDefault();
    $('#pendaftaran-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let pendaftaran = result;
                    let label;
                    if(pendaftaran.status == 'diajukan'){
                        label = `<label class="badge badge-gradient-warning text-dark">${pendaftaran.status.ucwords()}</label>`;
                    }else if(pendaftaran.status == 'ditolak'){
                        label = `<label class="badge badge-gradient-danger">${pendaftaran.status.ucwords()}</label>`;
                    }else{
                        label = `<label class="badge badge-gradient-info">${pendaftaran.status.ucwords()}</label>`;
                    }
                    let html = `<div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>NIM</th>
                                            <td>${pendaftaran.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${pendaftaran.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Tahun Akademik</th>
                                            <td>${pendaftaran.waktu_cuti.tahun_akademik.tahun_akademik} - ${pendaftaran.waktu_cuti.tahun_akademik.semester.ucwords()}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Alasan Cuti</th>
                                            <td>${pendaftaran.alasan_cuti}</td>
                                        </tr>
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${pendaftaran.created_at}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Permohonan Cuti</th>
                                            <td>
                                                <a href="${pendaftaran.file_surat_permohonan_cuti}" class="btn btn-info btn-sm" data-lightbox="${pendaftaran.nama_file_surat_permohonan_cuti}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File KRS Sebelumnya</th>
                                            <td>
                                                <a href="${pendaftaran.file_krs_sebelumnya}" class="btn btn-info btn-sm" data-lightbox="${pendaftaran.nama_file_krs_sebelumnya}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Slip UKT</th>
                                            <td>
                                                <a href="${pendaftaran.file_slip_ukt}" class="btn btn-info btn-sm" data-lightbox="${pendaftaran.nama_file_slip_ukt}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>`;
                    $('#pendaftaran-detail-content').html(html);
                });
});

let terima = $('.btn-terima');
terima.on('click',function(e){
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "Pendaftaran cuti diterima",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Terima',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            if (result.value) {
                let form = $(this).parents('form');
                form.submit();
            }
        }
    })
});

let tolak = $('.btn-tolak');
tolak.on('click', function(e){
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "pendaftaran cuti akan ditolak",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            if (result.value) {
                Swal.fire({
                    title: 'Keterangan',
                    input: 'textarea',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Cancel',
                    inputPlaceholder: 'Keterangan...',
                    inputAttributes: {
                      'aria-label': 'Keterangan...'
                    },
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value.trim() === undefined || value.trim() == null || value.length <= 0) {
                                resolve('Keterangan wajib diisi.')
                            } else {
                                console.log(value);
                                $('#keterangan_surat').val(value);
                                let form = $(this).parents('form');
                                form.submit();
                            }
                        })
                    }
                })
            }
        }
    })
});

$('.btn-surat-pengantar-cuti-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-pengantar-cuti-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let cuti = result;
                    let pendaftaranCuti = '';
                    if(cuti.waktu_cuti.pendaftaran_cuti.length > 0){
                        cuti.waktu_cuti.pendaftaran_cuti.forEach((daftarCuti) => {
                            pendaftaranCuti += `<tr>
                                                    <td>${daftarCuti.mahasiswa.nama}</td>
                                                    <td>${daftarCuti.mahasiswa.nim}</td>
                                                    <td>${daftarCuti.mahasiswa.prodi.strata} - ${daftarCuti.mahasiswa.prodi.nama_prodi}</td>
                                                    <td>${daftarCuti.alasan_cuti}</td>
                                                </tr>`;
                        });
                    }                    
                    let html = `<div class="row">
                                    <div class="col-4">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th>Nomor Surat</th>
                                                    <td>${cuti.nomor_surat}</td>
                                                </tr>
                                                <tr>
                                                    <th>Di Tandatangani Oleh</th>
                                                    <td>${cuti.user.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jumlah Cetak</th>
                                                    <td>${cuti.jumlah_cetak}</td>
                                                </tr>
                                                <tr>
                                                    <th>Di Buat</th>
                                                    <td>${cuti.created_at}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th>Daftar Mahasiswa</th>
                                                    <td>
                                                        <table class="table">
                                                            <tr>
                                                                <td>Nama</td>
                                                                <td>NIM</td>
                                                                <td>Prodi</td>
                                                                <td>Keterangan</td>
                                                            </tr>
                                                            ${pendaftaranCuti}
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        <div>
                                    </div>
                                </div> `
                    $('#surat-pengantar-cuti-detail-content').html(html);
                });
});

$('.btn-surat-pengantar-beasiswa-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-pengantar-beasiswa-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let beasiswa = result;
                    let mahasiswa = '';
                    let label;
                    if(beasiswa.status == 'menunggu tanda tangan'){
                        label = `<label class="badge badge-gradient-warning text-dark">${beasiswa.status.ucwords()}</label>`;
                    }else{
                        label = `<label class="badge badge-gradient-info">${beasiswa.status.ucwords()}</label>`;
                    }
                    if(beasiswa.mahasiswa.length > 0){
                        beasiswa.mahasiswa.forEach((mhs) => {
                            mahasiswa += `<tr>
                                                    <td>${mhs.nama}</td>
                                                    <td>${mhs.nim}</td>
                                                    <td>${mhs.prodi.strata} - ${mhs.prodi.nama_prodi}</td>
                                                    <td>${mhs.prodi.jurusan.nama_jurusan}</td>
                                                </tr>`;
                        });
                    }
                    
                    let html = `<div class="row">
                                    <div class="col-4">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th>Nomor Surat</th>
                                                    <td>${beasiswa.nomor_surat}</td>
                                                </tr>
                                                <tr>
                                                    <th>Perihal</th>
                                                    <td>${beasiswa.surat_masuk.perihal}</td>
                                                </tr>
                                                <tr>
                                                    <th>Diajukan Oleh</th>
                                                    <td>${beasiswa.kasubag.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Di Tandatangani Oleh</th>
                                                    <td>${beasiswa.user.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>${label}</td>
                                                </tr>
                                                <tr>
                                                    <th>Jumlah Cetak</th>
                                                    <td>${beasiswa.jumlah_cetak}</td>
                                                </tr>
                                                <tr>
                                                    <th>File Surat Masuk</th>
                                                    <td>
                                                        <a href="${beasiswa.link_file}" class="btn btn-info btn-sm" data-lightbox="${beasiswa.nama_file}">
                                                        <i class="mdi mdi mdi-eye"></i>
                                                        Lihat File Surat Masuk</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Di Buat</th>
                                                    <td>${beasiswa.created_at}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th>Daftar Mahasiswa</th>
                                                    <td>
                                                        <table class="table">
                                                            <tr>
                                                                <td>Nama</td>
                                                                <td>NIM</td>
                                                                <td>Prodi</td>
                                                                <td>Jurusan</td>
                                                            </tr>
                                                            ${mahasiswa}
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        <div>
                                    </div>
                                </div> `
                    $('#surat-pengantar-beasiswa-detail-content').html(html);
                });
});

$('.btn-surat-kegiatan-mahasiswa-progress').on('click', function (e) {
    e.preventDefault();
    $('#surat-progress-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let pengajuanSurat = result;
            let html = '';
            if(pengajuanSurat.status == 'diajukan'){
                html = `<div class="row">
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 9%" aria-valuenow="9" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                        </div>`;
            }else if(pengajuanSurat.status == 'diterima'){
                html = `<div class="row">
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_diterima}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Diterima</small></p> 
                            </div>
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                        </div>`;
            }else if(pengajuanSurat.status == 'ditolak'){
                html = `<div class="row">
                            <div class="col-6 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p>
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-danger mx-auto position-progress-round"></div>
                                <div class="bg-gradient-danger mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-6 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-center"></div>
                            <div class="col-6 text-center">
                                <div class="bg-gradient-danger mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-danger mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.updated_at}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-close-circle icon-sm text-danger"></i>
                                <small>Ditolak</small></p> 
                            </div>
                        </div>`;
            }else if(pengajuanSurat.status == 'disposisi dekan'){
                html = `<div class="row">
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Dekan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_dekan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_diterima}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Diterima</small></p> 
                            </div>
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                        </div>`;
            } else if(pengajuanSurat.status == 'disposisi wakil dekan I'){
                html = `<div class="row">
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Dekan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_dekan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_diterima}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Diterima</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_1}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Disposisi Wakil Dekan I</small></p> 
                            </div>
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                        </div>`;
            } else if(pengajuanSurat.status == 'disposisi wakil dekan II'){
                html = `<div class="row">
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Dekan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_dekan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Wakil Dekan II</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_2}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width:61%" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_diterima}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Diterima</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_1}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Disposisi Wakil Dekan I</small></p> 
                            </div>
                            <div class="col-3 text-center"></div>
                            <div class="col-3 text-center"></div>
                        </div>`;
            } else if(pengajuanSurat.status == 'disposisi wakil dekan III'){
                html = `<div class="row">
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Dekan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_dekan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Wakil Dekan II</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_2}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center"></div>
                            <div class="col-1 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width:73%" aria-valuenow="73" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_diterima}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Diterima</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_1}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Disposisi Wakil Dekan I</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_3}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Disposisi Wakil Dekan III</small></p> 
                            </div>
                            <div class="col-3 text-center"></div>
                        </div>`;
            } else if(pengajuanSurat.status == 'menunggu tanda tangan'){
                html = `<div class="row">
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Dekan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_dekan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Wakil Dekan II</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_2}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-border-color icon-sm text-info"></i>
                                    <small>Menunggu Tanda Tangan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_tanda_tangan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width:87%" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_diterima}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Diterima</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_1}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Disposisi Wakil Dekan I</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_3}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Disposisi Wakil Dekan III</small></p> 
                            </div>
                            <div class="col-3 text-center"></div>
                        </div>`;
            }else if(pengajuanSurat.status == 'selesai'){
                html = `<div class="row">
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Diajukan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.created_at}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Dekan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_dekan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    <small>Disposisi Wakil Dekan II</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_2}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                            <div class="col-2 text-center">
                                <p class="h6 m-0 mb-1 text-dark p-sm">
                                    <i class="mdi mdi-border-color icon-sm text-info"></i>
                                    <small>Menunggu Tanda Tangan</small>
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_tanda_tangan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-1 text-center"></div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width:100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_diterima}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Diterima</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_1}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Disposisi Wakil Dekan I</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_disposisi_wakil_dekan_3}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Disposisi Wakil Dekan III</small></p> 
                            </div>
                            <div class="col-3 text-center">
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_selesai}</small></p>
                                <p class="h6 mt-1 text-dark">
                                <i class="mdi mdi mdi-marker-check icon-sm text-info"></i>
                                <small>Selesai</small></p> 
                            </div>
                        </div>`;
            }
            $('#surat-progress-content').html(html);
        })
});

let terimaKegiatan = $('.btn-terima-kegiatan');
terimaKegiatan.on('click',function(e){
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "Pengajuan surat kegiatan mahasiswa diterima",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Terima',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            if (result.value) {
                let form = $(this).parents('form');
                form.submit();
            }
        }
    })
});

let disposisi = $('.disposisi');
disposisi.on('click',function(e){
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "Surat akan di disposisi",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Disposisi',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            if (result.value) {
                Swal.fire({
                    title: 'Catatan',
                    input: 'textarea',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Cancel',
                    inputPlaceholder: 'Catatan...',
                    inputAttributes: {
                      'aria-label': 'Catatan...'
                    },
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value.trim() === undefined || value.trim() == null || value.length <= 0) {
                                resolve('Catatan wajib diisi.')
                            } else {
                                console.log(value);
                                $('#catatan_disposisi').val(value);
                                let form = $(this).parents('form');
                                form.submit();
                            }
                        })
                    }
                })
            }
        }
    })
});


$('.btn-disposisi-detail').on('click',function(e){
    e.preventDefault();
    $('#disposisi-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let disposisi = result;
                    let content='';
                    disposisi.forEach((d)=>{
                        content += `<tr>
                                        <td>${d.nama}</td>
                                        <td>${d.pivot.catatan}</td>
                                    </tr>`;
                        
                    });
                    let html = `
                                    <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th>Diteruskan</th>
                                                    <th>Disposisi</th>
                                                </tr>
                                                ${content}
                                            </table>
                                </div>`
                    $('#disposisi-detail-content').html(html);
                });
});

let namaOrmawaOld = '{ ORMAWA }';
$('.ormawa-list').on('change',function(){
    let namaOrmawa = $('.ormawa-list option:selected').text();
    let replace = document.getElementsByClassName('replace');
    
    for (let form of replace) {
        let temp = form.value.toString();
        let newValue = temp.replace(namaOrmawaOld,namaOrmawa);
        
        form.value = newValue;
        form.previousSibling.children[2].children[0].innerHTML = newValue;
    }

    namaOrmawaOld = namaOrmawa;
});
 
$('.btn-pengajuan-surat-lulus-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-keterangan-lulus-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratLulus = result;
            let label;
            if(suratLulus.status == 'diajukan' || suratLulus.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratLulus.status.ucwords()}</label>`;
            }else if(suratLulus.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratLulus.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratLulus.status.ucwords()}</label>`;
            }
            let html = `<div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>NIM</th>
                                            <td>${suratLulus.mahasiswa.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${suratLulus.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>${suratLulus.mahasiswa.prodi.strata} - ${suratLulus.mahasiswa.prodi.nama_prodi}</td>
                                        </tr>
                                        <tr>
                                            <th>Jurusan</th>
                                            <td>${suratLulus.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>
                                        <tr>
                                            <th>IPK</th>
                                            <td>${suratLulus.ipk}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratLulus.keterangan}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Rekomendasi Jurusan</th>
                                            <td>
                                                <a href="${suratLulus.file_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${suratLulus.nama_file_rekomendasi_jurusan}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Berita Acara Ujian</th>
                                            <td>
                                                <a href="${suratLulus.file_berita_acara_ujian}" class="btn btn-info btn-sm" data-lightbox="${suratLulus.file_berita_acara_ujian}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>   
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${suratLulus.created_at}</td>
                                        </tr>
                                    </table>
                                </div>`;
            $('#surat-keterangan-lulus-detail-content').html(html);
        });
});

$('.btn-surat-lulus').on('click',function(e){
    $('#surat-progress-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let pengajuanSurat = result;
            if(pengajuanSurat.status == 'diajukan'){
                html = `<div class="row">
                            <div class="col-4 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-4 text-center"></div>
                            <div class="col-4 text-center"></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 33.3%" aria-valuenow="33.3" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-center"></div>
                                <div class="col-4 text-center"></div>
                                <div class="col-4 text-center"></div>
                            </div>
                        </div>`;
            }else if(pengajuanSurat.status == 'menunggu tanda tangan'){
                html = `<div class="row">
                            <div class="col-4 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-4 text-center"></div>
                            <div class="col-4 text-center"></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 66.6%" aria-valuenow="66.6   " aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-center"></div>
                                <div class="col-4 text-center">
                                    <div class="bg-gradient-info mx-auto position-progress-pole"></div>
                                    <div class="bg-gradient-info mx-auto position-progress-round"></div>
                                    <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_tunggu_tanda_tangan}</small></p>
                                    <p class="h6 mt-1 text-dark">
                                        <i class="mdi mdi-border-color icon-sm text-info"></i>
                                        Menunggu Tanda Tangan
                                    </p> 
                                </div>
                                <div class="col-4 text-center"></div>
                            </div>
                        </div>`;
            }else if(pengajuanSurat.status == 'selesai'){
                html = `<div class="row">
                            <div class="col-4 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-success"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-success mx-auto position-progress-round"></div>
                                <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-4 text-center"></div>
                            <div class="col-4 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-success"></i>
                                    Selesai
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_selesai}</small></p>
                                <div class="bg-gradient-success mx-auto position-progress-round"></div>
                                <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                            </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 100%" aria-valuenow="100   " aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-center"></div>
                                <div class="col-4 text-center">
                                    <div class="bg-gradient-success mx-auto position-progress-pole"></div>
                                    <div class="bg-gradient-success mx-auto position-progress-round"></div>
                                    <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_tunggu_tanda_tangan}</small></p>
                                    <p class="h6 mt-1 text-dark">
                                        <i class="mdi mdi-border-color icon-sm text-success"></i>
                                        Menunggu Tanda Tangan
                                    </p> 
                                </div>
                                <div class="col-4 text-center"></div>
                            </div>
                        </div>`;
            }else if(pengajuanSurat.status == 'ditolak'){
                html = `<div class="row">
                            <div class="col-6 text-center">
                                <p class="h6 m-0 mb-1 text-dark">
                                    <i class="mdi mdi-marker-check icon-sm text-info"></i>
                                    Diajukan
                                </p> 
                                <p class="text-muted mb-2"><small>${pengajuanSurat.tanggal_diajukan}</small></p>
                                <div class="bg-gradient-danger mx-auto position-progress-round"></div>
                                <div class="bg-gradient-danger mx-auto position-progress-pole"></div>
                            </div>
                            <div class="col-6 text-center"></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 100%" aria-valuenow="100   " aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-center"></div>
                                <div class="col-6 text-center">
                                    <div class="bg-gradient-danger mx-auto position-progress-pole"></div>
                                    <div class="bg-gradient-danger mx-auto position-progress-round"></div>
                                    <p class="text-muted mt-2 mb-0"><small>${pengajuanSurat.tanggal_ditolak}</small></p>
                                    <p class="h6 mt-1 text-dark">
                                        <i class="mdi mdi mdi-close-circle icon-sm text-danger"></i>
                                        Di Tolak
                                    </p> 
                                </div>
                            </div>
                        </div>`;
            }
        $('#surat-progress-content').html(html);
    })
});

$('.btn-surat-lulus-detail').on('click',function(e){
    $('#surat-keterangan-lulus-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratLulus = result.pengajuan_surat_keterangan_lulus;
            let label;
            if(suratLulus.status == 'diajukan' || suratLulus.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratLulus.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-info">${suratLulus.status.ucwords()}</label>`;
            }
            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Nomor Surat</th>
                                    <td>${result.nomor_surat}</td>
                                </tr>
                                <tr>
                                    <th>Di Tandangani Oleh</th>
                                    <td>${result.user.nama}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Cetak</th>
                                    <td>${result.jumlah_cetak}</td>
                                </tr>
                                <tr>
                                    <th>NIM</th>
                                    <td>${suratLulus.mahasiswa.nim}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>${suratLulus.mahasiswa.nama}</td>
                                </tr>
                                <tr>
                                    <th>Program Studi</th>
                                    <td>${suratLulus.mahasiswa.prodi.strata} - ${suratLulus.mahasiswa.prodi.nama_prodi}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td>${suratLulus.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                </tr>
                                <tr>
                                    <th>IPK</th>
                                    <td>${suratLulus.ipk}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>${label}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>${suratLulus.keterangan}</td>
                                </tr>
                                <tr>
                                    <th>File Surat Rekomendasi Jurusan</th>
                                    <td>
                                        <a href="${result.file_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${result.nama_file_rekomendasi_jurusan}">
                                        <i class="mdi mdi mdi-eye"></i>
                                        Lihat File</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>File Berita Acara Ujian</th>
                                    <td>
                                        <a href="${result.file_berita_acara_ujian}" class="btn btn-info btn-sm" data-lightbox="${result.file_berita_acara_ujian}">
                                        <i class="mdi mdi mdi-eye"></i>
                                        Lihat File</a>
                                    </td>
                                </tr>   
                                <tr>
                                    <th>Di Buat</th>
                                    <td>${result.created_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#surat-keterangan-lulus-detail-content').html(html);
        });
});

$('.btn-pengajuan-surat-material-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-material-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratMaterial = result;
            let label = '';
            let daftarKelompok = '';
            if(suratMaterial.status == 'diajukan' || suratMaterial.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratMaterial.status.ucwords()}</label>`;
            }else if(suratMaterial.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratMaterial.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratMaterial.status.ucwords()}</label>`;
            }
            
            if(suratMaterial.daftar_kelompok.length > 0){
                suratMaterial.daftar_kelompok.forEach((mhs)=>{
                    daftarKelompok += `<tr>
                                           <td>${mhs.nim}</td>
                                           <td>${mhs.nama}</td>
                                       </tr>`;
                });
            }
            let html = `<div class="row">
                            <div class="col-5">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Nama Kegiatan</th>
                                            <td>${suratMaterial.nama_kegiatan}</td>
                                        </tr>
                                        <tr>
                                            <th>Kepada</th>
                                            <td>${suratMaterial.kepada}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kelompok</th>
                                            <td>${suratMaterial.nama_kelompok}</td>
                                        </tr>
                                        <tr>
                                            <th>Diajukan Oleh</th>
                                            <td>${suratMaterial.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratMaterial.keterangan}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Rekomendasi Jurusan</th>
                                            <td>
                                                <a href="${result.file_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${suratMaterial.nama_file_rekomendasi_jurusan}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${suratMaterial.created_at}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Daftar kelompok</th>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <td>NIM</td>
                                                            <td>Nama</td>
                                                        </tr>
                                                        ${daftarKelompok}
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>`;
            $('#surat-material-detail-content').html(html);
        });
});

$('.btn-surat-material-detail').on('click',function(e){
    $('#surat-material-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratMaterial = result.pengajuan_surat_permohonan_pengambilan_material;
            
            let label = '';
            let daftarKelompok = '';
            if(suratMaterial.status == 'diajukan' || suratMaterial.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratMaterial.status.ucwords()}</label>`;
            }else if(suratMaterial.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratMaterial.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratMaterial.status.ucwords()}</label>`;
            }
            
            if(suratMaterial.daftar_kelompok.length > 0){
                suratMaterial.daftar_kelompok.forEach((mhs)=>{
                    daftarKelompok += `<tr>
                                           <td>${mhs.nim}</td>
                                           <td>${mhs.nama}</td>
                                       </tr>`;
                });
            }
            let html = `<div class="row">
                            <div class="col-5">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Nomor Surat</th>
                                            <td>${result.nomor_surat}</td>
                                        </tr>
                                        <tr>
                                            <th>Ditanda Tangani Oleh</th>
                                            <td>${result.user.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Cetak</th>
                                            <td>${result.jumlah_cetak}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kegiatan</th>
                                            <td>${suratMaterial.nama_kegiatan}</td>
                                        </tr>
                                        <tr>
                                            <th>Kepada</th>
                                            <td>${suratMaterial.kepada}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Kelompok</th>
                                            <td>${suratMaterial.nama_kelompok}</td>
                                        </tr>
                                        <tr>
                                            <th>Diajukan Oleh</th>
                                            <td>${suratMaterial.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratMaterial.keterangan}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Rekomendasi Jurusan</th>
                                            <td>
                                                <a href="${result.file_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${result.nama_file_rekomendasi_jurusan}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr> 
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${result.created_at}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-7">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Daftar kelompok</th>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <td>NIM</td>
                                                            <td>Nama</td>
                                                        </tr>
                                                        ${daftarKelompok}
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>`;
            $('#surat-material-detail-content').html(html);
        });
});