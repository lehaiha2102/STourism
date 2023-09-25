@extends('component.index')
@section('content')
<div class="row">
    <div class="col-md-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="table-header d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-semibold mb-4">Danh mục</h5>
          <a href="{{ route('category.new')  }}" class="btn btn-outline-success m-1 mb-4">Thêm mới</a>
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
                    <h6 class="fw-semibold mb-0">Slug</h6>
                  </th>
                  <th class="border-bottom-0">
                    <h6 class="fw-semibold mb-0">Avatar</h6>
                  </th>
                  <th class="border-bottom-0">
                    <h6 class="fw-semibold mb-0">Banner</h6>
                  </th>
                    <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0 d-flex align-items-center justify-content-center">Trạng thái</h6>
                    </th>
                  <th class="border-bottom-0">
                    {{-- some thing --}}
                  </th>
                </tr>
              </thead>
              <tbody>
              @foreach($categories as $index => $category)
                <tr>
                  <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6></td>
                  <td class="border-bottom-0">
                      <h6 class="fw-semibold mb-1">{{ $category->category_name }}</h6>
                  </td>
                  <td class="border-bottom-0">
                    <p class="mb-0 fw-normal">{{ $category->category_slug }}</p>
                  </td>
                  <td class="border-bottom-0">
                      <img src="/images/{{ $category->category_image }}" alt="category-avatar-{{ $category->category_slug }}" width="80">
                  </td>
                  <td class="border-bottom-0">
                      <img src="/images/{{ $category->category_banner }}" alt="category-banner-{{ $category->category_slug }}" width="80">
                  </td>
                    <td class="border-bottom-0 status-toggle" data-category-id="{{ $category->id }}" data-status="{{ $category->category_status }}" >
                        {!! $category->category_status == 1 ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Còn hoạt động</span>' : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Tạm ngừng hoạt động</span>' !!}
                    </td>
                    <td class="border-bottom-0">
                        <a class="btn btn-outline-warning m-1" href="{{ route('category.edit', ['category_slug' => $category->category_slug])  }}">Chỉnh sửa</a>
                        <button type="button" class="btn btn-outline-danger m-1" data-id="{{$category->id}}" data-toggle="modal" data-target="#exampleModal{{$category->id}}">
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
@foreach($categories as $category)
    <div class="modal fade" id="exampleModal{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xóa danh mục</h5>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Bạn có chắc chắn muốn xóa <span style="color:red">{{$category->category_name}}</span>?</p>
                    <form id="delete-category">
                        <input class="my-3" type="hidden" name="id" id="category_slug_delete" value="{{$category->category_slug}}">
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
            var category_id = $(this).data('category-id');
            var current_status = $(this).data('status');
            var new_status = current_status == 1 ? 0 : 1;

            $.ajax({
                url: '{{ route("categories.change-status") }}',
                type: 'POST',
                data: {
                    'category_id': category_id,
                    'status': new_status
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        var newStatusHtml = new_status == 1 ? '<span class="badge bg-success rounded-3 fw-semibold d-flex align-items-center justify-content-center">Còn hoạt động</span>' : '<span class="badge bg-danger rounded-3 fw-semibold d-flex align-items-center justify-content-center">Tạm ngừng hoạt động</span>';
                        $('.status-toggle[data-category-id="' + category_id + '"]').data('status', new_status);
                        $('.status-toggle[data-category-id="' + category_id + '"]').html(newStatusHtml);
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
        $('#delete-category').on('submit', function (e) {
            e.preventDefault();
            var categorySlug = $('#category_slug_delete').val();
            var formData = $(this).serialize();

            $.ajax({
                type: 'DELETE',
                url: '/admin/danh-muc/'+ categorySlug +'/xoa',
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
