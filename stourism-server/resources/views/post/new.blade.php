@extends('component.index')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Thêm bài viết</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="post-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Tên bài viết</label>
                                <input type="text" name="title" class="form-control" id="exampleInputEmail1">
                            </div>
                
                            <div class="mb-3">
                                <label for="disabledSelect12" class="form-label">Đối tượng</label>
                                <select id="disabledSelect12" name="target" class="form-select">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>                                
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="ckeditor  form-control" id="editor" name="description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung bài viết</label>
                                <textarea class="ckeditor form-control" id="editor" name="content"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Hình ảnh</label>
                                <input type="file" name="images" multiple class="form-control" id="exampleInputEmail1">
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
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
            $('#post-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: '/admin/bai-viet/them-moi',
                    data: formData,
                    processData: false,  // Không xử lý dữ liệu
                    contentType: false,  // Không đặt kiểu dữ liệu
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/bai-viet?create-success';
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
                            'Có lỗi trong quá trình thêm mới bài viết. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
