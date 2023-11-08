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
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="{{ route('dashboard') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../assets/images/logos/favicon.png" width="180" alt="">
                </a>
                <h1 class="text-center">Register</h1>
                <form id="register-form">
                  <div class="mb-3">
                    <label for="exampleInputtext1" class="form-label">Họ và tên</label>
                    <input type="text" name="full_name" class="form-control" id="exampleInputtext1">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đăng ký</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Bạn đã có tài khoản?</p>
                    <a class="text-primary fw-bold ms-2" href="{{ route('loginView') }}">Đăng nhập</a>
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
              $('#sign-up-button').prop('disabled', true).text('Loading...');
              // Hàm hiển thị toast lỗi
              function showErrorToast(errorMessage) {
                  var toast = $('#error-toast');
                  toast.find('.iq-alert-text').text(errorMessage);
                  toast.show();

                  setTimeout(function () {
                      toast.hide();
                  }, 3000); // 3 giây
              }

              var full_name = $('input[name="full_name"]').val();
              var email = $('input[name="email"]').val();
              var password = $('input[name="password"]').val();

              // Tạo mảng lưu trữ thông báo lỗi
              var errors = [];

              if (!full_name) {
                  errors.push('Please enter your full name.');
              }
              if (!email) {
                  errors.push('Please enter your email.');
              } else if (!isValidEmail(email)) {
                  errors.push('Please enter a valid email address.');
              }
              if (!password) {
                  errors.push('Please enter your password.');
              } else if (!isValidPassword(password)) {
                  errors.push('Password must be at least 8 characters, containing both uppercase and lowercase letters, and a number.');
              }

              if (errors.length > 0) {
                  errors.forEach(function (error) {
                      showErrorToast(error);
                  });
              } else {
                  var formData = $(this).serialize();

                  $.ajax({
                      type: 'POST',
                      url: '/admin/register',
                      data: formData,
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                          'Accept': 'application/json',
                      },
                      success: function (response) {
                          $('#sign-up-button').prop('disabled', false).text('Sign Up');
                          if (response.status === 'success') {
                              window.location.href = '/admin/confirm-email?email='+ email;
                          } else {
                              showErrorToast('There was an error during the registration process. Please try again!');
                          }
                      },
                      error: function (xhr) {
                          if (xhr.status === 422) {
                              var errors = xhr.responseJSON.errors;
                              var errorMessage = '';
                              for (var error in errors) {
                                  errorMessage += errors[error][0] + '\n';
                              }
                              showErrorToast(errorMessage);
                          } else {
                              alert('There was an error during the registration process. Please try again!');
                          }
                      },
                  });
              }
          });

          function isValidEmail(email) {
              var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
              return emailPattern.test(email);
          }

          function isValidPassword(password) {
              var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
              return passwordPattern.test(password);
          }
      });
  </script>
</body>

</html>
