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
                            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                <img src="../assets/images/logos/favicon.png" width="180" alt="">
                            </a>
                            <h1 class="text-center">Reset your password</h1>
                            <form id="confirm-email-form">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Receive email confirmation code</label>
                                    <input type="password" name="active_key" class="form-control mb-0" id="exampleInputEmail1" placeholder="Enter confirmation code">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail2">New password</label>
                                    <input type="password" name="password" class="form-control mb-0" id="exampleInputEmail2" placeholder="Enter new password">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Confirm new password</label>
                                    <input type="password" name="repassword" class="form-control mb-0" id="exampleInputEmail3" placeholder="Enter re-password">
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
        $('#confirm-email-form').on('submit', function (e) {
            e.preventDefault();

            function showErrorToast(errorMessage) {
                var toast = $('#error-toast');
                toast.find('.iq-alert-text').text(errorMessage);
                toast.show();

                setTimeout(function () {
                    toast.hide();
                }, 3000);
            }

            var currentURL = window.location.href;
            var emailParamIndex = currentURL.indexOf('email=');

            if (emailParamIndex !== -1) {
                var email = currentURL.slice(emailParamIndex + 6);
            } else {
                showErrorToast('Không tìm thấy tham số "email" trong URL.');
                return;
            }

            var token = $('input[name="active_key"]').val();
            var password = $('input[name="password"]').val();
            var repassword = $('input[name="repassword"]').val();

            var errors = [];
            if (!token) {
                errors.push('Please enter confirmation code.');
            }

            if (token.length !== 6) {
                errors.push('Your verification code is invalid, please re-enter!');
            }
            if (!password) {
                errors.push('Please enter your password.');
            } else if (!isValidPassword(password)) {
                errors.push('Password must be at least 8 characters, containing both uppercase and lowercase letters, and a number.');
            }
            if (!repassword) {
                errors.push('Please enter your re password.');
            } else if (!isValidPassword(repassword)) {
                errors.push('Re-Password must be at least 8 characters, containing both uppercase and lowercase letters, and a number.');
            }

            if(password != repassword){
                errors.push('The password you entered does not match, please re-enter.');
            }

            if (errors.length > 0) {
                errors.forEach(function (error) {
                    showErrorToast(error);
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: '/admin/reset-password',
                    data: {
                        active_key: token,
                        email: email,
                        password: password
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                    },
                    success: function (response) {
                        $('#sign-up-button').prop('disabled', false).text('Submit');
                        if (response.status === 'success') {
                            window.location.href = '/admin/login';
                        } else {
                            showErrorToast('There was an error during the reset password process. Please try again!');
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
                            alert('There was an error during the reset password process. Please try again!');
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
