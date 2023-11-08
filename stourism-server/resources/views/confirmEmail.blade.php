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
                            <h1 class="mb-0">Confirm Email</h1>
                            <p>You have successfully registered your account, please access your email to get your email confirmation code</p>
                            <form class="mt-4" id="confirm-email-form">

                                <div class="form-group mb-5">
                                    <label for="exampleInputEmail1">Receive email confirmation code</label>
                                    <input type="password" name="active_key" class="form-control mb-0" id="exampleInputEmail1" placeholder="Enter confirmation code">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Xác nhận</button>
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
            var errors = [];

            if (!token) {
                errors.push('Please enter confirmation code.');
            }

            if (token.length !== 6) {
                errors.push('Your verification code is invalid, please re-enter!');
            }

            if (errors.length > 0) {
                errors.forEach(function (error) {
                    showErrorToast(error);
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: '/admin/confirm-email',
                    data: {
                        active_key: token,
                        email: email,
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
                            showErrorToast('There was an error during the login process. Please try again!');
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
                            alert('There was an error during the login process. Please try again!');
                        }
                    },
                });
            }
        });
    });
</script>
</body>

</html>
