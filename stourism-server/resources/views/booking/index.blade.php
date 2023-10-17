@extends('component.index')
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-semibold mb-4">Quản lý đặt chỗ</h5>
                        <a href="{{ route('booking.new')  }}" class="btn btn-outline-success m-1 mb-4">Thêm mới</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Id</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Người book</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Thời gian nhận phòng</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Thời gian trả phòng</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Xử lý</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 d-flex align-items-center justify-content-center">Thanh toán tiền cọc</h6>
                                </th>
                                <th class="border-bottom-0">
                                    {{-- some thing --}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($booking as $index => $b)
                                <tr>
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6></td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->booker }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->checkin_time }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->checkout_time }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->booking_status }}</h6>
                                    </td>
                                    <td class="border-bottom-0 status-toggle" data-booking-id="{{ $b->id }}" data-status="{{ $b->booking_status }}" >
                                        {!! $b->booking_status == 1 ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Còn hoạt động</span>' : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Tạm ngừng hoạt động</span>' !!}
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->advance_payment_check }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <a class="btn btn-outline-warning m-1" href="{{ route('booking.edit', ['bookingId' => $b->id])  }}">Chỉnh sửa</a>
                                        <button type="button" class="btn btn-outline-danger m-1" data-id="{{$b->id}}" data-toggle="modal" data-target="#exampleModal{{$b->id}}">
                                            <i class="pe-7s-trash"></i>
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
    @foreach($booking as $b)
        <div class="modal fade" id="exampleModal{{$b->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xóa danh mục</h5>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Bạn có chắc chắn muốn xóa thông tin đặt phòng ?</span>?</p>
                        <form id="delete-booking">
                            <input class="my-3" type="hidden" name="id" id="id_delete" value="{{$b->id}}">
                            <button class="btn btn-danger my-3" type="submit">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.status-toggle').click(function() {
                var booking_id = $(this).data('booking-id');
                var current_status = $(this).data('status');
                var new_status = current_status == 1 ? 0 : 1;

                $.ajax({
                    url: '{{ route("booking.change-status") }}',
                    type: 'POST',
                    data: {
                        'bookingId': booking_id,
                        'status': new_status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            var newStatusHtml = new_status == 1 ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Còn hoạt động</span>' : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Tạm ngừng hoạt động</span>';
                            $('.status-toggle[data-booking-id="' + booking_id + '"]').data('status', new_status);
                            $('.status-toggle[data-booking-id="' + booking_id + '"]').html(newStatusHtml);
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#delete-booking').on('submit', function (e) {
                e.preventDefault();
                var bookingSlug = $('#id_delete').val();

                $.ajax({
                    type: 'DELETE',
                    url: '/admin/dat-cho/'+ bookingSlug +'/xoa',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if(response.status === 'success'){
                            alert('xóa thành công');
                            window.location.href = '/admin/dat-cho';
                        }
                    }
                });
            });
        });
    </script>
@endsection