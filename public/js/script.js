let jenisUser = document.getElementById('jenis_user');
if (jenisUser) {
    jenisUser.addEventListener('click', function () {
        let label = document.querySelector('label[for="username"]');
        let username = document.getElementById('username');
        switch (this.value) {
            case 'pimpinan':
                label.innerHTML = 'NIP';
                username.name = 'NIP';
                username.placeholder = 'NIP';
                break;
            case 'mahasiswa':
                label.innerHTML = 'NIM';
                username.name = 'NIM';
                username.placeholder = 'NIM';
                break;
            case 'pegawai':
                label.innerHTML = 'NIP';
                username.name = 'NIP';
                username.placeholder = 'NIP';
                break;
        }
    });

}
