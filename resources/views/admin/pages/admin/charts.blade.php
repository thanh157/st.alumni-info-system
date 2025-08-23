@extends('admin.layouts.master')

@section('title', 'Biểu đồ thống kê')

@section('content')
    <div class="container py-4">
        <!-- Header -->
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

        <!-- Bộ lọc -->
        <form id="filterForm" class="row g-3 mb-4" action="{{ route('admin.charts.index') }}" method="GET">
            @method('GET')
            <div class="col-md-3">
                <label for="survey_period" class="form-label">Chọn thuộc tính <span class="text-danger">*</span></label>
                <select name="select" class="form-select" required>
                    <option value="">-- Chọn --</option>
                    <option value="employment_status" {{ request('select') == 'employment_status' ? 'selected' : '' }}>Tình
                        trạng việc làm hiện tại</option>
                    <option value="work_area" {{ request('select') == 'work_area' ? 'selected' : '' }}>Đơn vị Anh/Chị đang
                        làm việc thuộc khu vực nào</option>
                    <option value="employed_since" {{ request('select') == 'employed_since' ? 'selected' : '' }}>Sau khi tốt
                        nghiệp, có việc làm từ khi nào</option>
                    <option value="trained_field" {{ request('select') == 'trained_field' ? 'selected' : '' }}>Có phù hợp
                        với ngành đào tạo không</option>
                    <option value="professional_qualification_field"
                        {{ request('select') == 'professional_qualification_field' ? 'selected' : '' }}>CV phù hợp chuyên
                        môn</option>
                    <option value="level_knowledge_acquired"
                        {{ request('select') == 'level_knowledge_acquired' ? 'selected' : '' }}>Có học được kỹ năng</option>
                    <option value="average_income" {{ request('select') == 'average_income' ? 'selected' : '' }}>Thu nhập
                        (triệu đồng)</option>
                    <option value="recruitment_type" {{ request('select') == 'recruitment_type' ? 'selected' : '' }}>Hình
                        thức tìm việc</option>
                    <option value="job_search_method" {{ request('select') == 'job_search_method' ? 'selected' : '' }}>Được
                        tuyển theo hình thức nào</option>
                    <option value="soft_skills_required"
                        {{ request('select') == 'soft_skills_required' ? 'selected' : '' }}>Kỹ năng mềm</option>
                    <option value="must_attended_courses"
                        {{ request('select') == 'must_attended_courses' ? 'selected' : '' }}>Tham gia khóa học nâng cao nào
                    </option>
                    <option value="solutions_get_job" {{ request('select') == 'solutions_get_job' ? 'selected' : '' }}>Giải
                        pháp tăng tỉ lệ đúng ngành</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="width: 150px">Xem biểu đồ</button>
            </div>
        </form>

        <!-- Biểu đồ -->
        <div class="row g-4">
            @foreach ($charts as $index => $chart)
                <div class="col-md-6">
                    <div class="card shadow-sm rounded-4 border-0 h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-center mb-3" style="font-size: 15px; line-height: 1.4;">
                                {{ $chart['name'] }}
                            </h6>
                            <div style="position: relative; height: 300px; width: 100%;">
                                <canvas id="chart{{ $index }}"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- CSS -->
    <style>
        .active-filter {
            background-color: #e9f7ef !important;
            border: 2px solid #28a745;
            font-weight: 600;
        }
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartsData = @json($charts);

        chartsData.forEach((chart, index) => {
            const ctx = document.getElementById('chart' + index).getContext('2d');
            const type = chart.type ? chart.type : (index === 0 ? 'doughnut' : 'bar');

            new Chart(ctx, {
                type: type,
                data: {
                    labels: Object.keys(chart.data),
                    datasets: [{
                        label: 'Số lượng',
                        data: Object.values(chart.data),
                        backgroundColor: [
                            '#28a745', '#ff5733', '#007bff', '#f1c40f', '#8e44ad', '#17a2b8'
                        ],
                        borderWidth: 1.5,
                        borderColor: '#fff',
                        borderRadius: type === 'bar' ? 6 : 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: type === 'doughnut',
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 13
                                },
                                usePointStyle: true,
                                padding: 15
                            }
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: type === 'bar' ? {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                drawBorder: false
                            },
                            categoryPercentage: 0.5,
                            barPercentage: 0.7
                        }
                    } : {}
                }
            });
        });
    </script>
@endsection
