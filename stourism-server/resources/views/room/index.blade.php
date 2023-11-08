@extends('component.index')
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-semibold mb-4">Quản lý phòng</h5>
                        <a href="{{ route('room.new')  }}" class="btn btn-outline-success m-1 mb-4">Thêm mới</a>
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
                                    <h6 class="fw-semibold mb-0">Chủ sở hữu</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Số lượng</h6>
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
                            @foreach($rooms as $index => $room)
                                <tr>
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6></td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $room->room_name }}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        @foreach($products as $product)
                                            @if($product->id == $room->product_id)
                                        <h6 class="fw-semibold mb-1">{{ $product->product_name }}</h6>
                                            @endif
                                            @endforeach
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ $room->room_quantity }} phòng</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{ number_format($room->room_rental_price) }} ₫</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <a class="btn btn-outline-warning m-1" href="{{ route('room.edit', ['room_slug' => $room->room_slug])  }}">Chỉnh sửa</a>
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
    @foreach($rooms as $room)
        <div class="modal fade" id="exampleModal{{$room->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xóa danh mục</h5>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Bạn có chắc chắn muốn xóa <span style="color:red">{{$room->room_name}}</span>?</p>
                        <form id="delete-room">
                            <input class="my-3" type="hidden" name="id" id="room_slug_delete" value="{{$room->room_slug}}">
                            <button class="btn btn-danger my-3" type="submit">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#delete-room').on('submit', function (e) {
                e.preventDefault();
                var roomSlug = $('#room_slug_delete').val();
                var formData = $(this).serialize();

                $.ajax({
                    type: 'DELETE',
                    url: '/admin/danh-muc/'+ roomSlug +'/xoa',
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
