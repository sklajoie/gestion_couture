<!DOCTYPE html>
<html lang="en">

<!--head start-->
@include('layouts._head')
    <!--head end-->
    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
      <div class="wrapper">
      
     @yield('content')
</div>

    @include('layouts._script')
  <!--script end-->
</body>

</html>
