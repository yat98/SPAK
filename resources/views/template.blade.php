<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('image/logo/logo_ung.png') }}" type="image">
    <title>SPAK | Sistem Pengelolaan Administrasi Kemahasiswaan</title>
    {{-- CSS --}}
    <link href="https://fonts.googleapis.com/css2?family=Tinos&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/jquery-datepicker/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/lightbox/css/lightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/fontawesome/font-awesome.min.css') }}">
    {{-- FROALA --}}
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/froala_editor.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/froala_style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/code_view.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/draggable.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/image_manager.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/image.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/line_breaker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/third_party/spell_checker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/special_characters.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/char_counter.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/quick_insert.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/help.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/froala/css/plugins/table.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/codemirror/codemirror.min.css') }}">
    {{-- END FROALA --}}
    <link rel="stylesheet" href="{{ asset('vendors/flipclock/css/flip-clock.css') }}">
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
    <script src="{{ asset('vendors/signatures-pad/js/signature_pad.umd.js') }}"></script>
    <script src="{{ asset('vendors/jquery-datepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('vendors/lightbox/js/lightbox.js') }}"></script>
    {{-- FROALA --}}
    <script src="{{ asset('vendors/codemirror/codemirror.min.js') }}"></script>
    <script src="{{ asset('vendors/codemirror/xml.min.js') }}"></script>  
    <script src="{{ asset('vendors/froala/js/froala_editor.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/align.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/code_beautifier.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/code_view.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/draggable.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/link.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/lists.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/paragraph_format.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/paragraph_style.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/table.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/url.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/entities.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/colors.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/font_family.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/font_size.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/file.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/char_counter.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/inline_style.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/quick_insert.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/help.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/third_party/spell_checker.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/special_characters.min.js') }}"></script>
    <script src="{{ asset('vendors/froala/js/plugins/word_paste.min.js') }}"></script>
    {{-- END FROALA --}}
    <script src="{{ asset('vendors/flipclock/js/flip-clock.js') }}"></script>
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
    @yield('timer-javascript')
    @yield('chart-javascript')
</body>

</html>