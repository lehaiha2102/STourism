@extends('component.index')
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-semibold mb-4">Quản lý phòng</h5>
                        <a href="{{ route('room.new') }}" class="btn btn-outline-success m-1 mb-4">Thêm mới</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle" id="example1">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Id</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Tên</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Chủ sở hữu</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Giá thuê</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        {{-- some thing --}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $index => $room)
                                    <tr data-key="{{ $room->id }}">
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{ $room->room_name }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            @foreach ($products as $product)
                                                @if ($product->id == $room->product_id)
                                                    <h6 class="fw-semibold mb-1">{{ $product->product_name }}</h6>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{ number_format($room->room_rental_price) }} ₫
                                            </h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <a class="btn btn-outline-warning m-1"
                                                href="{{ route('room.edit', ['room_slug' => $room->room_slug]) }}">Chỉnh
                                                sửa</a>
                                                <button type="button" class="btn btn-outline-danger m-1" data-id="{{$room->id}}" data-toggle="modal" data-target="#exampleModal{{$room->id}}">
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
    @foreach ($rooms as $room)
        <div class="modal fade" id="exampleModal{{ $room->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xóa phòng</h5>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Bạn có chắc chắn muốn xóa <span style="color:red">{{ $room->room_name }}</span>?
                        </p>
                        <button class="btn btn-danger my-3 delete" data-id="{{ $room->id }}">Xóa</button >
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
            showToast('Cập nhật phòng thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        } else if (urlParams.has('create-success')) {
            showToast('Thêm mới phòng thành công', 'success');
            history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    <script>
        $(document).ready(function() {
            $(".delete").click(function() {
                var button = $(this);
                var id = button.data("id");

                $.ajax({
                    url: "/admin/phong/" + id + '/xoa',
                    type: 'DELETE',
                    data: {
                        "id": id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function() {
                        showToast('Xóa phòng thành công', 'success');
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
                                'Có lỗi trong quá trình xóa phòng này. Vui lòng thử lại.'
                                );
                        }
                    }
                });
            });
        });
    </script>
@endsection
