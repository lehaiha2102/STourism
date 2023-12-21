@extends('component.index')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <div class="card"
                    style="background-image: url('{{ asset('../images/' . ($user->banner ? $user->banner : 'profile/pngtree-cartoon-tourism-beach-blue-image_14050.jpg')) }}'); background-size: cover;"
                    class="img-fluid avatar-xxl rounded-circle" alt="">
                    <div class="card-body pb-0">
                        <div class="row align-items-center">
                            <div class="col-md-3 justify-content-center align-items-center pb-4">
                                <div class="text-center">
                                    <img src="../images/{{ $user->avatar ? $user->avatar : '/profile/user-1.jpg' }}"
                                        class="img-fluid avatar-xxl rounded-circle" alt="">
                                </div>
                            </div><!-- end col -->
                            <div class="col-md-9">
                                <div class="ms-3">
                                    <div class="row my-4">
                                        <div class="col-md-12">
                                            <div>


                                            </div>
                                        </div><!-- end col -->
                                    </div><!-- end row -->


                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
                <div class="container mt-5">
                    <ul class="nav nav-tabs" id="myTabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#content1">Thông tin cá
                                nhân</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#content2">Mật khẩu</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-2">
                        <div class="tab-pane fade show active" id="content1">
                            <h3>Thông tin cá nhân</h3>
                            <div class="mb-4 row">
                                <div class="col-lg-3 col-md-3 col-3">
                                    <p class="">Họ và tên</p>
                                </div>
                                <div class="col-lg-9 col-md-9 col-9">
                                    <p class="">{{ $user->full_name }}</p>
                                </div>
                                <div class="col-lg-3 col-md-3 col-3">
                                    <p class="">Email</p>
                                </div>
                                <div class="col-lg-9 col-md-9 col-9">
                                    <p class="">{{ $user->email }}</p>
                                </div>
                                <div class="col-lg-3 col-md-3 col-3">
                                    <p class="">Số điện thoại</p>
                                </div>
                                <div class="col-lg-9 col-md-9 col-9">
                                    <p class="">{{ $user->phone }}</p>
                                </div>
                                <div class="col-lg-3 col-md-3 col-3">
                                    <p class="">Ngày sinh</p>
                                </div>
                                <div class="col-lg-9 col-md-9 col-9">
                                    <p class="">
                                        {{ $user->dob ? $user->dob : 'Bạn chưa cập nhật thông tin ngày sinh của bản thân' }}
                                    </p>
                                </div>
                                <div class="col-lg-3 col-md-3 col-3">
                                    <p class="">Địa chỉ</p>
                                </div>

                                <div class="col-lg-9 col-md-9 col-9 d-flex justify-content-between align-items-center">
                                    <p class="">{{ $user->address }}</p>
                                </div>
                                <div class="col-lg-12 col-md-12 col-12 d-flex justify-content-center align-items-center">
                                    <button class="btn btn-primary" data-id="{{ $user->id }}" data-toggle="modal"
                                        data-target="#exampleModal{{ $user->id }}">Cập nhật</button>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModal{{ $user->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Cập nhật
                                                thông tin</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form id="updateProfileForm" enctype="multipart/form-data">
                                                    <div class="mb-3">
                                                        <label for="exampleInputEmail3" class="form-label">Số điện thoại</label>
                                                        <input type="text" required name="phone" class="form-control" value="{{ $user->phone ?? '' }}" id="exampleInputEmail3" {{ $user->phone ? 'readonly' : '' }}>
                                                    </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Ngày sinh</label>
                                                    <input type="date" required name="dob" value="{{ $user->dob }}" class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail2" class="form-label">Địa chỉ</label>
                                                    <input type="text" required name="address" value="{{ $user->address }}" class="form-control" id="exampleInputEmail2">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputPassword3" class="form-label">Ảnh đại diện</label>
                                                    <input type="file" name="avatar" class="form-control" id="exampleInputPassword3"  value="{{$user->avatar}}">
                                                    <img id="preview-avatar" src="#" alt="Preview"
                                                        style="display:none; max-width:160px; height:80px;">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleInputPassword4" class="form-label">Ảnh bìa</label>
                                                    <input type="file" name="banner" class="form-control"
                                                        id="exampleInputPassword4" value="{{$user->banner}}">
                                                    <img id="preview-banner" src="#" alt="Preview"
                                                        style="display:none; max-width:160px; height:80px;">
                                                </div>
                                                <button class="btn btn-danger my-3 delete" type="submit">Cập nhật</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="content2">
                            <h3>Cập nhật khẩu</h3>
                            <form id="updatePassword">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Mật khẩu cũ</label>
                                    <input type="password" name="old_password" class="form-control"
                                        id="exampleInputEmail1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail2" class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="password" class="form-control" id="exampleInputEmail2"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail2" class="form-label">Nhập lại mật khẩu mới</label>
                                    <input type="password" name="repassword" class="form-control"
                                        id="exampleInputEmail2" required>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-danger delete mx-3" type="submit">Cập nhật</button>
                                    <button type="button" class="btn btn-secondary mx-3"
                                        data-dismiss="modal">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script>
        function showToast(message, type = 'success') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: 'top',
                position: 'right',
                close: true,
                backgroundColor: type === 'success' ? '#2ecc71' : '#e74c3c',
            }).showToast();
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('exampleInputPassword3');
            const previewImg = document.getElementById('preview-avatar');
            fileInput.addEventListener("change", function() {
                const reader = new FileReader();
                reader.onload = function() {
                    previewImg.src = reader.result;
                    previewImg.style.display = "block";
                };
                reader.readAsDataURL(fileInput.files[0]);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('exampleInputPassword4');
            const previewImg = document.getElementById('preview-banner');
            fileInput.addEventListener("change", function() {
                const reader = new FileReader();
                reader.onload = function() {
                    previewImg.src = reader.result;
                    previewImg.style.display = "block";
                };
                reader.readAsDataURL(fileInput.files[0]);
            });
        });
    </script>
    <script>
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('update-success')) {
            // Hiển thị toast
            showToast('Cập nhật thông tin cá nhân thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        } else if (urlParams.has('create-success')) {
            // Hiển thị toast
            showToast('Thêm mới thông tin cá nhân thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#updateProfileForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '/profile/cap-nhat-tai-khoan',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/quan-ly-tai-khoan?update-success';
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                showToast(value, 'error');
                            });
                        } else {
                            console.log(xhr)
                            alert(
                            'Có lỗi trong quá trình thêm mới danh mục. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#updatePassword').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '/profile/cap-nhat-mat-khau',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        showToast(response.message);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                showToast(value, 'error');
                            });
                        } else {
                            console.log(xhr)
                            showToast(
                                'Có lỗi trong quá trình cập nhật mật khẩu. Vui lòng thử lại.'
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection
