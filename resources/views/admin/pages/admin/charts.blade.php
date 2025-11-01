@extends('admin.layouts.master')

@section('title', 'Biểu đồ thống kê')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-1 fw-bold">Báo cáo - Thống kê</h5>
            <nav style="--bs-breadcrumb-divider: '>'; font-size: 14px;">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Báo cáo - Thống kê</a></li>
                    <li class="breadcrumb-item active">Biểu đồ thống kê</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.report.index') }}" class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Quay lại báo cáo
            </a>
        </div>
    </div>

    <form id="filterForm" class="row g-3 mb-4 align-items-end" action="{{ route('admin.charts.index') }}" method="GET">
        @method('GET')
        <div class="col-md-4">
            <label for="survey_period" class="form-label">Chọn thuộc tính <span class="text-danger">*</span></label>
            <select name="select" class="form-select" required>
                <option value="">-- Chọn --</option>
                <option value="employment_status" {{ request('select')=='employment_status' ? 'selected' : '' }}>Thống
                    kê tình trạng việc làm</option>
                <option value="work_area" {{ request('select')=='work_area' ? 'selected' : '' }}>Cơ cấu khu vực làm việc
                </option>
                <option value="employed_since" {{ request('select')=='employed_since' ? 'selected' : '' }}>Thời điểm có
                    việc làm sau tốt nghiệp</option>
                <option value="trained_field" {{ request('select')=='trained_field' ? 'selected' : '' }}>Mức độ phù hợp
                    của việc làm với ngành đào tạo</option>
                <option value="professional_qualification_field" {{
                    request('select')=='professional_qualification_field' ? 'selected' : '' }}>Mức độ công việc phù hợp
                    trình độ chuyên môn</option>
                <option value="level_knowledge_acquired" {{ request('select')=='level_knowledge_acquired' ? 'selected'
                    : '' }}>Mức độ áp dụng kiến thức/kỹ năng đã học</option>
                <option value="average_income" {{ request('select')=='average_income' ? 'selected' : '' }}>Phổ thu nhập
                    bình quân (triệu đồng) </option>
                <option value="recruitment_type" {{ request('select')=='recruitment_type' ? 'selected' : '' }}>Các
                    phương thức tìm kiếm việc làm</option>
                <option value="job_search_method" {{ request('select')=='job_search_method' ? 'selected' : '' }}>Thống
                    kê các hình thức tuyển dụng</option>
                <option value="soft_skills_required" {{ request('select')=='soft_skills_required' ? 'selected' : '' }}>
                    Mức độ đáp ứng của CTĐT về kỹ năng mềm</option>
                <option value="must_attended_courses" {{ request('select')=='must_attended_courses' ? 'selected' : ''
                    }}>Nhu cầu học tập, bồi dưỡng nâng cao
                </option>
                <option value="solutions_get_job" {{ request('select')=='solutions_get_job' ? 'selected' : '' }}>Đề xuất
                    giải pháp tăng tỷ lệ việc làm đúng ngành</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="width: 150px">Xem biểu đồ</button>
        </div>
    </form>

    @if(empty($charts))
    <div class="alert alert-info text-center">
        <i class="bi bi-bar-chart-line fs-3"></i>
        <p class="mb-0 mt-2">Vui lòng chọn một thuộc tính và nhấn "Xem biểu đồ" để hiển thị dữ liệu.</p>
    </div>
    @else
    <div class="row g-4">
        @foreach ($charts as $index => $chart)
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 border-0 h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-center mb-3" style="font-size: 15px; line-height: 1.4;">
                        {{ $chart['name'] }}
                    </h6>
                    <div id="chart{{ $index }}" style="height: 350px; width: 100%;"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script>
    am5.ready(function () {
        const chartsData = @json($charts ?? []);

        if (!chartsData || chartsData.length === 0) {
            return;
        }

        const modernColors = [
            am5.color(0x3b82f6), am5.color(0x10b981), am5.color(0xf59e0b),
            am5.color(0xef4444), am5.color(0x8b5cf6), am5.color(0x64748b),
            am5.color(0x14b8a6), am5.color(0xec4899), am5.color(0x84cc16)
        ];

        chartsData.forEach((chart, index) => {
            const chartDivId = 'chart' + index;
            const type = chart.type ? chart.type : 'bar';

            const amData = Object.keys(chart.data).map(key => ({
                category: key,
                value: chart.data[key]
            })).sort((a, b) => b.value - a.value);

            const root = am5.Root.new(chartDivId);

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            const isDoughnutDefault = (index === 0 && amData.length <= 7);

            if (type === 'doughnut' || isDoughnutDefault) {
                const chartInstance = root.container.children.push(am5percent.PieChart.new(root, {
                    layout: root.verticalLayout,
                    innerRadius: am5.percent(58)
                }));

                const series = chartInstance.series.push(am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    alignLabels: false
                }));

                series.get("colors").set("colors", modernColors);

                series.slices.template.setAll({
                    stroke: am5.color(0xffffff),
                    strokeWidth: 2,
                    tooltipText: "[bold]{category}:[/] {value} ({valuePercentTotal.formatNumber('0.0')}%)"
                });

                series.labels.template.set("forceHidden", true);
                series.ticks.template.set("forceHidden", true);

                series.data.setAll(amData);

                const legend = chartInstance.children.push(am5.Legend.new(root, {
                    centerX: am5.p50,
                    x: am5.p50,
                    marginTop: 15,
                    marginBottom: 15,
                    layout: root.horizontalLayout,
                    maxRows: 2,
                    oversizedBehavior: "wrap"
                }));

                legend.labels.template.setAll({
                    fontSize: 13,
                    fontWeight: "500"
                });
                legend.valueLabels.template.set("forceHidden", true);

                legend.data.setAll(series.dataItems);
                series.appear(1000, 100);

            } else { // 'bar' chart
                const chartInstance = root.container.children.push(am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "none",
                    wheelY: "none",
                    paddingLeft: 0,
                    paddingRight: 20
                }));

                // Thêm con trỏ để tương tác
                chartInstance.set("cursor", am5xy.XYCursor.new(root, {}));

                // Trục Y (trục giá trị)
                const yAxis = chartInstance.yAxes.push(am5xy.ValueAxis.new(root, {
                    min: 0,
                    renderer: am5xy.AxisRendererY.new(root, {
                        minGridDistance: 30
                    })
                }));
                yAxis.get("renderer").labels.template.setAll({ fontSize: 12 });
                // Định dạng số nguyên cho trục Y
                yAxis.get("renderer").labels.template.set("numberFormat", "#");

                // Trục X (trục danh mục)
                const xAxis = chartInstance.xAxes.push(am5xy.CategoryAxis.new(root, {
                    categoryField: "category",
                    renderer: am5xy.AxisRendererX.new(root, {
                        cellStartLocation: 0.1,
                        cellEndLocation: 0.9,
                        minorGridEnabled: false,
                        minGridDistance: 70
                    }),
                    tooltip: am5.Tooltip.new(root, {})
                }));
                xAxis.get("renderer").grid.template.set("forceHidden", true);

                xAxis.get("renderer").labels.template.setAll({
                    fontSize: 12,
                    oversizedBehavior: "truncate",
                    maxWidth: 120,

                });

                xAxis.data.setAll(amData);

                // Tạo series (dữ liệu cột)
                const series = chartInstance.series.push(am5xy.ColumnSeries.new(root, {
                    name: "Series",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "value",
                    categoryXField: "category",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "[bold]{categoryX}:[/] {valueY}"
                    })
                }));

                series.columns.template.setAll({
                    width: am5.percent(40),
                    cornerRadiusTL: 6,
                    cornerRadiusTR: 6,
                    tooltipY: 0
                });
                const legend = chartInstance.children.push(am5.Legend.new(root, {
                    centerX: am5.p50,
                    x: am5.p50,
                    marginTop: 15,
                    marginBottom: 15,
                    layout: root.horizontalLayout,
                    maxRows: 2,
                    oversizedBehavior: "wrap"
                }));
                legend.labels.template.setAll({
                    fontSize: 13,
                    fontWeight: "500",
                    maxWidth: 140,
                    oversizedBehavior: "truncate",
                    tooltipText: "{category}"
                });
                legend.valueLabels.template.set("forceHidden", true);
                chartInstance.get("colors").set("colors", modernColors);

                series.columns.template.adapters.add("fill", function (fill, target) {
                    return chartInstance.get("colors").getIndex(series.columns.indexOf(target));
                });
                series.columns.template.adapters.add("stroke", function (stroke, target) {
                    return chartInstance.get("colors").getIndex(series.columns.indexOf(target));
                });

                series.data.setAll(amData);

                series.appear(1000);
                chartInstance.appear(1000, 100);
            }
        });
    });
</script>
@endsection