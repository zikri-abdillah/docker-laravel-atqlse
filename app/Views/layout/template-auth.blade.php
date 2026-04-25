<!doctype html>
<html lang="en"> 
  <head> 
    <!-- META DATA -->
    <meta charset="UTF-8">
     <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
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

    <!-- select2 -->
    <link id="style" href="{{ base_url('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" /> 

    @isset($addCSS)
        {!! $addCSS !!}
    @endisset

</head> 
<body>  
    @isset($content)
        {!! $content !!}
    @endisset 

	<script>
      const baseurl = "{{ base_url() }}"
      const siteurl = "{{ site_url() }}"
    </script>
    
    <script src="{{ base_url('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ base_url() }}assets/auth/js/main.js"></script>
 
    <!-- CUSTOM JS --> 
    <script src="{{ base_url('/js/global/helpers.js') }}"></script>  
    <script src="{{ base_url('/js/auth/login.js') }}"></script>  
         
    <!-- Input-mask js  --> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 
  
    @isset($addJS)
        {!! $addJS !!}
    @endisset
</body> 
</html>