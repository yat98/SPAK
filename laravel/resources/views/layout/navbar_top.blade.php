<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="" href="{{ url('/') }}">
            <img src="{{ asset('image/logo/logo_ung.png') }}" class="logo-size-custom" alt="logo" />
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
            @if (Session::get('jenis_user') != 'admin')
            @if (isset($notifikasi) && isset($countNotifikasi))
            @if ($countNotifikasi > 0)
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count-symbol bg-danger"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">Notifikasi</h6>
                    <div class="dropdown-divider"></div>
                    @foreach ($notifikasi->take(5) as $notif)
                    <a class="dropdown-item preview-item" href="{{url($posisi.'/notifikasi/'.$notif->id)}}">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-info">
                                <i class="mdi mdi mdi-bell-ring"></i>
                            </div>
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">{{$notif->judul_notifikasi}}</h6>
                            <p class="text-gray ellipsis mb-0"> {{$notif->isi_notifikasi}}
                            </p>
                        </div>
                    </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <h6 class="p-3 mb-0 text-center">
                        <a href="{{ url($posisi.'/notifikasi') }}" class="text-dark">
                            Lihat Semua Notifikasi ({{$countNotifikasi}})
                        </a>
                    </h6>
                </div>
                @else
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">Notifikasi</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item p-4">
                        <div class="row">
                            <div class="col-12 text-center">
                                <img src="{{ asset('image/no_notifikasi.svg')}}" class="illustration-no-notification text-center">
                            </div>
                            <div class="col-12 text-center">
                                <p class="text-black text-center h4 mt-4 mx-5">
                                    Notifikasi Kosong!
                                </p>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="p-3 mb-0 text-center">
                        <a href="{{ url($posisi.'/notifikasi') }}" class="text-dark">
                            Lihat Semua Notifikasi
                        </a>
                    </h6>
                </div>
                @endif
                @endif
            </li>
            @endif
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown"
                    aria-expanded="false">
                    <div class="nav-profile-img">
                        <img src="{{ asset('image/dummy_user_man') }}" alt="image">
                        <span class="availability-status online"></span>
                    </div>
                    <div class="nav-profile-text">
                        <p class="mb-1 text-black">{{ (Session::get('jenis_user') != 'admin') ? ucwords(Auth::user()->nama) : ucwords(Auth::user()->username) }}</p>
                    </div>
                </a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="
                    {{ url($posisi.'/logout') }}
                    ">
                        <i class="mdi mdi-logout mr-2 text-primary"></i> Logout </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>