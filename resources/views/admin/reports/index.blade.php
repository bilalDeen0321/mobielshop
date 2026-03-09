@extends('admin.layout')

@section('title', 'Reports & Analytics')

@section('content')
<div class="reports-page">
<h3 class="page-title"> Reports & Analytics
    <small>sales statistics and detailed reports</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><span>Reports</span></li>
    </ul>
</div>

{{-- Summary stat cards --}}
<div class="row margin-bottom-20">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue">
            <div class="visual"><i class="fa fa-calendar-check-o"></i></div>
            <div class="details">
                <div class="number">{{ $currency }}{{ number_format($todayTotal, 2) }}</div>
                <div class="desc">Today's revenue</div>
                <div class="desc" style="font-size: 11px; opacity: 0.9;">{{ $todayCount }} transaction{{ $todayCount !== 1 ? 's' : '' }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat green">
            <div class="visual"><i class="fa fa-line-chart"></i></div>
            <div class="details">
                <div class="number">{{ $currency }}{{ number_format($weekTotal, 2) }}</div>
                <div class="desc">Last 7 days</div>
                <div class="desc" style="font-size: 11px; opacity: 0.9;">{{ $weekCount }} transaction{{ $weekCount !== 1 ? 's' : '' }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat purple">
            <div class="visual"><i class="fa fa-bar-chart"></i></div>
            <div class="details">
                <div class="number">{{ $currency }}{{ number_format($monthTotal, 2) }}</div>
                <div class="desc">This month</div>
                <div class="desc" style="font-size: 11px; opacity: 0.9;">{{ $monthCount }} transaction{{ $monthCount !== 1 ? 's' : '' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Charts row --}}
<div class="row">
    <div class="col-md-8">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-bar-chart font-dark"></i>
                    <span class="caption-subject bold uppercase">Sales trend (last 7 days)</span>
                </div>
            </div>
            <div class="portlet-body chart-wrap chart-wrap-tall">
                <canvas id="chart-daily-sales"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-pie-chart font-dark"></i>
                    <span class="caption-subject bold uppercase">Payment methods (30 days)</span>
                </div>
            </div>
            <div class="portlet-body chart-wrap chart-wrap-tall">
                <canvas id="chart-payment-methods"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-graph font-dark"></i>
                    <span class="caption-subject bold uppercase">Sales by month (last 6 months)</span>
                </div>
            </div>
            <div class="portlet-body chart-wrap chart-wrap-medium">
                <canvas id="chart-monthly-sales"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Report links - professional card grid --}}
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-doc font-dark"></i>
                    <span class="caption-subject bold uppercase">Detailed reports</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-sm-6 col-md-4 margin-bottom-20">
                        <a href="{{ route('admin.reports.daily') }}" class="report-card-link">
                            <div class="report-card">
                                <i class="fa fa-calendar report-card-icon blue-soft"></i>
                                <h4 class="report-card-title">Daily sales</h4>
                                <p class="report-card-desc">Sales for a specific date</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 margin-bottom-20">
                        <a href="{{ route('admin.reports.monthly') }}" class="report-card-link">
                            <div class="report-card">
                                <i class="fa fa-calendar-o report-card-icon green"></i>
                                <h4 class="report-card-title">Monthly sales</h4>
                                <p class="report-card-desc">Sales for a full month</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 margin-bottom-20">
                        <a href="{{ route('admin.reports.top-products') }}" class="report-card-link">
                            <div class="report-card">
                                <i class="fa fa-trophy report-card-icon purple"></i>
                                <h4 class="report-card-title">Top products</h4>
                                <p class="report-card-desc">By quantity and revenue (date range)</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 margin-bottom-20">
                        <a href="{{ route('admin.reports.low-stock') }}" class="report-card-link">
                            <div class="report-card">
                                <i class="fa fa-exclamation-triangle report-card-icon red"></i>
                                <h4 class="report-card-title">Low stock</h4>
                                <p class="report-card-desc">Products below minimum level</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4 margin-bottom-20">
                        <a href="{{ route('admin.reports.profit') }}" class="report-card-link">
                            <div class="report-card">
                                <i class="fa fa-money report-card-icon green-soft"></i>
                                <h4 class="report-card-title">Profit report</h4>
                                <p class="report-card-desc">Revenue vs cost (date range)</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('styles')
