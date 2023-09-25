<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modernize Free</title>
  <link rel="shortcut icon" type="image/png" href="/assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="/assets/css/styles.min.css" />
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    @include('component.sidebar')
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
        @include('component.header')
      <!--  Header End -->
      <div class="container-fluid">
        <!--  Row 1 -->
        @yield('content')
      </div>
    </div>
  </div>
  <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/sidebarmenu.js"></script>
  <script src="/assets/js/app.min.js"></script>
  <script src="/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="/assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="/assets/js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>

</html>
