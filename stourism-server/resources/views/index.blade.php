@extends('component.index')
@section('content')
    <div class="row mx-2">
        <div class="row col-lg-12 col-md-12 col-12 border shadow p-3 mb-5 bg-white rounded">
            <div class="col-lg-4 col-md-4 col-12 border">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span>
                        <i class="ti ti-user" style="font-size: 30px; color: green"></i>
                    </span>
                    <div class="d-flex flex-column justify-content-center align-items-center" style="font-weight: bold">
                        <span>{{ $totalUsers }}</span>
                        <span>Người dùng</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 border">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span>
                        <i class="ti ti-map-pin" style="font-size: 30px; color: red"></i>
                    </span>
                    <div class="d-flex flex-column justify-content-center align-items-center" style="font-weight: bold">
                        <span>{{ $operationScope }}</span>
                        <span>Khu vực</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 border">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span>
                        <i class="ti ti-heart-handshake" style="font-size: 30px; color: yellow"></i>
                    </span>
                    <div class="d-flex flex-column justify-content-center align-items-center" style="font-weight: bold">
                        <span>{{ $totalBusiness }}</span>
                        <span>Doanh nghiệp</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 border">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span>
                        <i class="ti ti-map" style="font-size: 30px; color: blue"></i>
                    </span>
                    <div class="d-flex flex-column justify-content-center align-items-center" style="font-weight: bold">
                        <span>{{ $travelPlace }}</span>
                        <span>Địa điểm du lịch</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 border">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span>
                        <i class="ti ti-blockquote" style="font-size: 30px; color: rgb(0, 0, 0)"></i>
                    </span>
                    <div class="d-flex flex-column justify-content-center align-items-center" style="font-weight: bold">
                        <span>{{ $totalPosts }}</span>
                        <span>Bài viết/Tháng</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 border">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <span>
                        <i class="ti ti-trekking" style="font-size: 30px; color: rgb(255, 94, 0)"></i>
                    </span>
                    <div class="d-flex flex-column justify-content-center align-items-center" style="font-weight: bold">
                        <span>{{ $travelPlace }}</span>
                        <span>Đặt chỗ/Tháng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Doanh số bán hàng</h5>
                        </div>
                        <div>
                            <select class="form-select" id="monthSelector">
                            </select>
                        </div>
                    </div>
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Yearly Breakup -->
                    <div class="card overflow-hidden">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-9 fw-semibold">Thu nhập năm</h5>
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="fw-semibold mb-3">{{ number_format($totalAdvancePaymentCurrentYear) }}
                                        <i class="ti ti-currency-dong fs-6"></i>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <!-- Monthly Earnings -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row alig n-items-start">
                                <div class="col-8">
                                    <h5 class="card-title mb-9 fw-semibold"> Thu thập hàng tháng</h5>
                                    <h4 class="fw-semibold mb-3">{{ number_format($total_advance_payment_current_month) }}
                                        <i class="ti ti-currency-dong fs-6"></i>
                                    </h4>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-warning rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-currency-dong fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="earning" data-advance-payment-by-day="{{ json_encode($advance_payment_by_day) }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h5 class="card-title fw-semibold">Giao dịch hôm nay</h5>
                    </div>
                    <ul class="timeline-widget mb-0 position-relative mb-n5">
                        @foreach ($transactionsToday as $trans)
                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                <div class="timeline-time text-dark flex-shrink-0 text-end">
                                    {{ $trans->transaction_hour . ':' . $trans->transaction_minute }}
                                </div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n1">Bạn có một giao dịch mới</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">Giao dịch gần đây</h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Id</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Tên người dùng</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Email</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Số điện thoại</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Quy</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Số tiền</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookingList as $id => $data )
                                <tr>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">{{$id + 1}}</h6>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-1">{{$data->full_name}}</h6>
                                        <span class="fw-normal">{{$data->booker_email}}</span><br/>
                                        <span class="fw-normal">{{$data->booker_phone}}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                      <span class="fw-normal">{{$data->room_name}}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-normal">{{$data->checkin_time}}</span>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-normal">{{$data->checkout_time}}</span>
                                        </div>
                                    </td>
                                    <td class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0 fs-4">{{number_format($data->payment)}} đ</h6>
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
    <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">Developed by Vicent Le</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            var currentMonth = currentDate.getMonth() + 1;
            var currentDay = currentDate.getDay();
            var monthNames = [
                "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
                "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
            ];
            var monthSelector = document.getElementById("monthSelector");
            var chartDiv = document.getElementById("chart");
            for (var i = 0; i < 12; i++) {
                var option = document.createElement("option");

                option.value = i + 1;
                option.text = monthNames[i] + " - " + currentYear;
                if (i + 1 === currentMonth) {
                    option.selected = true;
                }
                monthSelector.appendChild(option);
            }

            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueChart;

            monthSelector.addEventListener('change', function() {
                loadChartData(this.value);
            });

            function loadChartData(selectedMonth) {
                fetch('/get-revenue-data?month=' + selectedMonth)
                    .then(response => response.json())
                    .then(data => {
                        drawChart(data);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function drawChart(data) {
                if (revenueChart) {
                    revenueChart.destroy();
                }

                revenueChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.dates,
                        datasets: [{
                            label: 'Doanh số',
                            data: data.revenue,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            loadChartData(currentMonth);
        });
    </script>
@endsection
