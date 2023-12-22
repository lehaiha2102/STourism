@extends('component.index')
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-semibold mb-4">Địa điểm lưu trú</h5>
                        <a href="{{ route('product.new') }}" class="btn btn-outline-success m-1 mb-4">Thêm mới</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle" id="example">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Id</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Tên</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Ảnh</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Chủ sở hữu</h6>
                                    </th>

                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0 d-flex align-items-center justify-content-center">Trạng
                                            thái</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        {{-- some thing --}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr data-key="{{ $product->id }}">
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{ $product->product_name }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <img src="/images/{{ $product->product_main_image }}"
                                                alt="product-avatar-{{ $product->product_slug }}" width="80">
                                        </td>
                                        <td class="border-bottom-0">
                                            @foreach ($business as $b)
                                                @if ($product->business_id == $b->id)
                                                    <h6 class="fw-semibold mb-1">{{ $b->business_name }}</h6>
                                                @endif
                                            @endforeach
                                        </td>

                                        <td class="border-bottom-0 status-toggle" data-product-id="{{ $product->id }}"
                                            data-status="{{ $product->product_status }}">
                                            {!! $product->product_status == 1
                                                ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Còn hoạt động</span>'
                                                : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Tạm ngừng hoạt động</span>' !!}
                                        </td>
                                        <td class="border-bottom-0">
                                            <a class="btn btn-outline-success m-1"
                                                href="{{ route('product.detail', ['product_slug' => $product->product_slug]) }}">Xem</a>
                                            <a class="btn btn-outline-warning m-1"
                                                href="{{ route('product.edit', ['product_slug' => $product->product_slug]) }}">Chỉnh
                                                sửa</a>
                                            <button type="button" class="btn btn-outline-danger m-1"
                                                data-id="{{ $product->id }}" data-toggle="modal"
                                                data-target="#exampleModal{{ $product->id }}">
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
    @foreach ($products as $product)
        <div class="modal fade" id="exampleModal{{ $product->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xóa sản phẩm</h5>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Bạn có chắc chắn muốn xóa <span
                                style="color:red">{{ $product->product_name }}</span>?</p>
                        <button class="btn btn-danger my-3 delete" data-id="{{ $product->id }}">Xóa</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        $('#example').DataTable();
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
            showToast('Cập nhật địa điểm nghỉ dưỡng thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        } else if (urlParams.has('create-success')) {
            // Hiển thị toast
            showToast('Thêm mới địa điểm nghỉ dưỡng thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.status-toggle').click(function() {
                var product_id = $(this).data('product-id');
                var current_status = $(this).data('status');
                var new_status = current_status == 1 ? 0 : 1;

                $.ajax({
                    url: '{{ route('product.change-status') }}',
                    type: 'POST',
                    data: {
                        'product_id': product_id,
                        'product_status': new_status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            var newStatusHtml = new_status == 1 ?
                                '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Còn hoạt động</span>' :
                                '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Tạm ngừng hoạt động</span>';
                            $('.status-toggle[data-product-id="' + product_id + '"]').data(
                                'status', new_status);
                            $('.status-toggle[data-product-id="' + product_id + '"]').html(
                                newStatusHtml);
                            showToast('Thay đổi trạng thái địa điểm nghỉ dưỡng thành công',
                                'success');
                        } else {
                            showToast(response.message);
                        }
                    },
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".delete").click(function() {
                var button = $(this);
                var id = button.data("id");

                $.ajax({
                    url: "/admin/san-pham/" + id + '/xoa',
                    type: 'DELETE',
                    data: {
                        "id": id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function() {
                        showToast('Xóa đỉa điểm nghỉ dưỡng thành công', 'success');
                        var key = button.data('id');
                        $('tr[data-key="' + key + '"]').remove();
                        $('[data-dismiss="modal"]').trigger('click');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                showToast(value, 'error');
                            });
                        } else {
                            console.log(xhr);
                            showToast(
                                'Có lỗi trong quá trình xóa địa điểm này. Vui lòng thử lại.'
                                );
                        }
                    }
                });
            });
        });
    </script>
@endsection
