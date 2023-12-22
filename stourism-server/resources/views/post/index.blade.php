@extends('component.index')
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-semibold mb-4">Quản lý bài viết</h5>
                        <a href="{{ route('Post.new')  }}" class="btn btn-outline-success m-1 mb-4">Thêm mới</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle" id="example1">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Id</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Tiêu đề</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Mô tả ngắn</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Người viết</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Đối tượng</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Ngày tạo</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Cập nhật</h6>
                                </th>
                                <th class="border-bottom-0">
                                    {{-- some thing --}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $index => $post)
                                <tr>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $post->title }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $post->description }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $post->full_name }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $post->product_name }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $post->created_at }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $post->updated_at }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <a class="btn btn-outline-success m-1"
                                                href="{{ route('post.detail', ['id' => $post->id]) }}">Xem</a>
                                        <a class="btn btn-outline-warning m-1" href="{{ route('post.edit', ['id' => $post->id])  }}">Chỉnh sửa</a>
                                        <button type="button" class="btn btn-outline-danger m-1"
                                                data-id="{{ $post->id }}" data-toggle="modal"
                                                data-target="#exampleModal{{ $post->id }}">
                                                Xóa
                                            </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach($posts as $key => $post)
        <div class="modal fade" id="exampleModal{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xóa bài viết</h5>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Bạn có chắc chắn muốn xóa <span style="color:red">{{$post->title}}</span>?</p>
                        <form id="delete-post">
                            <input class="my-3" type="hidden" name="id" id="post_slug_delete" value="{{$post->id}}">
                            <button class="btn btn-danger my-3" type="submit">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script>
            $('#example1').DataTable();
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
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('update-success')) {
            // Hiển thị toast
            showToast('Cập nhật bài viết thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        } else if (urlParams.has('create-success')) {
            // Hiển thị toast
            showToast('Thêm mới bài viết thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#delete-post').on('submit', function (e) {
                e.preventDefault();
                var postSlug = $('#post_slug_delete').val();
                var formData = $(this).serialize();

                $.ajax({
                    type: 'DELETE',
                    url: '/admin/danh-muc/'+ postSlug +'/xoa',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if(response.status === 'success'){
                            window.location.href = '/admin/danh-muc';
                        }
                    }
                });
            });
        });
    </script>
@endsection
