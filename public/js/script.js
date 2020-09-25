let bahasa = {
    "sProcessing":   '<i class="mdi mdi-sync menu-icon rotate"></i>',
    "sLengthMenu":   "Tampilan _MENU_ data",
    "sZeroRecords":  "Data tidak ditemukan",
    "sInfo":         "Tampilan _START_ sampai _END_ dari _TOTAL_ data",
    "sInfoEmpty":    "Tampilan 0 hingga 0 dari 0 data",
    "sInfoFiltered": "(disaring dari _MAX_ data keseluruhan)",
    "sInfoPostFix":  "",
    'searchPlaceholder': 'Cari...',
    'sSearch': '',
    "sUrl":          "",
    "oPaginate": {
        "sFirst":    "Awal",
        "sPrevious": "Balik",
        "sNext":     "Lanjut",
        "sLast":     "Akhir"
    }
};

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
    console.log($(this).val());
    switch ($(this).val()) {
        case 'pimpinan':
            value = 'nip';
            break;
        case 'mahasiswa':
            value = 'nim';
            break;
        case 'pegawai':
            value = 'nip';
            break;
        case 'operator':
            value = 'username';
            username.attr('placeholder', value.ucwords());
            return;
    }
    username.attr('placeholder', value.toUpperCase());
});

$('.table-responsive').on('click','.sweet-delete', function (e) {
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

$('.table-responsive').on('click','.btn-verification', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Yakin?',
        text: "Surat akan diverifikasi!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            let form = $(this).parents('form');
            form.submit();
        }
    })
})

// $(window).resize(() => {
//     removePositionPagination(mediaQuery);
// }).trigger('resize');

$('.btn-upload').on('click', showProgress);

