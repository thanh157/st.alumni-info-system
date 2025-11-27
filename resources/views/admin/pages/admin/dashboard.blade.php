@extends('admin.layouts.master')

@section('content')
    <div class="card">
        {{-- Greeting --}}
        <div class="card-body pt-4 pb-0">
            <div id="greetBox"
                class="alert custom-greeting d-flex flex-column flex-sm-row align-items-start align-items-sm-center shadow-sm p-3 rounded mb-4"
                role="alert">
                <i id="greetIcon" class="fa-solid fa-sun me-2 fs-4 mb-2 mb-sm-0"></i>
                <div>
                    <strong id="greeting" class="fs-4 text-gradient"></strong>
                    <div class="text-muted small">Chúc bạn có một ngày làm việc hiệu quả, {{ auth()->user()->full_name }}</div>
                </div>
            </div>
        </div>



        {{-- <div class="text-muted small">
    @if (auth()->check())
        Chúc bạn có một ngày làm việc hiệu quả, {{ auth()->user()->full_name }}
    @else
        Chúc bạn có một ngày làm việc hiệu quả!
    @endif
</div> --}}

          {{-- @if (auth()->check())
                        <div class="d-none d-xl-block text-start">
                            <div class="fw-bold text-white" style="font-size: 14px;">{{ $auth['full_name'] }}</div>
                            <div class="text-white-50" style="font-size: 12px;">{{ $auth['email'] }}</div>
                        </div>
                    @endif --}}
                    

        {{-- KPI SECTION --}}
        <div class="card-body pt-0">
            <div class="mb-2">
                <p class="fw-semibold">Tổng Quan</p>
                 <span class="text-primary small">
                    (Dữ liệu đợt mới nhất: {{ $currentRoundName ?? '...' }})
                </span>
                <div class="row">
                    <div class="col-12 col-sm-6 col-xl-3 mb-3">
                        <div
                            class="p-3 rounded shadow-sm d-flex flex-column justify-content-between h-100 bg-white kpi-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="fw-semibold text-muted mb-1 small text-uppercase">1) TỶ LỆ PHẢN HỒI</p>
                                    <h4 class="mb-0">{{ $kpi1_ResponseRate }}%</h4>
                                </div>
                                <span class="kpi-icon kpi-blue"><i class="fa-solid fa-file-circle-check"></i></span>
                            </div>
                            <small class="text-muted mt-2">{{ $totalResponses }}/{{ $totalGraduates }} Sinh viên</small>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3 mb-3">
                        <div
                            class="p-3 rounded shadow-sm d-flex flex-column justify-content-between h-100 bg-white kpi-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="fw-semibold text-muted mb-1 small text-uppercase">2) CÓ VIỆC / PHẢN HỒI</p>
                                    <h4 class="mb-0">{{ $kpi2_EmployedPerRes }}%</h4>
                                </div>
                                <span class="kpi-icon kpi-green"><i class="fa-solid fa-briefcase"></i></span>
                            </div>
                            <small class="text-muted mt-2">Trên số SV đã trả lời</small>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3 mb-3">
                        <div
                            class="p-3 rounded shadow-sm d-flex flex-column justify-content-between h-100 bg-white kpi-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="fw-semibold text-muted mb-1 small text-uppercase">3) CÓ VIỆC / SỐ SV TỐT NGHIỆP </p>
                                    <h4 class="mb-0">{{ $kpi3_EmployedPerGrad }}%</h4>
                                </div>
                                <span class="kpi-icon kpi-amber"><i class="fa-solid fa-graduation-cap"></i></span>
                            </div>
                            <small class="text-muted mt-2">Trên tổng số SV tốt nghiệp</small>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3 mb-3">
                        <div
                            class="p-3 rounded shadow-sm d-flex flex-column justify-content-between h-100 bg-white kpi-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="fw-semibold text-muted mb-1 small text-uppercase">4) VIỆC LÀM PHÙ HỢP</p>
                                    <h4 class="mb-0">{{ $kpi4_RelevantPerGrad }}%</h4>
                                </div>
                                <span class="kpi-icon kpi-purple"><i class="fa-solid fa-star"></i></span>
                            </div>
                            <small class="text-muted mt-2">Đúng ngành + Liên quan + Học tiếp</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CHARTS SECTION --}}
            <div style="max-width: 1100px; margin: 30px auto; position: relative;">
                <h3 class="charts-title">Thống kê việc làm sinh viên theo đợt tốt nghiệp</h3>

                <a href="{{route('admin.charts.index')}}" class="btn btn-outline-primary btn-sm"
                    style="position: absolute; top: 0; right: 0; font-weight: 600;">
                    <i class="fa-solid fa-table-list me-1"></i>
                    Xem chi tiết
                </a>

                <div class="text-center mb-3">
                    <select id="chartTypeSelect" class="select">
                        <option value="employed">Tỉ lệ có việc làm / chưa có việc</option>
                        {{-- <option value="location">Làm trong nước / nước ngoài</option>
                        <option value="field">Đúng ngành / trái ngành (Gộp)</option> --}}
                        <option value="status_detail">Chi tiết tình hình việc làm </option>
                        <option value="area_detail">Chi tiết khu vực làm việc </option>
                    </select>
                </div>

                <div id="chartLoading" class="text-center p-5">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Đang tải dữ liệu biểu đồ...</p>
                </div>

                <div class="d-none" id="chartContainer">
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <div class="panel flex-fill" style="max-width: 380px;">
                            <h4 class="panel-title">Biểu đồ tròn tổng hợp</h4>
                            <div id="pieChart" class="chart-box"></div>
                        </div>
                        <div class="panel flex-fill" style="min-width: 560px;">
                            <h4 class="panel-title">Biểu đồ cột theo đợt</h4>
                            <div id="barChart" class="chart-box"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Styles --}}
    <style>
        :root {
            --ink: #0f172a;
            --muted: #667085;
            --line: #e7eaee;
            --bg: #ffffff;
            --brand: #2563eb;
            --brand-2: #06b6d4;
            --blue: #3b82f6;
            --green: #10b981;
            --purple: #8b5cf6;
            --amber: #f59e0b;
            --red: #ef4444;
            --radius: 14px;
            --shadow: 0 10px 30px rgba(2, 6, 23, .08);
        }

        .custom-greeting {
            background: linear-gradient(135deg, #eef2ff 0%, #ecfeff 100%);
            border: 1px solid #dbeafe;
        }

        .text-gradient {
            background: linear-gradient(90deg, var(--brand), var(--brand-2));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        #greetIcon {
            color: #f59e0b
        }

        .kpi-card {
            border: 1px solid var(--line)
        }

        .kpi-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.1rem
        }

        .kpi-blue {
            background: var(--blue)
        }

        .kpi-green {
            background: var(--green)
        }

        .kpi-purple {
            background: var(--purple)
        }

        .kpi-amber {
            background: var(--amber)
        }

        .charts-title {
            text-align: center;
            margin-bottom: 12px;
            color: var(--ink);
            font-weight: 800
        }

        .select {
            font-size: .95rem;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1.5px solid var(--line);
            min-width: 280px;
            background: #fff;
            color: var(--ink)
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 14px;
            box-shadow: var(--shadow)
        }

        .panel-title {
            margin: 6px 6px 12px;
            color: #334155;
            font-weight: 800;
            font-size: 1rem
        }

        .chart-box {
            width: 100%;
            height: 440px;
            border-radius: 12px
        }

        @media (max-width:768px) {
            .chart-box {
                height: 360px
            }
        }

        #pieChart .am5-Legend {
            margin-top: 8px !important;
        }
    </style>

    {{-- amCharts --}}
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    {{-- Greeting --}}
    <script>
        (() => {
            const h = new Date().getHours();
            const $txt = document.getElementById("greeting");
            const $ico = document.getElementById("greetIcon");
            let msg = "Chào buổi tối!", icon = "fa-moon";
            if (h >= 5 && h < 12) { msg = "Chào buổi sáng!"; icon = "fa-sun"; }
            else if (h >= 12 && h < 14) { msg = "Chào buổi trưa!"; icon = "fa-sun"; }
            else if (h >= 14 && h < 18) { msg = "Chào buổi chiều!"; icon = "fa-cloud-sun"; }
            $txt.textContent = msg;
            $ico.className = `fa-solid ${icon} me-2 fs-4 mb-2 mb-sm-0`;
        })();
    </script>

    {{-- Charts Logic --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            am5.ready(function () {
                const chartLoading = document.getElementById('chartLoading');
                const chartContainer = document.getElementById('chartContainer');
                const chartTypeSelect = document.getElementById('chartTypeSelect');

                let DATASETS = {};

                // --- 0. BẢNG MÀU DÙNG CHUNG CHO BAR & PIE ---
                const MODE_COLORS = {
                    // Ví dụ: Có việc = xanh, Chưa có việc = vàng
                    employed: [
                        am5.color(0x10b981), // Có việc làm
                        am5.color(0xfacc15), // Chưa có việc làm (vàng)
                    ],
                    location: [
                        am5.color(0x3b82f6), // Trong nước
                        am5.color(0xf59e0b), // Nước ngoài
                    ],
                    field: [
                        am5.color(0x8b5cf6), // Đúng ngành/LQ
                        am5.color(0x6b7280), // Trái ngành
                    ],
                    status_detail: [
                        am5.color(0x10b981), // Đúng ngành
                        am5.color(0x3b82f6), // Liên quan
                        am5.color(0xf59e0b), // Trái ngành
                        am5.color(0x8b5cf6), // Tiếp tục học
                        am5.color(0xef4444), // Chưa có việc
                    ],
                    area_detail: [
                        am5.color(0xe11d48), // Nhà nước
                        am5.color(0x2563eb), // Tư nhân
                        am5.color(0x0891b2), // Tự tạo việc
                        am5.color(0xd97706), // Nước ngoài
                    ]
                };

                // --- 1. SETUP PIE CHART ---
                const rootPie = am5.Root.new("pieChart");
                rootPie.setThemes([am5themes_Animated.new(rootPie)]);
                const pieChart = rootPie.container.children.push(am5percent.PieChart.new(rootPie, {
                    layout: rootPie.verticalLayout,
                    innerRadius: am5.percent(58)

                }));
                const pieSeries = pieChart.series.push(am5percent.PieSeries.new(rootPie, {
                    valueField: "value",
                    categoryField: "category",
                    alignLabels: false
                }));
                pieSeries.slices.template.setAll({
                    strokeOpacity: 0,
                    tooltipText: "{category}: {valuePercentTotal.formatNumber('0.0')}% ({value})"
                });
                pieSeries.labels.template.setAll({
                    text: "{valuePercentTotal.formatNumber('0.0')}%",
                    inside: true, radius: 0, fill: am5.color(0xffffff),
                    fontWeight: "700", forceHidden: true
                });
                const pieLegend = pieChart.children.push(am5.Legend.new(rootPie, {
                    centerX: am5.p50, x: am5.p50,
                    width: am5.percent(100),
                    marginTop: 25,
                    layout: rootPie.gridLayout
                }));
                pieLegend.valueLabels.template.set("forceHidden", true);

                // --- 2. SETUP BAR CHART ---
                const rootBar = am5.Root.new("barChart");
                rootBar.setThemes([am5themes_Animated.new(rootBar)]);
                const barChart = rootBar.container.children.push(am5xy.XYChart.new(rootBar, {
                    panX: false, panY: false,
                    wheelX: "panX", wheelY: "zoomX",
                    paddingLeft: 30, paddingBottom: 50,
                    layout: rootBar.verticalLayout
                }));
                barChart.set("cursor", am5xy.XYCursor.new(rootBar, {}));

                const xRenderer = am5xy.AxisRendererX.new(rootBar, { minGridDistance: 30 });
                xRenderer.labels.template.setAll({ centerY: am5.p50, centerX: am5.p50 });
                const xAxis = barChart.xAxes.push(am5xy.CategoryAxis.new(rootBar, {
                    categoryField: "term", renderer: xRenderer,
                }));
                const yAxis = barChart.yAxes.push(am5xy.ValueAxis.new(rootBar, {
                    min: 0, renderer: am5xy.AxisRendererY.new(rootBar, {})
                }));
                const barLegend = barChart.children.push(am5.Legend.new(rootBar, {
                    centerX: am5.p50, x: am5.p50,
                    marginTop: 15, marginBottom: 8
                }));

                // NHẬN am5.Color thay vì hex
                function createSeries(name, field, color) {
                    const s = barChart.series.push(am5xy.ColumnSeries.new(rootBar, {
                        name, xAxis, yAxis,
                        valueYField: field, categoryXField: "term",
                        sequencedInterpolation: true,
                        fill: color, // dùng color đã định nghĩa
                        tooltip: am5.Tooltip.new(rootBar, {
                            labelText: "[bold]{name}[/]\n{categoryX}: {valueY}"
                        })
                    }));
                    s.columns.template.setAll({
                        width: am5.percent(80), tooltipY: 0,
                        cornerRadiusTL: 6, cornerRadiusTR: 6
                    });
                    s.hide(0);
                    return s;
                }

                // --- 3. SERIES MAP DÙNG MÀU TỪ MODE_COLORS ---
                const seriesMap = {
                    employed: [
                        createSeries("Có việc làm", "employed", MODE_COLORS.employed[0]),
                        createSeries("Chưa có việc làm", "unemployed", MODE_COLORS.employed[1]),
                    ],
                    location: [
                        createSeries("Trong nước", "domestic", MODE_COLORS.location[0]),
                        createSeries("Nước ngoài", "foreign", MODE_COLORS.location[1]),
                    ],
                    field: [
                        createSeries("Đúng ngành/LQ", "related", MODE_COLORS.field[0]),
                        createSeries("Trái ngành", "unrelated", MODE_COLORS.field[1]),
                    ],
                    status_detail: [
                        createSeries("Đúng ngành", "s1", MODE_COLORS.status_detail[0]),
                        createSeries("Liên quan", "s2", MODE_COLORS.status_detail[1]),
                        createSeries("Trái ngành", "s3", MODE_COLORS.status_detail[2]),
                        createSeries("Tiếp tục học", "s4", MODE_COLORS.status_detail[3]),
                        createSeries("Chưa có việc", "s5", MODE_COLORS.status_detail[4]),
                    ],
                    area_detail: [
                        createSeries("Nhà nước", "a1", MODE_COLORS.area_detail[0]),
                        createSeries("Tư nhân", "a2", MODE_COLORS.area_detail[1]),
                        createSeries("Tự tạo việc", "a3", MODE_COLORS.area_detail[2]),
                        createSeries("Nước ngoài", "a4", MODE_COLORS.area_detail[3]),
                    ]
                };

                function refresh() {
                    const mode = chartTypeSelect.value;
                    if (!DATASETS[mode]) return;

                    // Bar chart
                    const all = Object.values(seriesMap).flat();
                    all.forEach(s => s.hide(0));

                    const vs = seriesMap[mode];
                    if (vs) {
                        barLegend.data.setAll(vs);
                        vs.forEach(s => s.show(0));
                        barChart.appear(600, 50);
                    }

                    // Pie chart
                    pieSeries.data.setAll(DATASETS[mode].pie);

                    pieSeries.events.once("datavalidated", () => {
                        pieSeries.dataItems.forEach((item, index) => {
                            const barColor = seriesMap[mode][index].get("fill");
                            if (barColor) {
                                item.get("slice").setAll({
                                    fill: barColor,
                                    stroke: barColor
                                });
                            }
                        });
                    });

                    pieLegend.data.setAll(pieSeries.dataItems);
                    pieSeries.appear(600, 50);
                }

                async function boot() {
                    try {
                        const res = await fetch("{{ url('dashboard/chart-data') }}", { cache: "no-store" });
                        if (!res.ok) throw new Error("Server error " + res.status);

                        DATASETS = await res.json();

                        if (!DATASETS.bar || DATASETS.bar.length === 0) {
                            chartLoading.innerHTML = '<p class="text-muted fw-semibold">Chưa có dữ liệu để hiển thị.</p>';
                            return;
                        }

                        xAxis.data.setAll(DATASETS.bar);
                        Object.values(seriesMap).flat().forEach(s => s.data.setAll(DATASETS.bar));

                        chartLoading.classList.add('d-none');
                        chartContainer.classList.remove('d-none');

                        chartTypeSelect.addEventListener('change', refresh);
                        refresh();
                    } catch (e) {
                        console.error(e);
                        chartLoading.innerHTML = `<p class="text-danger fw-semibold">Không thể tải dữ liệu.<br><small>${e.message}</small></p>`;
                    }
                }

                boot();
            });
        });
    </script>

@endsection