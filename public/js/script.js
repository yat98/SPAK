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
    let label = $('#username-id');
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
    label.html(value.toUpperCase())
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
            let mahasiswa = result[0];
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
                        tableStatus+=`<td><label class="badge badge-gradient-warning">${status.pivot.status.ucwords()}</label><td></tr>`;
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
tolakSurat.on('click',function(e){
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
                let form = $(this).parents('form');
                form.submit();
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
                                                Lihat File Surat</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>`;
                    $('#surat-masuk-detail-content').html(html);
                });
})

lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'disableScrolling':true
});

$('.btn-surat-masuk-edit').on('click',function(e){
    // e.preventDefault();
    // let html = `<label for="file_surat_masuk">File Surat Masuk *(Ukuran File &lt; 1MB)</label>
    //             <input class="file-upload-default" id="file_surat_masuk" name="file_surat_masuk" type="file">
    //             <div class="input-group col-xs-12">
    //                 <input class="form-control file-upload-info" placeholder="Upload File Surat Masuk" disabled="disabled" name="" type="text">
    //                 <span class="input-group-append">
    //                     <button class="file-upload-browse btn btn-gradient-success" type="button">Upload</button>
    //                 </span>
    //             </div>`;
    // $('.surat-masuk-file').empty();
    // $('.surat-masuk-file').html(html);
})