$('.table').on('click','.btn-detail', function (e) {
    e.preventDefault();
    $('#mahasiswa-detail-content').empty();
    $('#surat-keterangan-aktif-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let mahasiswa = result;
            let tableStatus = '';
            
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
                tableStatus = '<label class="badge badge-gradient-dark">Data Status Mahasiswa Belum Ada</label>';
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
                                    <td>${mahasiswa.ipk}</td>
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

$('.table-responsive').on('click','.btn-surat-detail', function (e) {
    e.preventDefault();
    $('#surat-keterangan-aktif-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratDetail = result;
            let label = '';

            if (suratDetail.status == 'Selesai'){
                label=`<label class="badge badge-gradient-info">${suratDetail.status}</label>`;
            }else if (suratDetail.status == 'Ditolak'){
                label=`<label class="badge badge-gradient-danger">${suratDetail.status}</label>`;
            }else{
                label=`<label class="badge badge-gradient-warning text-dark">${suratDetail.status}</label>`;
            }

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
                                    <td>${suratDetail.nomor_surat}</td>
                                </tr>
                                <tr>
                                    <th>Kode Surat</th>
                                    <td>${suratDetail.kode_surat.kode_surat}</td>
                                </tr>
                                <tr>
                                    <th>Tahun</th>
                                    <td>${suratDetail.tahun}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <td>${suratDetail.jenis_surat}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>${label}</td>
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
                                    <td>${suratDetail.created_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#surat-keterangan-aktif-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.simpan-tanda-tangan',function(e){
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

$('.table-responsive').on('click', '.tolak-surat', function(e){
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

$('.table-responsive').on('click','.btn-surat-progress', function (e) {
    e.preventDefault();
    $('#surat-progress-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let pengajuanSurat = result;
            let progressPercent = '0';
            let bgColor = 'bg-gradient-info';
            let icon = `<i class="mdi mdi-marker-check icon-sm text-info"></i>`;


            switch (pengajuanSurat.status) {
                case 'Selesai':
                    progressPercent = '100';
                    bgColor = 'bg-gradient-success';
                    icon = `<i class="mdi mdi-marker-check icon-sm text-success"></i>`;
                    break;
                case 'Diajukan':
                    progressPercent = '20';
                    break;
                case 'Verifikasi Kasubag':
                    progressPercent = '40';
                    break;
                case 'Verifikasi Kabag':
                    progressPercent = '60';
                    break;
                case 'Menunggu Tanda Tangan':
                    progressPercent = '80';
                    break;
                case 'Ditolak':
                    progressPercent = '100';
                    bgColor = 'bg-gradient-danger';
                    icon = `<i class="mdi mdi-close-circle icon-sm text-danger"></i>`
                    break;
            }

            let html = `<div class="row">
                            <div class="col-12 mt-2">
                                <p class="text-center text-muted mb-0">${progressPercent}%</p>
                                <div class="progress">
                                    <div class="progress-bar mt-0 ${bgColor} " role="progressbar" style="width: ${progressPercent}%" aria-valuenow="${progressPercent}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <p class="h6 m-0 mb-1 text-dark text-center">
                                    ${icon}
                                    ${pengajuanSurat.status}
                                </p> 
                                <p class="text-muted text-center mt-2 mb-0"><small>${pengajuanSurat.tanggal}</small></p>
                            </div>
                        </div>`;
            $('#surat-progress-content').html(html);
        })
        .catch(() => {
            errorMessage('Terjadi Kesalahan','Periksa koneksi anda kemudian refresh browser anda');
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

$('.table-responsive').on('click','.btn-surat-masuk-detail',function(e){
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
                                            <td><b>Di Buat</b></td>
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
                                <input class="form-control" id="tahapan_kegiatan" placeholder="Tahapan Kegiatan" name="tahapan_kegiatan[]" type="text">
                            </div>
                            <div class="col-md-6 mb-1">
                                <input class="form-control" id="tempat_kegiatan" placeholder="Tempat Kegiatan" name="tempat_kegiatan[]" type="text">
                            </div>
                            <div class="col-md-3">
                                <input class="tanggal form-control" id="tanggal_awal_kegiatan" placeholder="Tanggal Awal Kegiatan" name="tanggal_awal_kegiatan[]" type="text" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <input class="tanggal form-control" id="tanggal_akhir_kegiatan" placeholder="Tanggal Akhir Kegiatan" name="tanggal_akhir_kegiatan[]" type="text" autocomplete="off">
                            </div>
                        </div>`);
});


$('.table-responsive').on('click','.btn-pengajuan-surat-dispensasi-detail',function(e){
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

                    if (suratDispensasi.status == 'Selesai'){
                        label=`<label class="badge badge-gradient-info">${suratDispensasi.status}</label>`;
                    }else if (suratDispensasi.status == 'Ditolak'){
                        label=`<label class="badge badge-gradient-danger">${suratDispensasi.status}</label>`;
                    }else{
                        label=`<label class="badge badge-gradient-warning text-dark">${suratDispensasi.status}</label>`;
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
                                                    <th>Nama Kegiatan</th>
                                                    <td>${suratDispensasi.nama_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Diajukan Oleh</th>
                                                    <td>${suratDispensasi.operator.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        ${label}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Dibuat</th>
                                                    <td>${suratDispensasi.dibuat}</td>
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
                                            </table>
                                        </div>
                                    </div>
                                </div>`;
                    $('#surat-dispensasi-detail-content').html(html);
                });
});

$('.table-responsive').on('click','.btn-surat-dispensasi-detail',function(e){
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
                    
                    if (suratDispensasi.status == 'Selesai'){
                        label=`<label class="badge badge-gradient-info">${suratDispensasi.status}</label>`;
                    }else if (suratDispensasi.status == 'Ditolak'){
                        label=`<label class="badge badge-gradient-danger">${suratDispensasi.status}</label>`;
                    }else{
                        label=`<label class="badge badge-gradient-warning text-dark">${suratDispensasi.status}</label>`;
                    }

                    suratDispensasi.pengajuan_surat_dispensasi.mahasiswa.forEach((mahasiswa) => {
                        tableMahasiswa+=`<tr>
                                            <td>${mahasiswa.nim}</td>
                                            <td>${mahasiswa.nama}</td>
                                            <td>${mahasiswa.prodi.strata} - ${mahasiswa.prodi.nama_prodi}</td>
                                            <td>${mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>`;
                    });
                    suratDispensasi.pengajuan_surat_dispensasi.tahapan_kegiatan_dispensasi.forEach((tahapan,key)=>{
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
                                                    <th>Nama Kegiatan</th>
                                                    <td>${suratDispensasi.pengajuan_surat_dispensasi.nama_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Nomor Surat</th>
                                                    <td>${suratDispensasi.nomor_surat}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kode Surat</th>
                                                    <td>${suratDispensasi.kode_surat.kode_surat}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tahun</th>
                                                    <td>${suratDispensasi.tahun}</td>
                                                </tr>
                                                <tr>
                                                    <th>Diajukan Oleh</th>
                                                    <td>${suratDispensasi.pengajuan_surat_dispensasi.operator.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        ${label}
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
                                            </table>
                                        </div>
                                    </div>
                                </div>`;
                    $('#surat-dispensasi-detail-content').html(html);
                });
});

$('.table-responsive').on('click','.btn-pengajuan-surat-rekomendasi-detail',function(e){
    e.preventDefault();
    $('#surat-rekomendasi-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let suratRekomendasi = result;                    
                    let tableMahasiswa = '';
                    let label;

                    if (suratRekomendasi.status == 'Selesai'){
                        label=`<label class="badge badge-gradient-info">${suratRekomendasi.status}</label>`;
                    }else if (suratRekomendasi.status == 'Ditolak'){
                        label=`<label class="badge badge-gradient-danger">${suratRekomendasi.status}</label>`;
                    }else{
                        label=`<label class="badge badge-gradient-warning text-dark">${suratRekomendasi.status}</label>`;
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
                                                    <th>Diajukan Oleh</th>
                                                    <td>${suratRekomendasi.operator.nama}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        ${label}
                                                    </td>
                                                </tr> 
                                                <tr>
                                                    <th>Dibuat</th>
                                                    <td>${suratRekomendasi.dibuat}</td>
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
                                            </table>
                                        </div>
                                    </div>
                                </div>`;
                    $('#surat-rekomendasi-detail-content').html(html);
                });
});

$('.table-responsive').on('click','.btn-surat-rekomendasi-detail',function(e){
    e.preventDefault();
    $('#surat-rekomendasi-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
                .then(response => response.json())
                .then(result => {
                    let suratRekomendasi = result;                    
                    let tableMahasiswa = '';
                    let label;

                    if (suratRekomendasi.status == 'Selesai'){
                        label=`<label class="badge badge-gradient-info">${suratRekomendasi.status}</label>`;
                    }else if (suratRekomendasi.status == 'Ditolak'){
                        label=`<label class="badge badge-gradient-danger">${suratRekomendasi.status}</label>`;
                    }else{
                        label=`<label class="badge badge-gradient-warning text-dark">${suratRekomendasi.status}</label>`;
                    }

                    suratRekomendasi.pengajuan_surat_rekomendasi.mahasiswa.forEach((mahasiswa) => {
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
                                                    <th>Nama Kegiatan</th>
                                                    <td>${suratRekomendasi.pengajuan_surat_rekomendasi.nama_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Nomor Surat</th>
                                                    <td>${suratRekomendasi.nomor_surat}</td>
                                                </tr>
                                                <tr>
                                                    <th>Kode Surat</th>
                                                    <td>${suratRekomendasi.kode_surat.kode_surat}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tahun</th>
                                                    <td>${suratRekomendasi.tahun}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Kegiatan</th>
                                                    <td>${suratRekomendasi.tanggal}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tempat Kegiatan</th>
                                                    <td>${suratRekomendasi.pengajuan_surat_rekomendasi.tempat_kegiatan}</td>
                                                </tr>
                                                <tr>
                                                    <th>Diajukan Oleh</th>
                                                    <td>${suratRekomendasi.pengajuan_surat_rekomendasi.operator.nama}</td>
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
                                                    <th>Status</th>
                                                    <td>
                                                        ${label}
                                                    </td>
                                                </tr> 
                                                <tr>
                                                    <th>Dibuat</th>
                                                    <td>${suratRekomendasi.dibuat}</td>
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
                                            </table>
                                        </div>
                                    </div>
                                </div>`;
                    $('#surat-rekomendasi-detail-content').html(html);
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

$('.btn-pengajuan-surat-survei-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-survei-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratSurvei = result;
            let label;
            if(suratSurvei.status == 'diajukan' || suratSurvei.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratSurvei.status.ucwords()}</label>`;
            }else if(suratSurvei.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratSurvei.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratSurvei.status.ucwords()}</label>`;
            }
            let html = `<div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>NIM</th>
                                            <td>${suratSurvei.mahasiswa.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${suratSurvei.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>${suratSurvei.mahasiswa.prodi.strata} - ${suratSurvei.mahasiswa.prodi.nama_prodi}</td>
                                        </tr>
                                        <tr>
                                            <th>Jurusan</th>
                                            <td>${suratSurvei.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>
                                        <tr>
                                            <th>Kepada</th>
                                            <td>${suratSurvei.kepada}</td>
                                        </tr>
                                        <tr>
                                            <th>Mata Kuliah</th>
                                            <td>${suratSurvei.mata_kuliah}</td>
                                        </tr>
                                        <tr>
                                            <th>Data Survei</th>
                                            <td>${suratSurvei.data_survei}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratSurvei.keterangan}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Rekomendasi Jurusan</th>
                                            <td>
                                                <a href="${suratSurvei.file_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${suratSurvei.nama_file_rekomendasi_jurusan}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${suratSurvei.created_at}</td>
                                        </tr>
                                    </table>
                                </div>`;
            $('#surat-survei-detail-content').html(html);
        });
});

$('.btn-pengajuan-penelitian-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-penelitian-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratPenelitian = result;
            let label;
            if(suratPenelitian.status == 'diajukan' || suratPenelitian.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratPenelitian.status.ucwords()}</label>`;
            }else if(suratPenelitian.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratPenelitian.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratPenelitian.status.ucwords()}</label>`;
            }
            let html = `<div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>NIM</th>
                                            <td>${suratPenelitian.mahasiswa.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${suratPenelitian.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>${suratPenelitian.mahasiswa.prodi.strata} - ${suratPenelitian.mahasiswa.prodi.nama_prodi}</td>
                                        </tr>
                                        <tr>
                                            <th>Jurusan</th>
                                            <td>${suratPenelitian.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>
                                        <tr>
                                            <th>Kepada</th>
                                            <td>${suratPenelitian.kepada}</td>
                                        </tr>
                                        <tr>
                                            <th>Judul</th>
                                            <td>${suratPenelitian.judul}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratPenelitian.keterangan}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Rekomendasi Jurusan</th>
                                            <td>
                                                <a href="${suratPenelitian.file_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${suratPenelitian.nama_file_rekomendasi_jurusan}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${suratPenelitian.created_at}</td>
                                        </tr>
                                    </table>
                                </div>`;
            $('#surat-penelitian-detail-content').html(html);
        });
});

$('.btn-pengajuan-surat-data-awal-detail').on('click',function(e){
    e.preventDefault();
    $('#surat-data-awal-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratDataAwal = result;
            let label;
            if(suratDataAwal.status == 'diajukan' || suratDataAwal.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratDataAwal.status.ucwords()}</label>`;
            }else if(suratDataAwal.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratDataAwal.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratDataAwal.status.ucwords()}</label>`;
            }
            let html = `<div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>NIM</th>
                                            <td>${suratDataAwal.mahasiswa.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${suratDataAwal.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>${suratDataAwal.mahasiswa.prodi.strata} - ${suratDataAwal.mahasiswa.prodi.nama_prodi}</td>
                                        </tr>
                                        <tr>
                                            <th>Jurusan</th>
                                            <td>${suratDataAwal.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>
                                        <tr>
                                            <th>Kepada</th>
                                            <td>${suratDataAwal.kepada}</td>
                                        </tr>
                                        <tr>
                                            <th>Tempat Pengambilan Data</th>
                                            <td>${suratDataAwal.tempat_pengambilan_data}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratDataAwal.keterangan}</td>
                                        </tr>
                                        <tr>
                                            <th>File Surat Rekomendasi Jurusan</th>
                                            <td>
                                                <a href="${suratDataAwal.file_rekomendasi_jurusan}" class="btn btn-info btn-sm" data-lightbox="${suratDataAwal.nama_file_rekomendasi_jurusan}">
                                                <i class="mdi mdi mdi-eye"></i>
                                                Lihat File</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Di Buat</th>
                                            <td>${suratDataAwal.created_at}</td>
                                        </tr>
                                    </table>
                                </div>`;
            $('#surat-data-awal-detail-content').html(html);
        });
});

$('.btn-surat-survei-detail').on('click',function(e){
    $('#surat-survei-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratSurvei = result.pengajuan_surat_permohonan_survei;
            let label;
            if(suratSurvei.status == 'diajukan' || suratSurvei.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratSurvei.status.ucwords()}</label>`;
            }else if(suratSurvei.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratSurvei.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratSurvei.status.ucwords()}</label>`;
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
                                            <td>${suratSurvei.mahasiswa.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${suratSurvei.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>${suratSurvei.mahasiswa.prodi.strata} - ${suratSurvei.mahasiswa.prodi.nama_prodi}</td>
                                        </tr>
                                        <tr>
                                            <th>Jurusan</th>
                                            <td>${suratSurvei.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>
                                        <tr>
                                            <th>Kepada</th>
                                            <td>${suratSurvei.kepada}</td>
                                        </tr>
                                        <tr>
                                            <th>Mata Kuliah</th>
                                            <td>${suratSurvei.mata_kuliah}</td>
                                        </tr>
                                        <tr>
                                            <th>Data Survei</th>
                                            <td>${suratSurvei.data_survei}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratSurvei.keterangan}</td>
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
                                </div>`;
            $('#surat-survei-detail-content').html(html);
        });
});

$('.btn-surat-penelitian-detail').on('click',function(e){
    $('#surat-penelitian-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratPenelitian = result.pengajuan_surat_rekomendasi_penelitian;
            let label;
            if(suratPenelitian.status == 'diajukan' || suratPenelitian.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratPenelitian.status.ucwords()}</label>`;
            }else if(suratPenelitian.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratPenelitian.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratPenelitian.status.ucwords()}</label>`;
            }
            let html = `<div class="table-responsive">
                                    <table class="table">
                                    <tr>
                                        <th>Nomor Surat</th>
                                        <td>${result.nomor_surat}</td>
                                    </tr>
                                    <tr>
                                        <th>Tembusan</th>
                                        <td>${result.tembusan}</td>
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
                                        <td>${suratPenelitian.mahasiswa.nim}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <td>${suratPenelitian.mahasiswa.nama}</td>
                                    </tr>
                                    <tr>
                                        <th>Program Studi</th>
                                        <td>${suratPenelitian.mahasiswa.prodi.strata} - ${suratPenelitian.mahasiswa.prodi.nama_prodi}</td>
                                    </tr>
                                    <tr>
                                        <th>Jurusan</th>
                                        <td>${suratPenelitian.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                    </tr>
                                        <tr>
                                            <th>Kepada</th>
                                            <td>${suratPenelitian.kepada}</td>
                                        </tr>
                                        <tr>
                                            <th>Judul</th>
                                            <td>${suratPenelitian.judul}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratPenelitian.keterangan}</td>
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
                                </div>`;
            $('#surat-penelitian-detail-content').html(html);
        });
});

$('.btn-surat-data-awal-detail').on('click',function(e){
    $('#surat-data-awal-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratDataAwal = result.pengajuan_surat_permohonan_pengambilan_data_awal;
            let label;
            if(suratDataAwal.status == 'diajukan' || suratDataAwal.status == 'menunggu tanda tangan'){
                label = `<label class="badge badge-gradient-warning text-dark">${suratDataAwal.status.ucwords()}</label>`;
            }else if(suratDataAwal.status == 'selesai'){
                label = `<label class="badge badge-gradient-info">${suratDataAwal.status.ucwords()}</label>`;
            }else{
                label = `<label class="badge badge-gradient-danger">${suratDataAwal.status.ucwords()}</label>`;
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
                                            <td>${suratDataAwal.mahasiswa.nim}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <td>${suratDataAwal.mahasiswa.nama}</td>
                                        </tr>
                                        <tr>
                                            <th>Program Studi</th>
                                            <td>${suratDataAwal.mahasiswa.prodi.strata} - ${suratDataAwal.mahasiswa.prodi.nama_prodi}</td>
                                        </tr>
                                        <tr>
                                            <th>Jurusan</th>
                                            <td>${suratDataAwal.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                        </tr>
                                        <tr>
                                            <th>Kepada</th>
                                            <td>${suratDataAwal.kepada}</td>
                                        </tr>
                                        <tr>
                                            <th>Tempat Pengambilan Data</th>
                                            <td>${suratDataAwal.tempat_pengambilan_data}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>${label}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>${suratDataAwal.keterangan}</td>
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
                                </div>`;
            $('#surat-data-awal-detail-content').html(html);
        });
});

$('.table-responsive').on('click','.user-detail', function (e) {
    e.preventDefault();
    $('#user-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let user = result;
            let tableStatus = '';
            
            if (user.status_aktif == 'Aktif'){
                tableStatus+=`<label class="badge badge-gradient-info">${user.status_aktif}</label>`;
            }else{
                tableStatus+=`<label class="badge badge-gradient-dark">${user.status_aktif}</label>`;
            }
            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>NIP</th>
                                    <td>${user.nip}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>${user.nama}</td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td>${user.jabatan}</td>
                                </tr>
                                <tr>
                                    <th>Pangkat</th>
                                    <td>${user.pangkat}</td>
                                </tr>
                                <tr>
                                    <th>Golongan</th>
                                    <td>${user.golongan}</td>
                                </tr>
                                <tr>
                                    <th>Status Aktif</th>
                                    <td>${tableStatus}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${user.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${user.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#user-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.jurusan-detail', function (e) {
    e.preventDefault();
    $('#jurusan-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let jurusan = result;

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Nama Jurusan</th>
                                    <td>${jurusan.nama_jurusan}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${jurusan.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${jurusan.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#jurusan-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.prodi-detail', function (e) {
    e.preventDefault();
    $('#prodi-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let prodi = result;

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Nama Program Studi</th>
                                    <td>${prodi.nama_prodi}</td>
                                </tr>
                                <tr>
                                    <th>Strata</th>
                                    <td>${prodi.strata}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td>${prodi.nama_jurusan}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${prodi.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${prodi.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#prodi-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.tahun-akademik-detail', function (e) {
    e.preventDefault();
    console.log('a');
    $('#tahun-akademik-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let tahunAkademik = result;
            let tableStatus = '';
            
            if (tahunAkademik.status_aktif == 'Aktif'){
                tableStatus+=`<label class="badge badge-gradient-info">${tahunAkademik.status_aktif}</label>`;
            }else{
                tableStatus+=`<label class="badge badge-gradient-dark">${tahunAkademik.status_aktif}</label>`;
            }

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Tahun Akademik</th>
                                    <td>${tahunAkademik.tahun_akademik}</td>
                                </tr>
                                <tr>
                                    <th>Semester</th>
                                    <td>${tahunAkademik.semester}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>${tableStatus}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${tahunAkademik.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${tahunAkademik.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#tahun-akademik-detail-content').html(html);
        });
})


$('.table-responsive').on('click','.status-mahasiswa-detail', function (e) {
    e.preventDefault();
    console.log('a');
    $('#status-mahasiswa-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let statusMahasiswa = result;
            let tableStatus = '';
            
            if (statusMahasiswa.status == 'Aktif'){
                tableStatus+=`<label class="badge badge-gradient-info">${statusMahasiswa.status}</label>`;
            }else if(statusMahasiswa.status == 'Lulus'){
                tableStatus+=`<label class="badge badge-gradient-success">${statusMahasiswa.status}</label>`;
            }else if(statusMahasiswa.status == 'Drop Out' || statusMahasiswa.status == 'Keluar'){
                tableStatus+=`<label class="badge badge-gradient-danger">${status.pivot.status}</label>`;
            }else if(statusMahasiswa.status == 'Cuti'){
                tableStatus+=`<label class="badge badge-gradient-warning text-dark">${statusMahasiswa.status}</label>`;
            }else{
                tableStatus+=`<label class="badge badge-gradient-dark">${statusMahasiswa.status}</label>`;
            }

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>NIM</th>
                                    <td>${statusMahasiswa.mahasiswa.nim}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>${statusMahasiswa.mahasiswa.nama}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Akademik</th>
                                    <td>${statusMahasiswa.tahun_akademik.tahun_akademik}</td>
                                </tr>
                                <tr>
                                    <th>Semester</th>
                                    <td>${statusMahasiswa.tahun_akademik.semester.ucwords()}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>${tableStatus}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${statusMahasiswa.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${statusMahasiswa.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#status-mahasiswa-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.ormawa-detail', function (e) {
    e.preventDefault();
    $('#ormawa-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let ormawa = result;

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Nama Ormawa</th>
                                    <td>${ormawa.nama}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td>${ormawa.jurusan.nama_jurusan}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${ormawa.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${ormawa.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#ormawa-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.pimpinan-ormawa-detail', function (e) {
    e.preventDefault();
    $('#pimpinan-ormawa-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let pimpinanOrmawa = result;

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>NIM</th>
                                    <td>${pimpinanOrmawa.nim}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>${pimpinanOrmawa.mahasiswa.nama}</td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td>${pimpinanOrmawa.jabatan}</td>
                                </tr>
                                <tr>
                                    <th>Nama Ormawa</th>
                                    <td>${pimpinanOrmawa.ormawa.nama}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td>${pimpinanOrmawa.mahasiswa.prodi.jurusan.nama_jurusan}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${pimpinanOrmawa.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${pimpinanOrmawa.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#pimpinan-ormawa-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.operator-detail', function (e) {
    e.preventDefault();
    $('#operator-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let operator = result;
            let tableStatus = '';
            
            if (operator.status_aktif == 'Aktif'){
                tableStatus+=`<label class="badge badge-gradient-info">${operator.status_aktif}</label>`;
            }else{
                tableStatus+=`<label class="badge badge-gradient-dark">${operator.status_aktif}</label>`;
            }

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Nama</th>
                                    <td>${operator.nama}</td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td>${operator.username}</td>
                                </tr>
                                <tr>
                                    <th>Bagian</th>
                                    <td>${operator.bagian}</td>
                                </tr>
                                <tr>
                                    <th>Status Aktif</th>
                                    <td>${tableStatus}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${operator.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${operator.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#operator-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.kode-surat-detail', function (e) {
    e.preventDefault();
    $('#kode-surat-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let kodeSurat = result;
            let tableStatus = '';
            
            if (kodeSurat.status_aktif == 'Aktif'){
                tableStatus+=`<label class="badge badge-gradient-info">${kodeSurat.status_aktif}</label>`;
            }else{
                tableStatus+=`<label class="badge badge-gradient-dark">${kodeSurat.status_aktif}</label>`;
            }

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Kode Surat</th>
                                    <td>${kodeSurat.kode_surat}</td>
                                </tr>   
                                <tr>
                                    <th>Status Aktif</th>
                                    <td>${tableStatus}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${kodeSurat.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Diubah</th>
                                    <td>${kodeSurat.updated_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#kode-surat-detail-content').html(html);
        });
})

$('.table-responsive').on('click','.pengajuan-surat-keterangan-detail', function (e) {
    e.preventDefault();
    $('#surat-keterangan-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratKeterangan = result;
            let tableStatus = '';
            let diajukan = '';
            let html;
            
            if (suratKeterangan.status == 'Selesai'){
                tableStatus+=`<label class="badge badge-gradient-info">${suratKeterangan.status}</label>`;
            }else if (suratKeterangan.status == 'Ditolak'){
                tableStatus+=`<label class="badge badge-gradient-danger">${suratKeterangan.status}</label>`;
            }else{
                tableStatus+=`<label class="badge badge-gradient-warning text-dark">${suratKeterangan.status}</label>`;
            }

            if(suratKeterangan.id_operator == null){
                diajukan = suratKeterangan.mahasiswa.nama;
            }else{
                diajukan = suratKeterangan.operator.nama;
            }
            
            if(suratKeterangan.jenis_surat == 'Surat Keterangan Aktif Kuliah'){
                html = `<div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>NIM</th>
                                        <td>${suratKeterangan.mahasiswa.nim}</td>
                                    </tr>   
                                    <tr>
                                        <th>Nama</th>
                                        <td>${suratKeterangan.mahasiswa.nama}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Surat</th>
                                        <td>${suratKeterangan.jenis_surat}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Akademik</th>
                                        <td>${suratKeterangan.tahun_akademik.tahun_akademik} - ${suratKeterangan.tahun_akademik.semester.ucwords()}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>${tableStatus}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>${suratKeterangan.keterangan}</td>
                                    </tr>
                                    <tr>
                                        <th>Diajukan Oleh</th>
                                        <td>${diajukan}</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat</th>
                                        <td>${suratKeterangan.created_at}</td>
                                    </tr>
                                </table>
                            </div>`;                
            }else{
                html = `<div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>NIM</th>
                                        <td>${suratKeterangan.mahasiswa.nim}</td>
                                    </tr>   
                                    <tr>
                                        <th>Nama</th>
                                        <td>${suratKeterangan.mahasiswa.nama}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Surat</th>
                                        <td>${suratKeterangan.jenis_surat}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>${tableStatus}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>${suratKeterangan.keterangan}</td>
                                    </tr>
                                    <tr>
                                        <th>Diajukan Oleh</th>
                                        <td>${diajukan}</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat</th>
                                        <td>${suratKeterangan.created_at}</td>
                                    </tr>
                                </table>
                            </div>`;                    
            }
            $('#surat-keterangan-detail-content').html(html);
        });
});

$('.table-responsive').on('click','.surat-keterangan-detail', function (e) {
    e.preventDefault();
    $('#surat-keterangan-detail-content').empty();
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let suratKeterangan = result.pengajuan_surat_keterangan;
            let tableStatus = '';
            let diajukan = '';
            if (`result.status` == 'Selesai'){
                tableStatus+=`<label class="badge badge-gradient-info">${result.status}</label>`;
            }else if (suratKeterangan.status == 'Ditolak'){
                tableStatus+=`<label class="badge badge-gradient-danger">${result.status}</label>`;
            }else{
                tableStatus+=`<label class="badge badge-gradient-warning text-dark">${result.status}</label>`;
            }

            if(suratKeterangan.id_operator == null){
                diajukan = suratKeterangan.mahasiswa.nama;
            }else{
                diajukan = suratKeterangan.operator.nama;
            }

            let html = `<div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Nomor Surat</th>
                                    <td>${result.nomor_surat}/${result.kode_surat.kode_surat}</td>
                                </tr> 
                                <tr>
                                    <th>NIM</th>
                                    <td>${suratKeterangan.mahasiswa.nim}</td>
                                </tr>   
                                <tr>
                                    <th>Nama</th>
                                    <td>${suratKeterangan.mahasiswa.nama}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Surat</th>
                                    <td>${result.jenis_surat}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Akademik</th>
                                    <td>${suratKeterangan.tahun_akademik.tahun_akademik} - ${suratKeterangan.tahun_akademik.semester.ucwords()}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>${tableStatus}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>${suratKeterangan.keterangan}</td>
                                </tr>
                                <tr>
                                    <th>Diajukan Oleh</th>
                                    <td>${diajukan}</td>
                                </tr>
                                <tr>
                                    <th>Surat Dibuat Oleh</th>
                                    <td>${result.operator.nama}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>${result.created_at}</td>
                                </tr>
                            </table>
                        </div>`;
            $('#surat-keterangan-detail-content').html(html);
        });
});