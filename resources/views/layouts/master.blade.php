<!DOCTYPE html>
<html lang="en">

<!--head start-->
@include('layouts._head')
    <!--head end-->
    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
      <div class="wrapper">
      
        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
          <img class="animation__wobble" src="assetsdist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div> --}}

    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
   {{-- @include('layouts._header') --}}
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
   {{-- @include('layouts._menu') --}}
    <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
     @yield('content')
    <!--main content end-->
    {{-- <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      <a class="btn btn-danger"  href="{{route('Logout')}}">Déconnexion</a>
    </aside> --}}
    <!--footer start-->
{{-- @include('layouts._footer') --}}
    <!--footer end-->
  
  <!--script-->
</div>

    @include('layouts._script')
  <!--script end-->
</body>

</html>
