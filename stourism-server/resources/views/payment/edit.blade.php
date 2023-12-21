@extends('component.index')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Thêm mới phòng</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="room-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Tên phòng</label>
                                <input type="text" name="room_name" value="{{ $rooms->room_name }}" class="form-control" id="exampleInputEmail1">
                                <input type="hidden" id="room-slug" value="{{ $rooms->room_slug }}">
                            </div>
                
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Số lượng</label>
                                <input type="number" name="room_quantity" value="{{ $rooms->room_quantity }}" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Giá phòng</label>
                                <input type="number" name="room_rental_price" value="{{ $rooms->room_rental_price }}" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect12" class="form-label">Thuộc sở hữu</label>
                                <select id="disabledSelect12" name="product_id" class="form-select">
                                    @foreach ($products as $product)
                                        @if ($product->id == $rooms->product_id)
                                            <option value="{{ $product->id }}" selected>{{ $product->product_name }}</option>
                                        @else
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>                            
                            <div class="mb-3">
                                <label for="exampleInputPassword3" class="form-label">Số lượng người lớn tối đa</label>
                                <input type="number" name="adult_capacity" value="{{ $rooms->adult_capacity }}" class="form-control" id="exampleInputPassword3">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword12" class="form-label">Số lượng trẻ em tối đa</label>
                                <input type="number" name="children_capacity" value="{{ $rooms->children_capacity }}" class="form-control" id="exampleInputPassword12">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword4" class="form-label">Bộ sưu tập ảnh</label>
                                <input type="file" name="room_image[]" value="{{ $rooms->room_image }}" multiple class="form-control" id="exampleInputPassword4">
                                <img id="preview-banner" src="#" alt="Preview" style="display:none; max-width:160px; height:80px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="ckeditor form-control" value="{{ $rooms->room_quantity }}" id="editor" name="room_description">{{ $rooms->room_description }}</textarea>
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
        $(document).ready(function () {
            $('#room-form').on('submit', function (e) {
                e.preventDefault();
                const roomSlug = $('#room-slug').val();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '/admin/phong/' + roomSlug + '/cap-nhat',
                    data: formData,
                    processData: false, 
                    contentType: false, 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/phong?update-success';
                        } else {
                            showToast('Có lỗi trong quá trình chỉnh sửa phòng. Vui lòng thử lại.', 'error');
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
                            showToast('Có lỗi trong quá trình cập nhật phòng. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
