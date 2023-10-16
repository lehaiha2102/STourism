@extends('component.index')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Thuê phòng</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="booking-form">
                            <div class="mb-3">
                                <label for="disabledSelect1" class="form-label">Người thuê</label>
                                <select id="disabledSelect1" name="booker" class="form-select">
                                    <option ></option>
                                    @foreach($users as $index => $user)
                                        <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect12" class="form-label">Phòng</label>
                                <select id="disabledSelect12" name="room_id" class="form-select">
                                    @foreach($rooms as $index => $room)
                                        <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Tình trạng</label>
                                <select id="disabledSelect" name="booking_status" class="form-select">
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Tạm ngừng</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail12" class="form-label">Thời gian đến</label>
                                <input type="datetime-local" name="checkin_time" class="form-control" id="exampleInputEmail12">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail13" class="form-label">Thời gian trả phòng</label>
                                <input type="datetime-local" name="checkout_time" class="form-control" id="exampleInputEmail13">
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Tình trạng đặt cọc phòng</label>
                                <select id="disabledSelect" name="advance_payment_check" class="form-select">
                                    <option value="1">Đã thanh toán</option>
                                    <option value="0">Chưa thanh toán</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail14" class="form-label">Số tiền cọc trước</label>
                                <input type="number" name="advance_payment" class="form-control" id="exampleInputEmail14">
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
        $(document).ready(function () {
            $('#booking-form').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: '/admin/thue-phong/them-moi',
                    data: formData,
                    processData: false,  // Không xử lý dữ liệu
                    contentType: false,  // Không đặt kiểu dữ liệu
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            window.location.href = '/admin/thue-phong';
                        } else {
                            alert('Có lỗi trong quá trình thêm mới doanh nghiệp. Vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
