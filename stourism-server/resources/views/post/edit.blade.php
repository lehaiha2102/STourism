@extends('component.index')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Cập nhật bài viết</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="post-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Tên bài viết</label>
                                <input type="text" name="title" class="form-control" value="{{$post->title}}" id="exampleInputEmail1">
                                <input type="hidden" name="id" id="post-id" value="{{ $post->id }}">
                            </div>
                
                            <div class="mb-3">
                                <label for="disabledSelect12" class="form-label">Đối tượng</label>
                                <select id="disabledSelect12" name="target" class="form-select">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $product->id == $post->target ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>                                
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="ckeditor  form-control" value="{{$post->description}}" id="editor" name="description">{{$post->description}}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung bài viết</label>
                                <textarea class="ckeditor form-control" id="editor" value="{{$post->content}}" name="content">{{$post->content}}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Hình ảnh</label>
                                <input type="file" name="images" multiple class="form-control" value="{{$post->images}}" id="exampleInputEmail1">
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
            $('#post-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                console.log(formData);
                $.ajax({
                    type: 'post',
                    url: '/admin/bai-viet/cap-nhat',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/bai-viet?update-success';
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
                            showToast('Có lỗi trong quá trình cập nhật bài viết. Vui lòng thử lại.', 'error');
                        }
                    }
                });
            });
        });
    </script>
@endsection
