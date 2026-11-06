<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Envsogo</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Fonts (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')
    <link href="{{ asset('css/tokens.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('soft-ui/assets/css/soft-ui-dashboard.min.css') }}">

    @yield('style')

</head>
<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <div class="content-wrappe">

            <section class="content">
                
                @yield('content')
            </section>
        </div>

        
    </div>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery-3.7.1.slim.js') }}"></script>
    <!-- Bootstrap 4 -->
    @yield('script')
</body>

</html>
