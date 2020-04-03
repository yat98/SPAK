<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('image/dummy_user_man') }}">
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ ucwords(Session::get('username')) }}</span>
                    <span class="text-secondary text-small">
                        {{ ($posisi == 'admin') ? 'Admin':'' }}
                    </span>
                </div>
            </a>
        </li>
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
        <li class="nav-item {{ ($halaman == 'profil') ? 'active':'' }}">
            <a class="nav-link" href="{{ url(Request::segment(1).'/profil') }}">
                <span class="menu-title">Ubah Profil</span>
                <i class="mdi mdi mdi mdi-settings menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>