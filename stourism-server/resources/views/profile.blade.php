@extends('component.index')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <div class="card"
                    style="background-image: url('{{ asset('assets/images/' . ($user->banner ? $user->banner : 'profile/pngtree-cartoon-tourism-beach-blue-image_14050.jpg')) }}'); background-size: cover;"
                    class="img-fluid avatar-xxl rounded-circle" alt="">
                    <div class="card-body pb-0">
                        <div class="row align-items-center">
                            <div class="col-md-3 justify-content-center align-items-center pb-4">
                                <div class="text-center">
                                    <img src="../assets/images/{{ $user->avatar ? $user->avartar : '/profile/user-1.jpg' }}"
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
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs nav-tabs-custom border-bottom-0 mt-3 nav-justfied" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link px-4 active" data-bs-toggle="tab" href="#projects-tab" role="tab" aria-selected="false" tabindex="-1">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">THÔNG TIN CÁ NHÂN</span>
                                </a>
                            </li><!-- end li -->
                            <li class="nav-item" role="presentation">
                                <!-- Sử dụng thuộc tính data-bs-target với ID của tab thay vì href -->
                                <a class="nav-link px-4" data-bs-toggle="tab" data-bs-target="#tasks-tab">
                                    <span class="d-block d-sm-none"><i class="mdi mdi-menu-open"></i></span>
                                    <span class="d-none d-sm-block">Mật khẩu</span>
                                </a>
                            </li><!-- end li --><!-- end li -->
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="tab-content p-4">
                        <div class="d-flex align-items-center">
                            <div class="tab-pane fade show active" id="projects-tab" role="tabpanel">
                                <div class="flex-1">
                                    <p class="card-title mb-4">THÔNG TIN CÁ NHÂN</p>
                                </div>
                                <div class="col-md-12" id="project-items-1">
                                    <div class="row" id="all-projects">
                                        <div class="card">
                                            <div class="card-body">

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
                                                        <p class="">{{ $user->dob ? $user->dob : 'Bạn chưa cập nhật thông tin ngày sinh của bản thân' }}</p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-3">
                                                        <p class="">Địa chỉ</p>
                                                    </div>

                                                    <div
                                                        class="col-lg-9 col-md-9 col-9 d-flex justify-content-between align-items-center">
                                                        <p class="">{{ $user->address }}</p>
                                                    </div>
                                                    <div
                                                        class="col-lg-12 col-md-12 col-12 d-flex justify-content-center align-items-center">
                                                        <button class="btn btn-primary" data-id="{{ $user->id }}"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal{{ $user->id }}">Cập nhật</button>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="exampleModal{{ $user->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Cập nhật
                                                                    thông tin</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="updateProfileForm">
                                                                    @if (!$user->phone)
                                                                        <div class="mb-3">
                                                                            <label for="exampleInputEmail3"
                                                                                class="form-label">Số điện thoại</label>
                                                                            <input type="text" name="phone"
                                                                                class="form-control"
                                                                                id="exampleInputEmail3">
                                                                        </div>
                                                                    @endif
                                                                    <div class="mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Ngày sinh</label>
                                                                        <input type="date" name="dob"
                                                                            value="{{ $user->dob }}"
                                                                            class="form-control" id="exampleInputEmail1">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="exampleInputEmail2"
                                                                            class="form-label">Địa chỉ</label>
                                                                        <input type="text" name="address"
                                                                            value="{{ $user->address }}"
                                                                            class="form-control" id="exampleInputEmail2">
                                                                    </div>
                                                                    <button class="btn btn-danger my-3 delete"
                                                                        type="submit">Cập nhật</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Hủy</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end cardbody -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tasks-tab" role="tabpanel">
                                <div class="flex-1">
                                    <p class="card-title mb-4">CẬP NHẬT MẬT KHẨU</p>
                                </div>
                                <div class="col-md-12" id="project-items-1">
                                    <div class="row" id="all-projects">
                                        <div class="card">
                                            <div class="card-body">

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
                                                        <p class="">{{ $user->dob ? $user->dob : 'Bạn chưa cập nhật thông tin ngày sinh của bản thân' }}</p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-3">
                                                        <p class="">Địa chỉ</p>
                                                    </div>

                                                    <div
                                                        class="col-lg-9 col-md-9 col-9 d-flex justify-content-between align-items-center">
                                                        <p class="">{{ $user->address }}</p>
                                                    </div>
                                                    <div
                                                        class="col-lg-12 col-md-12 col-12 d-flex justify-content-center align-items-center">
                                                        <button class="btn btn-primary" data-id="{{ $user->id }}"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal{{ $user->id }}">Cập nhật</button>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="exampleModal{{ $user->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Cập nhật
                                                                    thông tin</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="updateProfileForm">
                                                                    @if (!$user->phone)
                                                                        <div class="mb-3">
                                                                            <label for="exampleInputEmail3"
                                                                                class="form-label">Số điện thoại</label>
                                                                            <input type="text" name="phone"
                                                                                class="form-control"
                                                                                id="exampleInputEmail3">
                                                                        </div>
                                                                    @endif
                                                                    <div class="mb-3">
                                                                        <label for="exampleInputEmail1"
                                                                            class="form-label">Ngày sinh</label>
                                                                        <input type="date" name="dob"
                                                                            value="{{ $user->dob }}"
                                                                            class="form-control" id="exampleInputEmail1">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="exampleInputEmail2"
                                                                            class="form-label">Địa chỉ</label>
                                                                        <input type="text" name="address"
                                                                            value="{{ $user->address }}"
                                                                            class="form-control" id="exampleInputEmail2">
                                                                    </div>
                                                                    <button class="btn btn-danger my-3 delete"
                                                                        type="submit">Cập nhật</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Hủy</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end cardbody -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                </div>
                            </div>
                            <!-- end row -->
                        </div><!-- end tab pane -->
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
        $(document).ready(function () {
            $('#updateProfileForm').submit(function (e) {
                e.preventDefault();
    
                $.ajax({
                    type: 'PUT',
                    url: '{{ route('profile.update') }}',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        window.location.href = '/admin/quan-ly-tai-khoan?update-success';
                    },
                    error: function (error) {
                        console.log(error.responseJSON);
                        showToast('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                });
            });
        });
    </script>
@endsection
