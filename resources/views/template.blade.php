<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('image/logo/logo_ung.png') }}" type="image">
    <title>SPAK | Sistem Pengelolaan Administrasi Kemahasiswaan</title>
    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-css.css') }}">
</head>

<body>
    @yield('content')
    {{-- JavaScript --}}
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/misc.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script>
    <script src="{{ asset('vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/form-upload.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    @if(Session::has('info') && Session::has('info-title'))
    <script>
        infoMessage("{{ Session::get('info-title') }}","{{ Session::get('info') }}");
    </script>
    @endif
    @if(Session::has('success') && Session::has('success-title'))
    <script>
        successMessage("{{ Session::get('success-title') }}","{{ Session::get('success') }}");
    </script>
    @endif
    @if(Session::has('success-timer') && Session::has('success-timer-title'))
    <script>
        successMessageTimer("{{ Session::get('success-timer-title') }}","{{ Session::get('success-timer') }}");
    </script>
    @endif
    @if(Session::has('error') && Session::has('error-title'))
    <script>
        errorMessage("{{ Session::get('error-title') }}","{{ Session::get('error') }}");
    </script>
    @endif
</body>

</html>