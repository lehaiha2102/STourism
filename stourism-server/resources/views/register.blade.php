<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modernize Free</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../assets/images/logos/dark-logo.svg" width="180" alt="">
                </a>
                <p class="text-center">Your Social Campaigns</p>
                <form id="register-form">
                  <div class="mb-3">
                    <label for="exampleInputtext1" class="form-label">Họ và tên</label>
                    <input type="text" name="full_name" class="form-control" id="exampleInputtext1">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputPhone1" class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" id="exampleInputPhone1">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword12" class="form-label">Nhập lại khẩu</label>
                    <input type="password" class="form-control" id="exampleInputPassword2">
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đăng ký</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Bạn đã có tài khoản?</p>
                    <a class="text-primary fw-bold ms-2" href="{{ route('login') }}">Đăng nhập</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function () {
        $('#register-form').on('submit', function (e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            
            $.ajax({
                type: 'POST',
                url: '/admin/dang-ky',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function (response) {
                    if(response.status === 'success'){
                        alert('Bạn đã đăng ký tài khoản thành công');
                        window.location.href = '/admin/dang-nhap';
                    } else{
                        alert('Vui lòng đăng ký lại');
                    }; 
                }
            });
        });
    });
</script>
</body>

</html>