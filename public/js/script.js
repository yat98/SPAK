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
    let url = $(this).attr('href');
    let a = fetch(url)
        .then(response => response.json())
        .then(result => {
            let mahasiswa = result[0];
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
                            </table>
                        </div>`;
            $('#mahasiswa-detail-content').html(html);
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
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'white',
    minWidth: 0.5,
    maxWidth:  3.5,
    penColor: "blue"
});

function resizeCanvas() {
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    signaturePad.clear();
}

window.onresize = resizeCanvas;
resizeCanvas();

var reset = document.getElementById("reset");
reset.addEventListener('click',function(e){
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