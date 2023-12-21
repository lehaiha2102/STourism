@extends('component.index')
@section('content')
    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-semibold mb-4">Quản lý người dùng</h5>
                        <a href="" class="btn btn-outline-success m-1 mb-4">Thêm mới</a>
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
                                        <h6 class="fw-semibold mb-0">Email</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Số điện thoại</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Địa chỉ</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Phân quyền</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        {{-- some thing --}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usersWithRoles as $index => $data)
                                    <tr>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{ $data->full_name }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">
                                                {{ $data->email ? $data->email : 'Chưa cập nhật email' }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">
                                                {{ $data->phone ? $data->phone : 'Chưa cập nhật số điện thoại' }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">
                                                {{ $data->address ? $data->address : 'Chưa cập nhật địa chỉ' }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">
                                                {{ $data->role_name ? $data->role_name : 'Chưa cập nhật phân quyền' }}</h6>
                                        </td>
                                        <td class="border-bottom-0 d-flex align-items-center">
                                            @if ($data->role_name !== 'admin')
                                                <a href="#" class="toggle-banner text-white"
                                                    data-user-id="{{ $data->id }}">
                                                    <span id="userSpan_{{ $data->id }}"
                                                        class="badge {{ $data->banner ? 'bg-primary' : 'bg-danger' }} me-2">{{ $data->banner ? 'Mở khóa' : 'Khóa tài khoản' }}</span>
                                                </a>
                                            @endif
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
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-banner').click(function(e) {
                e.preventDefault();
                var userId = $(this).data('user-id');

                $.ajax({
                    type: 'POST',
                    url: '/toggle-banner/' + userId,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            var spanId = 'userSpan_' + userId;
                            console.log('Updating span:',
                                spanId);

                            $('#' + spanId).removeClass().addClass('badge ' + (response.banner ?
                                'bg-primary' : 'bg-danger')).html(response.banner ?
                                'Mở khóa' : 'Khóa tài khoản');
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
@endsection
