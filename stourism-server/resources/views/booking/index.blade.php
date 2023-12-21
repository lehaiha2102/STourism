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
                                    <h6 class="fw-semibold mb-0">Người đặt</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Phòng</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Thời gian nhận phòng</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Thời gian trả phòng</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Số tiền thanh toán</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Trạng thái thanh toán</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($booking as $index => $b)
                                <tr>
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6></td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->full_name }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->room_name }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->checkin_time }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $b->checkout_time }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ number_format($b->payment) }}</h6>
                                    </td>
                                    <td class="border-bottom-0 status-toggle" data-booking-id="{{ $b->id }}" data-status="{{ $b->booking_status }}" >
                                        {!! $b->payment_check == 1 ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Đã thanh toán</span>' : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Chưa thanh toán</span>' !!}
                                    </td>
                                    <td class="border-bottom-0 status-toggle" data-booking-id="{{ $b->id }}" data-status="{{ $b->booking_status }}" >
                                        {!! $b->booking_status === 'success' ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Đã hoàn thành</span>' : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Chưa hoàn thành</span>' !!}
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
@endsection
