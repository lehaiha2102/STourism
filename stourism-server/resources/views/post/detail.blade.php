@extends('component.index')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title fw-semibold mb-4">Chi tiết bài viết</h1>
                <div class="row justify-content-between">
                    <div class="card col-lg-6 col-md-6 col-12">
                        <div class="card-body row">
                            <div class="col-md-6 mb-2">
                                <h5>Tên bài viết: </h5>
                            </div>
                            <div class="col-md-6 mb-2">
                                <h5>{{ $product->product_name }}</h5>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Trạng thái: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="p-1"
                                    style="background-color: {{ $product->product_status === 1 ? '#5d87ff' : '#fa896b' }}; color: white">
                                    {{ $product->product_status === 1 ? 'Đang hoạt động' : 'Tạm ngừng kinh doanh' }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Số điện thoại: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="p-1"
                                    style="background-color: {{ $product->product_phone ? 'color: black' : '#fa896b; color: white' }}">
                                    {{ $product->product_phone ? $product->product_phone : 'Chủ sở hữu chưa đăng ký số điện thoại cho địa điểm này' }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Email: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="p-1"
                                    style="background-color: {{ $product->product_email ? 'color: black' : '#fa896b; color: white' }}">
                                    {{ $product->product_email ? $product->product_email : 'Chủ sở hữu chưa đăng ký email cho địa điểm này' }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Địa chỉ: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="p-1"
                                    style="background-color: {{ $product->product_address ? 'color: black' : '#fa896b; color: white' }}">
                                    {{ $product->product_address ? $product->product_address : 'Chủ sở hữu chưa đăng ký địa chỉ cho địa điểm này' }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Mô tả: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="p-1"
                                    style="background-color: {{ $product->product_description ? 'color: black' : '#fa896b; color: white' }}">
                                    {{ $product->product_description ? strip_tags($product->product_description) : 'Chủ sở hữu chưa có mô tả cho địa điểm này' }}
                                </span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <h5>Dịch vụ được cung cấp: </h5>
                            </div>
                            @php
                                $commonServices = ['Máy lạnh', 'Chỗ đậu xe', 'Lễ tân 24/24', 'Đưa đón tận nơi'];
                                $productServices = json_decode($product->product_service);
                            @endphp

                            @foreach ($commonServices as $service)
                                @if (in_array($service, $productServices))
                                    <div class="col-md-6 mb-2">
                                        <span>
                                            {{ $service }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="card col-lg-6 col-md-6 col-12">
                        <div class="card-body row">
                            <div class="col-md-6 mb-2">
                                <h5>Chủ sở hữu: </h5>
                            </div>
                            <div class="col-md-6 mb-2">
                                @foreach ($business as $b)
                                    @if ($b->id == $product->business_id)
                                        <h5>{{ $b->business_name }}</h5>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Trạng thái: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                @foreach ($business as $b)
                                    @if ($b->id == $product->business_id)
                                        <span class="p-1"
                                            style="background-color: {{ $b->business_status === 1 ? '#5d87ff' : '#fa896b' }}; color: white">
                                            {{ $b->business_status === 1 ? 'Đang hoạt động' : 'Tạm ngừng kinh doanh' }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Mảng kinh doanh: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                @foreach ($business as $b)
                                    @if ($b->id == $product->business_id)
                                        @php
                                            $businessSegment = json_decode($b->business_segment);
                                        @endphp

                                        @if (is_array($businessSegment))
                                            @foreach ($businessSegment as $segment)
                                                <p>{{ $segment }}</p>
                                            @endforeach
                                        @else
                                            <p>{{ $businessSegment }}</p>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Số điện thoại: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                @foreach ($business as $b)
                                    @if ($b->id == $product->business_id)
                                        <p>{{ $b->business_phone }}</p>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Email: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                @foreach ($business as $b)
                                    @if ($b->id == $product->business_id)
                                        <p>{{ $b->business_email }}</p>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6 mb-2">
                                <span>Địa chỉ: </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                @foreach ($business as $b)
                                    @if ($b->id == $product->business_id)
                                        <p>{{ $b->business_address }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card col-lg-6 col-md-6 col-12">
                        <div class="card-body row">
                            @php
                                $product_images = json_decode($product->product_image, true); // Use true to decode as an array
                            @endphp
                        
                            @if (is_array($product_images))
                                @foreach ($product_images as $image)
                                    <img src="/images/{{ $image }}" alt="Product Image"/>
                                @endforeach
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    @endsection
