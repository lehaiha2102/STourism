@extends('component.index')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Thêm mới sản phẩm</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="product-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Tên sản phẩm</label>
                                <input type="text" name="product_name" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Tình trạng</label>
                                <select id="disabledSelect" name="product_status" class="form-select">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Tạm ngừng</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Thuộc sở hữu</label>
                                <select id="disabledSelect" name="business_id" class="form-select">
                                    @foreach($business as $b)
                                        <option value="{{ $b->id }}">{{ $b->business_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Dịch vụ</label>
                                <select id="disabledSelect" multiple name="product_service[]" class="form-select">
                                    <option value="Máy lạnh">Máy lạnh</option>
                                    <option value="Máy lạnh">Chỗ đậu xe</option>
                                    <option value="Máy lạnh">Lễ tân 24/24</option>
                                    <option value="Đưa đón tận nơi">Đưa đón tận nơi</option>

                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect12" class="form-label">Mảng kinh doanh</label>
                                <select id="disabledSelect12" name="category_id[]" multiple class="form-select">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Số điện thoại</label>
                                <input type="text" name="product_phone" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email</label>
                                <input type="text" name="product_email" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Địa chỉ</label>
                                <input type="text" name="product_address" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword3" class="form-label">Ảnh</label>
                                <input type="file" name="product_main_image" class="form-control" id="exampleInputPassword3">
                                <img id="preview-avatar" src="#" alt="Preview" style="display:none; max-width:160px; height:80px;">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword4" class="form-label">Bộ sưu tập ảnh</label>
                                <input type="file" name="product_image[]" multiple class="form-control" id="exampleInputPassword4">
                                <img id="preview-banner" src="#" alt="Preview" style="display:none; max-width:160px; height:80px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="ckeditor form-control" id="editor" name="product_description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
    <script>
        CKEDITOR.replace('editor');
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
        $(document).ready(function () {
            $('#product-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '/admin/san-pham/them-moi',
                    data: formData,
                    processData: false,  // Không xử lý dữ liệu
                    contentType: false,  // Không đặt kiểu dữ liệu
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/san-pham';
                        } else {
                            alert('Có lỗi trong quá trình thêm mới danh mục. Vui lòng thử lại.');
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
                            alert('Có lỗi trong quá trình thêm mới danh mục. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