<style>
/* Chart containers: fixed height so charts and labels/legends are not cut off */
.chart-wrap { position: relative; width: 100%; }
.chart-wrap-tall { min-height: 320px; }
.chart-wrap-medium { min-height: 200px; }
.chart-wrap canvas { max-width: 100%; }

/* Stat cards: ensure transaction count text is visible */
.reports-page .dashboard-stat .details { min-height: 70px; overflow: visible; }
.reports-page .dashboard-stat .number { font-size: 22px; }
.reports-page .dashboard-stat .desc { margin-top: 2px; line-height: 1.3; }

.report-card-link { display: block; text-decoration: none; color: inherit; }
.report-card { background: #fff; border: 1px solid #e7ecf1; border-radius: 6px; padding: 22px; transition: box-shadow 0.2s, border-color 0.2s; min-height: 120px; }
.report-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.1); border-color: #3598dc; }
.report-card-icon { font-size: 32px; display: block; margin-bottom: 12px; }
.report-card-icon.blue-soft { color: #3598dc; }
.report-card-icon.green { color: #32c5d2; }
.report-card-icon.green-soft { color: #26c281; }
.report-card-icon.purple { color: #8e44ad; }
.report-card-icon.red { color: #e7505a; }
.report-card-title { margin: 0 0 10px 0; font-size: 17px; font-weight: 600; color: #2c3e50; }
.report-card-desc { margin: 0; font-size: 13px; color: #7f8c8d; line-height: 1.5; }
.portlet.bordered { border: 1px solid #e7ecf1 !important; border-radius: 4px; }
.portlet.bordered .portlet-title { border-bottom: 1px solid #eef1f5; padding-bottom: 10px; margin-bottom: 0; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
    var currency = window.adminCurrencySymbol || '£';

    // Colours
    var blue = 'rgba(54, 162, 235, 0.8)';
    var blueBorder = 'rgb(54, 162, 235)';
    var green = 'rgba(75, 192, 192, 0.8)';
    var greenBorder = 'rgb(75, 192, 192)';
    var palette = [
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 99, 132, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)'
    ];
    var paletteBorder = ['rgb(54, 162, 235)', 'rgb(255, 99, 132)', 'rgb(255, 206, 86)', 'rgb(75, 192, 192)', 'rgb(153, 102, 255)', 'rgb(255, 159, 64)'];

    // 7 days bar chart
    var dailyCtx = document.getElementById('chart-daily-sales');
    if (dailyCtx) {
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: @json($chart7DaysLabels ?? []),
                datasets: [{
                    label: 'Revenue',
                    data: @json($chart7DaysTotals ?? []),
                    backgroundColor: blue,
                    borderColor: blueBorder,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return currency + Number(ctx.raw).toFixed(2); }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(v) { return currency + v; }
                        }
                    }
                }
            }
        });
    }

    // Monthly bar chart
    var monthlyCtx = document.getElementById('chart-monthly-sales');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($chartMonthlyLabels ?? []),
                datasets: [{
                    label: 'Revenue',
                    data: @json($chartMonthlyTotals ?? []),
                    backgroundColor: green,
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return currency + Number(ctx.raw).toFixed(2); }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(v) { return currency + v; }
                        }
                    }
                }
            }
        });
    }

    // Payment methods doughnut
    var paymentLabels = @json($paymentChartLabels ?? []);
    var paymentTotals = @json($paymentChartTotals ?? []);
    var paymentCtx = document.getElementById('chart-payment-methods');
    if (paymentCtx && paymentLabels.length > 0) {
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentTotals,
                    backgroundColor: palette.slice(0, paymentLabels.length),
                    borderColor: paletteBorder.slice(0, paymentLabels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { bottom: 20 } },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                var pct = total ? ((ctx.raw / total) * 100).toFixed(1) : 0;
                                return ctx.label + ': ' + currency + Number(ctx.raw).toFixed(2) + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    } else if (paymentCtx) {
        paymentCtx.parentNode.innerHTML = '<p class="text-muted text-center" style="padding: 40px 20px;">No payment data in the last 30 days.</p>';
    }
})();
</script>
@endpush
