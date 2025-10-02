@extends('admin.layouts.master')

@section('title', 'Báo cáo - Tổng hợp khảo sát việc làm')

@section('content')
    @php
        // Khai báo các biến để sử dụng trong view và tránh lỗi undefined
        $currentSurveyId = request('survey_id');
    @endphp
    <style>
        .custom-select {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            background-color: #fff;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            min-width: 220px;
            font-weight: 500;
        }

        .custom-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .custom-select:hover {
            border-color: #0d6efd;
        }
    </style>
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="mb-1 fw-bold">Báo cáo - Thống kê</h5>
                <nav style="--bs-breadcrumb-divider: '>'; font-size: 14px;">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Bảng điều khiển</a></li>
                        <li class="breadcrumb-item active">Báo cáo tổng hợp</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Tabs & Filter -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab1">Mẫu báo cáo 1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab2">Mẫu báo cáo 2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab3">Mẫu báo cáo 3</a>
            </li>
            <form method="GET" action="{{ route('admin.report.index') }}" class="ms-auto">
                <div class="d-flex align-items-center gap-2">
                    <label for="survey_id" class="col-form-label fw-semibold mb-0">Khảo sát:</label>
                    <select name="survey_id" id="survey_id" class="form-select custom-select" onchange="this.form.submit()">
                        <option value="">-- Chọn khảo sát --</option>
                        @php
                            // Lấy danh sách survey một lần duy nhất
                            $surveys_list = \App\Models\Survey::orderBy('created_at', 'desc')->get();
                        @endphp
                        @foreach ($surveys_list as $item)
                            <option value="{{ $item->id }}" {{ $currentSurveyId == $item->id ? 'selected' : '' }}>
                                {{ $item->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </ul>

        <!-- Nội dung tab -->
        @if($currentSurveyId && isset($r1) && !empty($r1))
            @php
                // Chuẩn bị dữ liệu một lần để tránh truy vấn trong vòng lặp (N+1 problem)
                $responsesByCode = $r2->keyBy('code_student');
                $studentIdsForGraduation = $studentTab2->pluck('id');
                $graduationData = Illuminate\Support\Facades\DB::table('graduation_student')
                    ->join('graduation', 'graduation_student.graduation_id', '=', 'graduation.id')
                    ->whereIn('graduation_student.student_id', $studentIdsForGraduation)
                    ->select('graduation_student.student_id', 'graduation.certification', 'graduation.certification_date')
                    ->get()
                    ->keyBy('student_id');
                $majors = \App\Models\Major::all()->keyBy('id');
            @endphp
            <div class="tab-content">
                <!-- Mẫu báo cáo 1 -->
                <div class="tab-pane fade show active" id="tab1">
                    <div class="card shadow-sm border mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="{{ route('admin.charts.index') }}" class="btn btn-primary me-2">
                                    <i class="bi bi-eye"></i> Xem biểu đồ thống kê
                                </a>
                                <a href="{{ route('surveys.export', ['survey_id' => $currentSurveyId, 'graduation_id' => $selectedGraduationId]) }}" class="btn btn-success">
                                    <i class="bi bi-download"></i> Tải xuống báo cáo
                                </a>
                            </div>

                            <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                                <div class="text-center mb-4">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="mb-1">BAN QUẢN LÝ ĐÀO TẠO.</h6>
                                    <h5 class="fw-bold text-decoration-underline mb-0">
                                        BÁO CÁO TÌNH HÌNH VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}
                                    </h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle" style="font-size: 12px; min-width: 2000px;">
                                        <thead class="align-middle">
                                        <tr>
                                            <th rowspan="3">TT</th>
                                            <th rowspan="3">Mã ngành<br><small>(Ghi bằng số theo mã ngành tuyển sinh)</small></th>
                                            <th rowspan="3">Tên ngành đào tạo</th>
                                            <th colspan="2" rowspan="2">(4)<br>Số sinh viên tốt nghiệp</th>
                                            <th colspan="2" rowspan="2">(5)<br>Số sinh viên phản hồi</th>
                                            <th colspan="5">Tình hình việc làm</th>
                                            <th rowspan="3">Tỷ lệ có việc làm / phản hồi</th>
                                            <th rowspan="3">Tỷ lệ có việc làm / tốt nghiệp</th>
                                            <th colspan="4" rowspan="2">Khu vực làm việc</th>
                                            <th rowspan="3">Nơi làm việc<br>(Tỉnh/TP)</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Có việc làm</th>
                                            <th rowspan="2">Tiếp tục học</th>
                                            <th rowspan="2">Chưa có việc làm</th>
                                        </tr>
                                        <tr>
                                            <th>Tổng số</th><th>Nữ</th>
                                            <th>Tổng số</th><th>Nữ</th>
                                            <th>Đúng ngành</th><th>Liên quan</th><th>Không liên quan</th>
                                            <th>Nhà nước</th><th>Tư nhân</th><th>Tự tạo</th><th>Nước ngoài</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td></td>
                                            <td>Tổng hợp</td>
                                            <td>{{ $r1['total_student'] }}</td>
                                            <td>{{ $r1['total_nu'] }}</td>
                                            <td>{{ $r1['total_res'] }}</td>
                                            <td>{{ $r1['total_res_nu'] }}</td>
                                            <td>{{ $r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan }}</td>
                                            <td>{{ $r1_trained_field->dung_nganh }}</td>
                                            <td>{{ $r1_trained_field->lien_quan }}</td>
                                            <td>{{ $r1_trained_field->khong_lien_quan }}</td>
                                            <td>{{ $r2->where('employment_status', 2)->count() }}</td>
                                            <td>{{ $r2->where('employment_status', 3)->count() }}</td>
                                            <td>{{ $r1['total_res'] > 0 ? round(($r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan) / $r1['total_res'] * 100, 2) . '%' : '0%' }}</td>
                                            <td>{{ $r1['total_student'] > 0 ? round(($r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan) / $r1['total_student'] * 100, 2) . '%' : '0%' }}</td>
                                            <td>{{ $r1_work_area->nha_nuoc }}</td>
                                            <td>{{ $r1_work_area->tu_nhan }}</td>
                                            <td>{{ $r1_work_area->tu_tao }}</td>
                                            <td>{{ $r1_work_area->nuoc_ngoai }}</td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mẫu báo cáo 2 -->
                <div class="tab-pane fade" id="tab2">
                    <div class="card shadow-sm border mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="{{ route('admin.charts.index') }}" class="btn btn-primary me-2">
                                    <i class="bi bi-eye"></i> Xem biểu đồ thống kê
                                </a>
                                <a href="{{ route('surveys.export', ['survey_id' => $currentSurveyId, 'graduation_id' => $selectedGraduationId]) }}" class="btn btn-success">
                                    <i class="bi bi-download"></i> Tải xuống báo cáo
                                </a>
                            </div>
                            <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                                <div class="text-center mb-4">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="mb-3">BAN QUẢN LÝ ĐÀO TẠO</h6>
                                    <h5 class="fw-bold text-decoration-underline mb-0">DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}</h5>
                                </div>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered text-center align-middle mb-0" style="font-size: 13px; min-width: 1500px;">
                                        <thead class="align-middle">
                                        <tr>
                                            <th rowspan="2">Mã sinh viên</th><th rowspan="2">Họ và tên</th><th rowspan="2">Nữ</th><th rowspan="2">Số thẻ CCCD/CMND</th>
                                            <th rowspan="2">Mã ngành đào tạo<br><small>(Ghi bằng số theo mã ngành tuyển sinh của Bộ Giáo dục và Đào tạo)</small></th>
                                            <th colspan="2">Quyết định tốt nghiệp</th><th colspan="2">Thông tin liên hệ</th>
                                            <th rowspan="2">Hình thức khảo sát<br>(Online, điện thoại, email …)</th><th rowspan="2">Có phản hồi</th><th rowspan="2">Ngành</th><th rowspan="2">Khóa học</th>
                                        </tr>
                                        <tr><th>Số Quyết định</th><th>Ngày ký Quyết định</th><th>Điện thoại</th><th>Email</th></tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($studentTab2 as $item)
                                            @php
                                                $graduation = $graduationData->get($item->id);
                                                $res = $responsesByCode->get($item->code);
                                                $major = $majors->get($item->training_industry_id);
                                            @endphp
                                            <tr>
                                                <td>{{ $item->code }}</td>
                                                <td>{{ $item->full_name }}</td>
                                                <td>{{ $item->gender == 'female' ? 'x' : '' }}</td>
                                                <td>{{ $item->citizen_identification }}</td>
                                                <td>{{ optional($major)->code }}</td>
                                                <td>{{ optional($graduation)->certification }}</td>
                                                <td>{{ optional($graduation)->certification_date ? date('d-m-Y', strtotime($graduation->certification_date)) : '' }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td></td>
                                                <td>{{ $res ? 'Có' : 'Không' }}</td>
                                                <td>{{ optional($major)->name }}</td>
                                                <td>{{-- Khóa học cần thêm trường dữ liệu --}}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="13" class="text-center">Không có dữ liệu sinh viên cho đợt khảo sát này.</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mẫu báo cáo 3 -->
                <div class="tab-pane fade" id="tab3">
                    <div class="card shadow-sm border mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="{{ route('admin.charts.index') }}" class="btn btn-primary me-2">
                                    <i class="bi bi-eye"></i> Xem biểu đồ thống kê
                                </a>
                                <a href="{{ route('surveys.export', ['survey_id' => $currentSurveyId, 'graduation_id' => $selectedGraduationId]) }}" class="btn btn-success">
                                    <i class="bi bi-download"></i> Tải xuống báo cáo
                                </a>
                            </div>
                            <div class="border rounded p-3" style="max-height: 800px; overflow: auto;">
                                <div class="text-center mb-4">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="mb-3">BAN QUẢN LÝ ĐÀO TẠO</h6>
                                    <h5 class="fw-bold text-decoration-underline mb-0">DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }} PHẢN HỒI VỀ TÌNH HÌNH VIỆC LÀM</h5>
                                </div>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered text-center align-middle mb-0" style="font-size: 13px; min-width: 3400px;">
                                        <thead>
                                        <tr>
                                            <th rowspan="3">Mã sinh viên</th><th rowspan="3">Họ và tên</th><th rowspan="3">Ngày sinh</th><th rowspan="3">Giới tính</th>
                                            <th rowspan="3">Số thẻ CCCD/CMND</th><th rowspan="3">Mã ngành đào tạo<br><small>(Ghi bằng số theo mã ngành tuyển sinh)</small></th>
                                            <th rowspan="3">Điện thoại</th><th rowspan="3">Email</th><th colspan="5">Tình hình việc làm</th>
                                            <th colspan="4">Khu vực làm việc</th><th rowspan="3">Nơi làm việc (Tỉnh/ Tp)<br>Ghi bằng mã số tỉnh</th>
                                            <th colspan="4">Thời gian có việc làm sau tốt nghiệp</th><th colspan="4">Thu nhập bình quân/1 tháng</th>
                                            <th colspan="3">Kiến thức, kỹ năng từ nhà trường</th><th colspan="5">Hình thức tìm việc làm</th>
                                            <th colspan="8">Kỹ năng mềm cần thiết cho công việc</th><th colspan="7">Khóa học đã tham gia sau khi tốt nghiệp</th>
                                            <th colspan="7">Giải pháp nâng cao tỷ lệ việc làm đúng ngành đào tạo</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Có việc làm</th><th rowspan="2">Tiếp tục học</th><th rowspan="2">Chưa có việc làm</th>
                                            <th>Khu vực nhà nước</th><th>Khu vực tư nhân</th><th>Có yếu tố nước ngoài</th><th>Tự tạo việc làm</th>
                                            <th>Dưới 3 tháng</th><th>Từ 3 tháng đến 6 tháng</th><th>Từ 6 tháng đến 12 tháng</th><th>Trên 12 tháng</th>
                                            <th>Dưới 5 triệu đồng</th><th>Từ 5 triệu đến 10 triệu đồng</th><th>Từ trên 10 triệu đến 15 triệu đồng</th><th>Trên 15 triệu đồng</th>
                                            <th>Đã học được</th><th>Chỉ học được một phần</th><th>Không học được</th>
                                            <th>Do Học viện/khoa giới thiệu</th><th>Bạn bè, người quen giới thiệu</th><th>Tự tìm việc làm</th><th>Tự tạo việc làm</th><th>Hình thức khác</th>
                                            <th>Kỹ năng giao tiếp</th><th>Kỹ năng lãnh đạo</th><th>Kỹ năng thuyết trình</th><th>Kỹ năng Tiếng Anh</th>
                                            <th>Kỹ năng làm việc nhóm</th><th>Kỹ năng tin học</th><th>Kỹ năng viết báo cáo tài liệu</th><th>Khác</th>
                                            <th>Nâng cao kiến thức chuyên môn</th><th>Nâng cao kỹ năng chuyên môn nghiệp vụ</th><th>Nâng cao về kỹ năng công nghệ thông tin</th>
                                            <th>Nâng cao kỹ năng ngoại ngữ</th><th>Phát triển kỹ năng quản lý</th><th>Tiếp tục học lên cao</th><th>Khác</th>
                                            <th>Học viện tổ chức các buổi trao đổi</th><th>Học viện tổ chức chương trình chia sẻ từ cựu sinh viên</th>
                                            <th>Học viện tổ chức trao đổi với nhà tuyển dụng</th><th>Đơn vị tuyển dụng tham gia đào tạo</th>
                                            <th>Chương trình đào tạo được cập nhật</th><th>Tăng cường thực hành tại cơ sở</th><th>Khác</th>
                                        </tr>
                                        <tr><th>Đúng ngành đào tạo</th><th>Liên quan đến ngành đào tạo</th><th>Không liên quan đến ngành đào tạo</th></tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($r2 as $item)
                                            <tr>
                                                <td>{{ $item->code_student }}</td><td>{{ $item->full_name }}</td><td>{{ !empty($item->dob) ? date('d-m-Y', strtotime($item->dob)) : '' }}</td>
                                                <td>{{ $item->gender == 'male' ? 'Nam' : 'Nữ' }}</td><td>{{ $item->identification_card_number }}</td><td>{{ optional($majors->get($item->training_industry_id))->code }}</td>
                                                <td>{{ $item->phone_number }}</td><td>{{ $item->email }}</td>
                                                <td>{{ $item->trained_field == 1 ? 1 : 0 }}</td><td>{{ $item->trained_field == 2 ? 1 : 0 }}</td><td>{{ $item->trained_field == 3 ? 1 : 0 }}</td>
                                                <td>{{ $item->employment_status == 2 ? 1 : 0 }}</td><td>{{ $item->employment_status == 3 ? 1 : 0 }}</td>
                                                <td>{{ $item->work_area == '1' ? 1 : 0 }}</td><td>{{ $item->work_area == '2' ? 1 : 0 }}</td>
                                                <td>{{ $item->work_area == '4' ? 1 : 0 }}</td><td>{{ $item->work_area == '3' ? 1 : 0 }}</td>
                                                <td>{{ $item->city_work_id }}</td>
                                                @foreach (config('config.employed_since', []) as $k => $v) <td>{{ $k == $item->employed_since ? 1 : 0 }}</td> @endforeach
                                                @foreach (config('config.average_income', []) as $k => $v) <td>{{ $k == $item->average_income ? 1 : 0 }}</td> @endforeach
                                                @foreach (config('config.level_knowledge_acquired', []) as $k => $v) <td>{{ $k == $item->level_knowledge_acquired ? 1 : 0 }}</td> @endforeach
                                                @foreach (config('config.recruitment_type', []) as $k => $v) @php $data = json_decode($item->recruitment_type, true); @endphp <td>{{ in_array($k, data_get($data, 'value', [])) ? 1 : 0 }}</td> @endforeach
                                                @foreach (config('config.soft_skills_required', []) as $k => $v) @php $data = json_decode($item->soft_skills_required, true); @endphp <td>{{ in_array($k, data_get($data, 'value', [])) ? 1 : 0 }}</td> @endforeach
                                                @foreach (config('config.must_attended_courses', []) as $k => $v) @php $data = json_decode($item->must_attended_courses, true); @endphp <td>{{ in_array($k, data_get($data, 'value', [])) ? 1 : 0 }}</td> @endforeach
                                                @foreach (config('config.solutions_get_job', []) as $k => $v) @php $data = json_decode($item->solutions_get_job, true); @endphp <td>{{ in_array($k, data_get($data, 'value', [])) ? 1 : 0 }}</td> @endforeach
                                            </tr>
                                        @empty
                                            <tr><td colspan="45" class="text-center">Không có sinh viên nào phản hồi.</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info mt-3">Vui lòng chọn một cuộc khảo sát để xem báo cáo.</div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabLinks = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
            tabLinks.forEach(link => {
                link.addEventListener('click', function() {
                    localStorage.setItem('activeReportTab', this.getAttribute('href'));
                });
            });
            const lastTab = localStorage.getItem('activeReportTab');
            if (lastTab) {
                const triggerEl = document.querySelector(`.nav-link[href="${lastTab}"]`);
                if (triggerEl) new bootstrap.Tab(triggerEl).show();
            }
        });
    </script>
@endsection
