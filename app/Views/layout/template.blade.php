<!doctype html>
<html lang="en"> 
<head> 
  
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'> --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="APLIKASI LS 2023">
    <meta name="author" content="Dev Team">
    <meta name="keywords" content="Surveyor, Laporan Survoyor, Surveyor Report">
    <?= csrf_meta() ?>

    <!-- TITLE -->
    @if (isset($pageTitle))
        <title>{{$pageTitle}}</title>
    @else
        <title>APLIKASI LS - PT ATQ</title>
    @endif   

    <link rel="shortcut icon" type="image/png" href="{{ base_url('assets/images/logos/atq-ico.ico') }}"/> 
    
    <!-- STYLE CSS -->
    <link id="style" href="{{ base_url('assets/css/styles.min.css') }}" rel="stylesheet" /> 
    <link id="style" href="{{ base_url('assets/css/style.addition.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link id="style" href="{{ base_url('assets/css/icons.css') }}" rel="stylesheet" />
    <link id="style" href="{{ base_url('css/appls.css') }}" rel="stylesheet" />

    <!-- Datatables -->
    <link id="style" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link id="style" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css" rel="stylesheet" />

    <!-- Bootstrap-Date  Picker js-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" integrity="sha512-x2MVs84VwuTYP0I99+3A4LWPZ9g+zT4got7diQzWB4bijVsfwhNZU2ithpKaq6wTuHeLhqJICjqE6HffNlYO7w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker3.min.css" integrity="sha512-Kzco+Sw7jh2LkHgAe/l4N6tTVn9n4BGbytazdb9CtGM0n3b1oh3t7/J3u4ZAm8GlaJ4o4pbB63aZCuVHWpq9Rw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- select2 -->
    <link id="style" href="{{ base_url('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" /> 

    @isset($addCSS)
        {!! $addCSS !!}
    @endisset

</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <!-- Sidebar Start --> 
    @php
        $roleInternal = [3,4,6,7];  
    @endphp 

    @if(in_array(session()->get('sess_role'), $roleInternal)) 
        @include('layout.navbar') 
    @elseif(session()->get('sess_role') == 10) 
        @include('layout.navbar-client')
    @else 
        @include('layout.navbar')  
    @endif
    <!--  Sidebar End -->

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      @include('layout.header')
      <!--  Header End -->
 
      <div class="container-fluid">  
        @isset($content)
            {!! $content !!}
        @endisset 
      </div>

    </div>
  </div>

    <!-- JQUERY JS -->
    <script src="{{ base_url('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- <script src="{{ base_url('assets/js/jquery.min.js') }}"></script> -->

    <!-- BOOTSTRAP JS -->
    {{-- <script src="{{ base_url('assets/plugins/bootstrap/js/popper.min.js') }}"></script> --}}
    <script src="{{ base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ base_url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script> --}}

    <!-- SIDE-MENU JS -->
    <script src="{{ base_url('assets/js/sidebarmenu.js') }}"></script> 
    <!-- <script src="{{ base_url('assets/plugins/sidemenu/sidemenu.js') }}"></script> -->

    <script src="{{ base_url('assets/js/app.min.js') }}"></script>

    <script src="{{ base_url('assets/libs/simplebar/dist/simplebar.js') }}"></script>

    <!-- STICKY JS -->
    {{-- <script src="{{ base_url('assets/js/sticky.js') }}"></script> --}}
 
    <!-- Bootstrap-Date Range Picker js-->
    <script src="{{ base_url('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>

    <!-- jQuery UI Date Picker js -->
    <script src="{{ base_url('assets/plugins/date-picker/jquery-ui.js') }}"></script>

    <!-- Sweet-alert js  --> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script src="https://cdn.jsdelivr.net/gh/mgalante/jquery.redirect@master/jquery.redirect.js"></script>
  
    <!-- COLOR THEME JS -->
    <script src="{{ base_url('assets/js/themeColors.js') }}"></script>
 
    <!-- CUSTOM JS -->
    {{-- <script src="{{ base_url('assets/js/custom.js') }}"></script> --}}

    <!-- CUSTOM JS -->
    <script src="{{ base_url('/js/global/init.js') }}"></script>
    <script src="{{ base_url('/js/global/helpers.js') }}"></script>

    <script src="{{ base_url('assets/plugins/numberaja/numberaja.js') }}"></script>
    
    <script src="{{ base_url('assets/plugins/notify/js/jquery.growl.js') }}"></script>
    <script src="{{ base_url('assets/plugins/notify/js/notifIt.js') }}"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/jquery-easy-loading@2.0.0-rc.2/dist/jquery.loading.min.js"></script>

    <script src="{{ base_url('assets/plugins/select2/select2.min.js') }}"></script> 
    
    @isset($addJS)
        {!! $addJS !!}
    @endisset

    <script>
        const baseurl = "{{ base_url() }}";
        const siteurl = "{{ site_url() }}";
    </script>
</body>

</html>