@extends('component.index')
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-semibold mb-4">Doanh nghiệp</h5>
                        <a href="{{ route('business.new')  }}" class="btn btn-outline-success m-1 mb-4">Thêm mới</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Id</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Tên</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Avatar</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Chủ sở hữu</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Mảng kinh doanh</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 d-flex align-items-center justify-content-center">Trạng thái</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 d-flex align-items-center justify-content-center">Thông tin khác</h6>
                                </th>
                                <th class="border-bottom-0">
                                    {{-- some thing --}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($business as $index => $b)
                                <tr data-key="{{ $b->id }}">
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6></td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->business_name }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <img src="/images/{{ $b->business_logo }}" alt="business-avatar-{{ $b->business_slug }}" width="80">
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->full_name }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        @php
                                            $businessSegment = json_decode($b->business_segment);
                                        @endphp

                                        @if (is_array($businessSegment))
                                            @foreach ($businessSegment as $segment)
                                                <p class="fw-semibold d-flex align-items-center justify-content-center">{{ $segment }}</p>
                                            @endforeach
                                        @else
                                            <p class="fw-semibold d-flex align-items-center justify-content-center">{{ $businessSegment }}</p>
                                        @endif
                                    </td>
                                    <td class="border-bottom-0 status-toggle" data-business-id="{{ $b->id }}" data-status="{{ $b->business_status }}" >
                                        {!! $b->business_status == 1 ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Còn hoạt động</span>' : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Tạm ngừng hoạt động</span>' !!}
                                    </td>
                                    <td class="border-bottom-0">
                                       <span class="fw-semibold d-flex align-items-center justify-content-center">Số điện thoại: {{ $b->business_phone }}</span>
                                        <span class="fw-semibold d-flex align-items-center justify-content-center">Email: {{ $b->business_email }}</span>
                                        <span class="fw-semibold d-flex align-items-center justify-content-center">Địa chỉ: {{ $b->business_address }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <a class="btn btn-outline-warning m-1" href="{{ route('business.edit', ['business_slug' => $b->business_slug])  }}">Chỉnh sửa</a>
                                        <button type="button" class="btn btn-outline-danger m-1"
                                                data-id="{{ $b->id }}" data-toggle="modal"
                                                data-target="#exampleModal{{ $b->id }}">
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
    @foreach($business as $b)
        <div class="modal fade" id="exampleModal{{$b->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xóa doanh nghiệp</h5>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Bạn có chắc chắn muốn xóa <span style="color:red">{{$b->business_name}}</span>?</p>
                        <button class="btn btn-danger my-3 delete" data-id="{{ $b->id }}">Xóa</button >
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
            showToast('Cập nhật doanh nghiệp thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        } else if (urlParams.has('create-success')) {
            // Hiển thị toast
            showToast('Thêm mới doanh nghiệp thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.status-toggle').click(function() {
                var business_id = $(this).data('business-id');
                var current_status = $(this).data('status');
                var new_status = current_status == 1 ? 0 : 1;

                $.ajax({
                    url: '{{ route("business.change-status") }}',
                    type: 'POST',
                    data: {
                        'business_id': business_id,
                        'status': new_status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            var newStatusHtml = new_status == 1 ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Còn hoạt động</span>' : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Tạm ngừng hoạt động</span>';
                            $('.status-toggle[data-business-id="' + business_id + '"]').data('status', new_status);
                            $('.status-toggle[data-business-id="' + business_id + '"]').html(newStatusHtml);
                            showToast(response.message);
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
                    url: "/admin/doanh-nghiep/" + id + '/xoa',
                    type: 'DELETE',
                    data: {
                        "id": id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function() {
                        showToast('Xóa doanh nghiệp thành công', 'success');
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
                            alert(
                            'Có lỗi trong quá trình thêm mới doanh nghiệp. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
