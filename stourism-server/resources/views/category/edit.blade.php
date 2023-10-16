@extends('component.index')
@section('content')
    @foreach($categories as $index => $category)
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Chỉnh sửa danh mục sản phẩm</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="category-form" enctype="multipart/form-data">
                            <input type="hidden" id="category-slug" value="{{$category->category_slug}}">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Tên danh mục</label>
                                    <input type="text" name="category_name" class="form-control" value="{{$category->category_name}}" id="exampleInputEmail1">
                                </div>
                                <div class="mb-3">
                                    <label for="disabledSelect" class="form-label">Tình trạng</label>
                                    <select id="disabledSelect" name="category_status" value="{{$category->category_status}}" class="form-select">
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Tạm ngừng</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword3" class="form-label">Avatar</label>
                                    <input type="file" name="category_image" value="{{$category->category_image}}" class="form-control" id="exampleInputPassword3">
                                    <img id="preview-avatar" src="/images/{{$category->category_image}}" alt="Preview" style="max-width:160px; height:80px;">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword4" class="form-label">Banner</label>
                                    <input type="file" name="category_banner" value="{{$category->category_banner}}" class="form-control" id="exampleInputPassword4">
                                    <img id="preview-banner" src="/images/{{$category->category_banner}}" alt="Preview" style="max-width:160px; height:80px;">
                                </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="ckeditor form-control" id="editor" name="category_description">{{$category->category_description}}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
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
            $('#category-form').on('submit', function (e) {
                e.preventDefault();
                const categorySlug = $('#category-slug').val();
                var formData = new FormData(this);

                $.ajax({
                    type: 'post',
                    url: '/admin/danh-muc/' + categorySlug + '/cap-nhat',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/danh-muc';
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
