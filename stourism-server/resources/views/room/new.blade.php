@extends('component.index')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Thêm mới phòng & bàn</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="room-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Tên phòng(bàn)</label>
                                <input type="text" name="room_name" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Tình trạng</label>
                                <select id="disabledSelect" name="room_status" class="form-select">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Tạm ngừng</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Loại phòng(bàn)</label>
                                <select id="disabledSelect" name="room_type" class="form-select">
                                    <option value="1">Vip</option>
                                    <option value="0">Private</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Diện tích (m2)</label>
                                <input type="number" name="room_area" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Số lượng</label>
                                <input type="number" name="room_quantity" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Giá phòng</label>
                                <input type="number" name="room_rental_price" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect12" class="form-label">Thuộc sở hữu</label>
                                <select id="disabledSelect12" name="product_id" class="form-select">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Địa chỉ</label>
                                <input type="text" name="room_address" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword3" class="form-label">Khả dụng(người)</label>
                                <input type="number" name="room_capacity" class="form-control" id="exampleInputPassword3">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword4" class="form-label">Bộ sưu tập ảnh</label>
                                <input type="file" name="room_image[]" multiple class="form-control" id="exampleInputPassword4">
                                <img id="preview-banner" src="#" alt="Preview" style="display:none; max-width:160px; height:80px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="ckeditor form-control" id="editor" name="room_description"></textarea>
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
        $(document).ready(function () {
            $('#room-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '/admin/phong/them-moi',
                    data: formData,
                    processData: false,  // Không xử lý dữ liệu
                    contentType: false,  // Không đặt kiểu dữ liệu
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/phong';
                        } else {
                            alert('Có lỗi trong quá trình thêm mới danh mục. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
