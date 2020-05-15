<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('image/dummy_user_man') }}">
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column overflow-hidden">
                    <span class="font-weight-bold mb-2">{{ ucwords(Session::get('username')) }}</span>
                    <span class="text-secondary text-small" >
                        {{ ($posisi == 'admin') ? 'Admin':'' }}
                        {{ ($posisi == 'mahasiswa') ? 'Mahasiswa':'' }}
                        {!! ($posisi == 'pegawai') ? '<small>'.ucwords(Session::get('jabatan')).'</small>':'' !!}
                        @if(Session::get('jabatan') == 'dekan')
                        <small>Dekan</small>
                        @elseif(Session::get('jabatan') == 'wd1')
                        <small>Wakil Dekan I</small>
                        @elseif(Session::get('jabatan') == 'wd2')
                        <small>Wakil Dekan II</small>
                        @elseif(Session::get('jabatan') == 'wd3')
                        <small>Wakil Dekan III</small>
                        @endif
                    </span>
                </div>
            </a>
        </li>
        @if ($posisi == 'admin')
        <li class="nav-item {{ ($halaman == 'dashboard-admin') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'jurusan') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/jurusan') }}">
                <span class="menu-title">Jurusan</span>
                <i class="mdi mdi-bank menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'program-studi') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/program-studi') }}">
                <span class="menu-title">Program Studi</span>
                <i class="mdi mdi mdi-book-multiple menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'tahun-akademik') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/tahun-akademik') }}">
                <span class="menu-title">Tahun Akademik</span>
                <i class="mdi mdi mdi-calendar-text menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'mahasiswa' || $halaman == 'user') ? 'active':'' }}">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Pengguna</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ ($halaman == 'mahasiswa') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/mahasiswa') }}">Mahasiswa</a></li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'user') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/user') }}">User</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ ($halaman == 'status-mahasiswa') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/status-mahasiswa') }}">
                <span class="menu-title">Status Mahasiswa</span>
                <i class="mdi mdi mdi-checkbox-multiple-marked menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'ormawa') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/ormawa') }}">
                <span class="menu-title">Ormawa</span>
                <i class="mdi mdi mdi-check-decagram menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'pimpinan-ormawa') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/pimpinan-ormawa') }}">
                <span class="menu-title">Pimpinan Ormawa</span>
                <i class="mdi mdi mdi mdi-account-multiple menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'profil') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/profil') }}">
                <span class="menu-title">Ubah Profil</span>
                <i class="mdi mdi mdi mdi-settings menu-icon"></i>
            </a>
        </li>
        @elseif($posisi == 'pegawai')
        <li class="nav-item {{ ($halaman == 'dashboard-pegawai') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'kode-surat') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/kode-surat') }}">
                <span class="menu-title">Kode Surat</span>
                <i class="mdi mdi mdi-format-list-numbered menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'tanda-tangan') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/tanda-tangan') }}">
                <span class="menu-title">Tanda Tangan</span>
                <i class="mdi mdi mdi mdi-border-color menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'surat-keterangan-aktif-kuliah' || $halaman == 'surat-keterangan-kelakuan-baik' || $halaman == 'surat-dispensasi' || $halaman == 'surat-masuk' || $halaman == 'surat-rekomendasi' || $halaman == 'surat-tugas' || $halaman == 'surat-persetujuan-pindah' || $halaman == 'surat-pengantar-cuti' ||  $halaman == 'surat-pengantar-beasiswa') ? 'active':'' }}">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">
                    Surat
                </span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-file-document-box menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ ($halaman == 'surat-masuk') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-masuk') }}">
                            Surat Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ ($halaman == 'surat-keterangan-aktif-kuliah') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-keterangan-aktif-kuliah') }}">
                            Surat Keterangan <br> Aktif Kuliah
                        </a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-keterangan-kelakuan-baik') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-keterangan-kelakuan-baik') }}">
                            Surat Keterangan <br> Kelakuan Baik
                        </a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-dispensasi') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-dispensasi') }}">Surat Dispensasi</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-rekomendasi') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-rekomendasi') }}">Surat Rekomendasi</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-tugas') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-tugas') }}">Surat Tugas</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-persetujuan-pindah') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-persetujuan-pindah') }}">Surat Persetujuan Pindah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ ($halaman == 'surat-pengantar-cuti') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-pengantar-cuti') }}">Surat Pengantar Cuti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ ($halaman == 'surat-pengantar-beasiswa') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-pengantar-beasiswa') }}">Surat Pengantar Beasiswa</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ ($halaman == 'waktu-cuti') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/waktu-cuti') }}">
                <span class="menu-title">Waktu Cuti</span>
                <i class="mdi mdi mdi mdi-clock menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'pendaftaran-cuti') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/pendaftaran-cuti') }}">
                <span class="menu-title">Pendaftaran Cuti</span>
                <i class="mdi mdi mdi mdi mdi-playlist-check menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'profil') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/profil') }}">
                <span class="menu-title">Ubah Profil</span>
                <i class="mdi mdi mdi mdi-settings menu-icon"></i>
            </a>
        </li>
        @elseif($posisi == 'mahasiswa')
        <li class="nav-item {{ ($halaman == 'dashboard-mahasiswa') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'surat-keterangan-aktif-kuliah' || $halaman == 'surat-keterangan-kelakuan-baik' || $halaman == 'surat-dispensasi' || $halaman == 'surat-rekomendasi' || $halaman == 'surat-tugas' || $halaman == 'surat-persetujuan-pindah') ? 'active':'' }}">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">
                    Surat
                </span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-file-document-box menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ ($halaman == 'surat-keterangan-aktif-kuliah') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/pengajuan/surat-keterangan-aktif-kuliah') }}">
                            Surat Keterangan <br> Aktif Kuliah
                        </a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-keterangan-kelakuan-baik') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/pengajuan/surat-keterangan-kelakuan-baik') }}">Surat Keterangan <br> Kelakuan Baik</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-dispensasi') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-dispensasi') }}">Surat Dispensasi</a>
                    </li>
                     <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-rekomendasi') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-rekomendasi') }}">Surat Rekomendasi</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-tugas') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-tugas') }}">Surat Tugas</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-persetujuan-pindah') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/pengajuan/surat-persetujuan-pindah') }}">Surat Persetujuan Pindah</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ ($halaman == 'pendaftaran-cuti') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/pendaftaran-cuti') }}">
                <span class="menu-title">Pendaftaran Cuti</span>
                <i class="mdi mdi mdi mdi mdi-playlist-check menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'profil') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/profil') }}">
                <span class="menu-title">Ubah Profil</span>
                <i class="mdi mdi mdi mdi-settings menu-icon"></i>
            </a>
        </li>
        @elseif($posisi == 'pimpinan')
        <li class="nav-item {{ ($halaman == 'dashboard-pimpinan') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'tanda-tangan') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/tanda-tangan') }}">
                <span class="menu-title">Tanda Tangan</span>
                <i class="mdi mdi mdi mdi-border-color menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ ($halaman == 'surat-keterangan-aktif-kuliah' || $halaman == 'surat-keterangan-kelakuan-baik' || $halaman == 'surat-dispensasi' || $halaman == 'surat-rekomendasi' || $halaman == 'surat-tugas' || $halaman == 'surat-persetujuan-pindah' || $halaman == 'surat-pengantar-beasiswa') ? 'active':'' }}">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">
                    Surat
                </span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-file-document-box menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-dispensasi') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-dispensasi') }}">Surat Dispensasi</a>
                    </li>
                     <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-rekomendasi') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-rekomendasi') }}">Surat Rekomendasi</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-tugas') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-tugas') }}">Surat Tugas</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ ($halaman == 'surat-persetujuan-pindah') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-persetujuan-pindah') }}">Surat Persetujuan Pindah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ ($halaman == 'surat-pengantar-beasiswa') ? 'active':'' }}" href="{{ asset(Request::segment(1).'/surat-pengantar-beasiswa') }}">Surat Pengantar Beasiswa</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ ($halaman == 'profil') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/profil') }}">
                <span class="menu-title">Ubah Profil</span>
                <i class="mdi mdi mdi mdi-settings menu-icon"></i>
            </a>
        </li>
        @endif
    </ul>
</nav>