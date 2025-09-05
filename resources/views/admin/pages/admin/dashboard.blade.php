@extends('admin.layouts.master')

@section('content')
    <div class="card">
        <div class="card-body pt-4 pb-0">
            <div id="greetBox" class="alert custom-greeting d-flex flex-column flex-sm-row align-items-start align-items-sm-center shadow-sm p-3 rounded mb-4" role="alert">
                <i id="greetIcon" class="fa-solid fa-sun me-2 fs-4 mb-2 mb-sm-0"></i>
                <div>
                    <strong id="greeting" class="fs-4 text-gradient"></strong>
                    <div class="text-muted small">Chúc bạn có một ngày làm việc hiệu quả, Supper Admin CNTT</div>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="mb-2">
                <p class="fw-semibold">Tổng quan</p>
                <div class="row">
                    <div class="col-12 col-sm-6 col-xl-3 mb-3">
                        <div class="p-3 rounded shadow-sm d-flex flex-column justify-content-between h-100 bg-white kpi-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="fw-semibold text-muted mb-1 small text-uppercase">PHIẾU ĐÃ KHẢO SÁT</p>
                                    <h4 class="mb-0">280</h4>
                                </div>
                                <span class="kpi-icon kpi-blue"><i class="fa-solid fa-file-circle-check"></i></span>
                            </div>
                            <small class="text-muted mt-2">Tổng số sinh viên đã phản hồi khảo sát</small>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3 mb-3">
                        <div class="p-3 rounded shadow-sm d-flex flex-column justify-content-between h-100 bg-white kpi-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="fw-semibold text-muted mb-1 small text-uppercase">TỶ LỆ CÓ VIỆC LÀM</p>
                                    <h4 class="mb-0">88%</h4>
                                </div>
                                <span class="kpi-icon kpi-green"><i class="fa-solid fa-briefcase"></i></span>
                            </div>
                            <small class="text-muted mt-2">Tỷ lệ sinh viên đã có việc làm sau tốt nghiệp</small>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3 mb-3">
                        <div class="p-3 rounded shadow-sm d-flex flex-column justify-content-between h-100 bg-white kpi-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="fw-semibold text-muted mb-1 small text-uppercase">LỚP HỌC</p>
                                    <h4 class="mb-0">15</h4>
                                </div>
                                <span class="kpi-icon kpi-purple"><i class="fa-solid fa-users-line"></i></span>
                            </div>
                            <small class="text-muted mt-2">Tổng số lớp học có trong hệ thống</small>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3 mb-3">
                        <div class="p-3 rounded shadow-sm d-flex flex-column justify-content-between h-100 bg-white kpi-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="fw-semibold text-muted mb-1 small text-uppercase">ĐỢT TỐT NGHIỆP</p>
                                    <h4 class="mb-0">6</h4>
                                </div>
                                <span class="kpi-icon kpi-amber"><i class="fa-solid fa-calendar-check"></i></span>
                            </div>
                            <small class="text-muted mt-2">Số đợt tốt nghiệp đã thực hiện khảo sát</small>
                        </div>
                    </div>
                </div>
            </div>

            <div style="max-width: 960px; margin: 30px auto;">
                <h3 class="charts-title">Thống kê việc làm sinh viên theo đợt tốt nghiệp</h3>

                <div class="text-center mb-3">
                    <select id="chartType" class="select">
                        <option value="employed">Tỉ lệ có việc làm / tốt nghiệp</option>
                        <option value="location">Làm trong nước / nước ngoài</option>
                        <option value="field">Đúng ngành / trái ngành</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between gap-4 flex-wrap">
                    <div class="panel flex-fill" style="max-width: 360px;">
                        <h4 class="panel-title">Biểu đồ tròn tổng hợp</h4>
                        <div id="pieChart" class="chart-box"></div>
                    </div>

                    <div class="panel flex-fill" style="min-width: 540px;">
                        <h4 class="panel-title">Biểu đồ cột theo đợt</h4>
                        <div id="chartdiv" class="chart-box"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root{
            --ink:#0f172a; --muted:#667085; --line:#e7eaee; --bg:#ffffff;
            --brand:#2563eb; --brand-2:#06b6d4;
            --blue:#3b82f6; --green:#10b981; --purple:#8b5cf6; --amber:#f59e0b; --red:#ef4444;
            --radius:14px; --shadow:0 10px 30px rgba(2,6,23,.08);
        }
        .custom-greeting{ background: linear-gradient(135deg, #eef2ff 0%, #ecfeff 100%); border: 1px solid #dbeafe; }
        .text-gradient{ background: linear-gradient(90deg, var(--brand), var(--brand-2)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        #greetIcon{ color:#f59e0b }
        .kpi-card{ border:1px solid var(--line) }
        .kpi-icon{ width:40px;height:40px;border-radius:12px;display:grid;place-items:center;color:#fff;font-size:1.1rem }
        .kpi-blue{ background:var(--blue) } .kpi-green{ background:var(--green) } .kpi-purple{ background:var(--purple) } .kpi-amber{ background:var(--amber) }
        .charts-title{ text-align:center; margin-bottom: 12px; color: var(--ink); font-weight:800 }
        .select{ font-size: 0.95rem; padding: 10px 14px; border-radius: 10px; border:1.5px solid var(--line); min-width:280px; background:#fff; color: var(--ink); }
        .panel{ background:#fff; border:1px solid var(--line); border-radius: var(--radius); padding: 14px; box-shadow: var(--shadow) }
        .panel-title{ margin: 6px 6px 12px; color:#334155; font-weight:800; font-size:1rem }
        .chart-box{ width: 100%; height: 440px; border-radius: 12px }
        @media (max-width: 768px){ .chart-box{ height: 360px } }
        #pieChart .am5-Legend { margin-top: 8px !important; }
    </style>

    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/plugins/exporting.js"></script>

    <script>
        (function(){
            const hour = new Date().getHours();
            const $txt = document.getElementById("greeting");
            const $ico = document.getElementById("greetIcon");
            const $box = document.getElementById("greetBox");
            let msg = "Chào buổi tối!", icon = "fa-moon", bg = "linear-gradient(135deg,#e0e7ff,#cffafe)";
            if (hour>=5 && hour<12){ msg="Chào buổi sáng!"; icon="fa-sun"; bg="linear-gradient(135deg,#fef9c3,#d9f99d)"; }
            else if (hour>=12 && hour<14){ msg="Chào buổi trưa!"; icon="fa-sun"; bg="linear-gradient(135deg,#fff7ed,#dbeafe)"; }
            else if (hour>=14 && hour<18){ msg="Chào buổi chiều!"; icon="fa-cloud-sun"; bg="linear-gradient(135deg,#e0f2fe,#fde68a)"; }
            $txt.textContent = msg;
            $ico.className = `fa-solid ${icon} me-2 fs-4 mb-2 mb-sm-0`;
            $box.style.background = bg;
        })();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function(){
            am5.ready(function(){
                function ensureFreshRoot(divId){
                    am5.array.each(am5.registry.rootElements, (r) => {
                        if (r.dom && r.dom.id === divId) r.dispose();
                    });
                }

                const PALETTE = [0x3b82f6, 0x10b981, 0xf59e0b, 0x8b5cf6, 0xef4444];
                const SLATE = 0x334155;

                const DATASETS = {
                    employed: {
                        title: "Tỉ lệ có việc làm / tốt nghiệp",
                        pie: [
                            { value: 88, category: "Có việc làm" },
                            { value: 12, category: "Chưa có việc làm" }
                        ],
                        bar: [
                            { term: "Đợt 1 2023", value: 250, color: PALETTE[0] },
                            { term: "Đợt 2 2023", value: 320, color: PALETTE[1] },
                            { term: "Đợt 1 2024", value: 280, color: PALETTE[4] },
                            { term: "Đợt 2 2024", value: 410, color: PALETTE[2] },
                            { term: "Dự kiến 2025", value: 350, color: PALETTE[3] }
                        ]
                    },
                    location: {
                        title: "Làm trong nước / nước ngoài",
                        pie: [
                            { value: 82, category: "Trong nước" },
                            { value: 18, category: "Nước ngoài" }
                        ],
                        bar: [
                            { term: "Đợt 1 2023", value: 210, color: PALETTE[0] },
                            { term: "Đợt 2 2023", value: 290, color: PALETTE[1] },
                            { term: "Đợt 1 2024", value: 240, color: PALETTE[4] },
                            { term: "Đợt 2 2024", value: 360, color: PALETTE[2] },
                            { term: "Dự kiến 2025", value: 300, color: PALETTE[3] }
                        ]
                    },
                    field: {
                        title: "Đúng ngành / trái ngành",
                        pie: [
                            { value: 71, category: "Đúng ngành" },
                            { value: 29, category: "Trái ngành" }
                        ],
                        bar: [
                            { term: "Đợt 1 2023", value: 180, color: PALETTE[0] },
                            { term: "Đợt 2 2023", value: 250, color: PALETTE[1] },
                            { term: "Đợt 1 2024", value: 220, color: PALETTE[4] },
                            { term: "Đợt 2 2024", value: 300, color: PALETTE[2] },
                            { term: "Dự kiến 2025", value: 260, color: PALETTE[3] }
                        ]
                    }
                };

                ensureFreshRoot("pieChart");
                const rootPie = am5.Root.new("pieChart");
                rootPie.setThemes([ am5themes_Animated.new(rootPie) ]);

                const chartPie = rootPie.container.children.push(
                    am5percent.PieChart.new(rootPie, { layout: rootPie.verticalLayout, innerRadius: am5.percent(58) })
                );

                const seriesPie = chartPie.series.push(
                    am5percent.PieSeries.new(rootPie, {
                        valueField: "value",
                        categoryField: "category",
                        alignLabels: false
                    })
                );

                seriesPie.get("colors").set("colors", PALETTE.map(c => am5.color(c)));

                seriesPie.slices.template.setAll({
                    strokeOpacity: 0,
                    tooltipText: "{category}: {valuePercentTotal.formatNumber('0.0')}%"
                });

                 seriesPie.labels.template.setAll({
                    text: "{valuePercentTotal.formatNumber('0.0')}%",
                    inside: true,
                    centerX: am5.p50,
                    centerY: am5.p50,
                    radius: 0,
                    textAlign: "center",
                    fontSize: "0.95em",
                    fontWeight: "700",
                    fill: am5.color(0xffffff),
                     forceHidden: true
                 });

                seriesPie.slices.template.states.create("hover", { scale: 1.05, shiftRadius: 6 });

                const legendPie = chartPie.children.push(am5.Legend.new(rootPie, {
                    centerX: am5.p50, x: am5.p50,
                    layout: rootPie.horizontalLayout, useDefaultMarker: true
                }));
                legendPie.data.setAll(seriesPie.dataItems);

                am5plugins_exporting.Exporting.new(rootPie, {
                    filePrefix: "pie-stats",
                    pngOptions: { quality: 0.92 },
                    dataSource: () => seriesPie.data.values
                });

                ensureFreshRoot("chartdiv");
                const root = am5.Root.new("chartdiv");
                root.setThemes([ am5themes_Animated.new(root) ]);

                const chart = root.container.children.push(am5xy.XYChart.new(root, {
                    panX: true, panY: false, paddingLeft: 0
                }));
                chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none" }));

                const xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30, grid: { strokeOpacity: 0.06 } });
                const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, { categoryField: "term", renderer: xRenderer }));

                const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                    min: 0, extraMax: 0.1,
                    renderer: am5xy.AxisRendererY.new(root, { grid: { strokeOpacity: 0.06 } })
                }));

                const series = chart.series.push(am5xy.ColumnSeries.new(root, {
                    name: "Theo đợt", xAxis, yAxis,
                    valueYField: "value", categoryXField: "term",
                    sequencedInterpolation: true,
                    interpolationDuration: 700,
                    interpolationEasing: am5.ease.out(am5.ease.cubic)
                }));

                series.columns.template.setAll({
                    cornerRadiusTL: 8, cornerRadiusTR: 8,
                    width: am5.percent(70),
                    strokeOpacity: 0,
                    tooltipText: "{categoryX}: {valueY.formatNumber('#,###')}",
                    interactive: true
                });

                series.columns.template.adapters.add("fill", function (fill, target) {
                    return target.dataItem?.dataContext?.columnSettings?.fill || am5.color(PALETTE[0]);
                });

                const hover = series.columns.template.states.create("hover", {});
                hover.setAll({
                    scaleY: 1.06, scaleX: 1.02,
                    shadowBlur: 7, shadowColor: am5.color(0x000000), shadowOpacity: 0.15
                });

                series.bullets.push(function(){
                    return am5.Bullet.new(root, {
                        locationY: 1,
                        sprite: am5.Label.new(root, {
                            text: "{valueY.formatNumber('#,###')}",
                            centerX: am5.p50, centerY: am5.p100, dy: -6,
                            populateText: true, fontWeight: "700", fill: am5.color(SLATE)
                        })
                    });
                });

                xRenderer.labels.template.adapters.add("rotation", (rotation, target)=>{
                    const w = target.maxWidth || 0;
                    return (w < 60) ? -25 : 0;
                });

                function loadBar(mode){
                    const rows = DATASETS[mode].bar.map(d => ({
                        term: d.term, value: d.value, columnSettings: { fill: am5.color(d.color) }
                    }));
                    xAxis.data.setAll(rows);
                    series.data.setAll(rows);
                    series.appear(700, 60);
                    chart.appear(700, 60);
                }

                am5plugins_exporting.Exporting.new(root, {
                    filePrefix: "bar-stats",
                    pngOptions: { quality: 0.92 },
                    dataSource: () => series.data.values
                });

                const $sel = document.getElementById('chartType');
                function refresh(){
                    const mode = $sel.value || 'employed';
                    seriesPie.data.setAll(DATASETS[mode].pie);
                    legendPie.data.setAll(seriesPie.dataItems);
                    loadBar(mode);
                }
                $sel.addEventListener('change', refresh);
                refresh();
            });
        });
    </script>
@endsection
