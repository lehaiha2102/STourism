@extends('component.index')
@section('content')

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Chỉnh sửa thông tin doanh nghiệp</h5>
                    <div class="card">
                        <div class="card-body">
                            @foreach($business as $index => $b)
                            <form id="business-form" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Tên doanh nghiệp</label>
                                    <input type="text" name="business_name" value="{{ $b->business_name }}" class="form-control" id="exampleInputEmail1">
                                    <input type="hidden" id="business-slug" value="{{ $b->business_slug }}">
                                </div>
                                <div class="mb-3">
                                    <label for="disabledSelect1" class="form-label">Chủ sở hữu</label>
                                    <select id="disabledSelect1" name="user_id" value="{{ $b->user_id }}" class="form-select">
                                        <option ></option>
                                        @foreach($users as $index => $user)
                                            @if($user->id === $b->user_id)
                                                <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="disabledSelect12" class="form-label">Mảng kinh doanh</label>
                                    <select id="disabledSelect12" name="business_segment[]" value={{ $b->business_segment }} multiple class="form-select">
                                        <option value="Khách sạn và Nhà nghỉ">Khách sạn và Nhà nghỉ</option>
                                        <option value="Nhà hàng và Thực phẩm">Nhà hàng và Thực phẩm</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="disabledSelect" class="form-label">Tình trạng</label>
                                    <select id="disabledSelect" name="business_status" value="{{ $b->business_status }}" class="form-select">
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Tạm ngừng</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail12" class="form-label">Email</label>
                                    <input type="text" name="business_email" value="{{ $b->business_email }}" class="form-control" id="exampleInputEmail12">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail13" class="form-label">Số điện thoại</label>
                                    <input type="text" name="business_phone" value="{{ $b->business_phone }}" class="form-control" id="exampleInputEmail13">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail14" class="form-label">Địa chỉ</label>
                                    <input type="text" name="business_address" value="{{ $b->business_address }}" class="form-control" id="exampleInputEmail14">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword3" class="form-label">Logo</label>
                                    <input type="file" name="business_logo" value="{{ $b->business_logo }}" class="form-control" id="exampleInputPassword3">
                                    <img id="preview-avatar" src="/images/{{ $b->business_logo }}" alt="Preview" style="max-width:160px; height:80px;">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword4" class="form-label">Banner</label>
                                    <input type="file" name="business_banner" value="{{ $b->business_banner }}" class="form-control" id="exampleInputPassword4">
                                    <img id="preview-banner" src="/images/{{ $b->business_banner }}" alt="Preview" style="max-width:160px; height:80px;">
                                </div>
                                <button type="submit" class="btn btn-primary">Thêm mới</button>
                            </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
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
        $(document).ready(function () {
            $('#business-form').on('submit', function (e) {
                e.preventDefault();
                const businessSlug = $('#business-slug').val();
                var formData = new FormData(this);
                $.ajax({
                    type: 'post',
                    url: '/admin/doanh-nghiep/'+businessSlug+'/cap-nhat',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/doanh-nghiep?update-success';
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                showToast(value, 'error');
                            });
                        } else {
                            console.log(xhr)
                            alert('Có lỗi trong quá trình cập nhật danh mục. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
