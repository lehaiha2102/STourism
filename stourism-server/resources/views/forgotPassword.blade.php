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
                            <h1 class="mb-2 text-center">Forgot Password</h1>
                            <p>Enter your email address and we'll send you an email with instructions to reset your password.</p>
                            <form class="mt-4" id="confirm-email-form">

                                <div class="form-group mb-4">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input type="email" name="email" class="form-control mb-0" id="exampleInputEmail1" placeholder="Enter email">
                                </div>
                                <a href="{{ route('admin.loginView') }}" type="button" class="btn btn-secondary w-100 py-8 fs-4 mb-4 rounded-2">Hủy</a>
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

            var email = $('input[name="email"]').val();
            var errors = [];

            if (!email) {
                errors.push('Please enter your email.');
            } else if (!isValidEmail(email)) {
                errors.push('Please enter a valid email address.');
            }
            if (errors.length > 0) {
                errors.forEach(function (error) {
                    showErrorToast(error);
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: '/admin/forgot-password',
                    data: {
                        email: email,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                    },
                    success: function (response) {
                        $('#sign-up-button').prop('disabled', false).text('Submit');
                        if (response.status === 'success') {
                            window.location.href = '/admin/reset-password?email='+email;
                        } else {
                            showErrorToast('There was an error during the forgot password process. Please try again!');
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
                            showErrorToast('There was an error during the forgot password process. Please try again!');
                        }
                    },
                });
            }
        });
        function isValidEmail(email) {
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            return emailPattern.test(email);
        }
    });
</script>
</body>

</html>
