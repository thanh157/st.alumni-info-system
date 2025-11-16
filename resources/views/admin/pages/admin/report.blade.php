@extends('admin.layouts.master')

@section('title', 'Báo cáo - Tổng hợp khảo sát việc làm')

@section('content')
    @php
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

        /* Đảm bảo tab nav-link khi active đậm hơn */
        .nav-tabs .nav-link {
            font-weight: 500;
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            font-weight: 700;
            color: #0d6efd;
        }

        /* CSS cho dropdown export */
        .dropdown-item {
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            padding-left: 1.5rem;
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            width: 20px;
        }

        .dropdown-header {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
        }
    </style>
    <div class="container py-4">
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

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            {{-- Tabs (ĐÃ THÊM TAB 4) --}}
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab1">Mẫu báo cáo 1</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab2">Mẫu báo cáo 2</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab3">Mẫu báo cáo 3</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab4">Mẫu báo cáo 4</a>
                </li>
            </ul>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.report.index') }}" class="ms-0 ms-md-auto">
                <div class="d-flex align-items-center gap-2">
                    <label for="survey_id" class="col-form-label fw-semibold mb-0">Khảo sát:</label>
                    <select style="width: 450px;" name="survey_id" id="survey_id" class="form-select custom-select"
                        onchange="this.form.submit()">
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
        </div>


        @if ($currentSurveyId && isset($r1) && !empty($r1))
            @php
                // Chuẩn bị dữ liệu một lần
                $responsesByCode = $r2->keyBy('code_student');
                $studentIdsForGraduation = $studentTab2->pluck('id');
                $graduationData = Illuminate\Support\Facades\DB::table('graduation_student')
                    ->join('graduation', 'graduation_student.graduation_id', '=', 'graduation.id')
                    ->whereIn('graduation_student.student_id', $studentIdsForGraduation)
                    ->select(
                        'graduation_student.student_id',
                        'graduation.certification',
                        'graduation.certification_date',
                    )
                    ->get()
                    ->keyBy('student_id');
                $majors = \App\Models\Major::all()->keyBy('id');
            @endphp

            {{-- CÁC NÚT BẤM CHUNG - ĐÃ CẬP NHẬT --}}
            <div class="d-flex justify-content-end mb-3 gap-2 flex-wrap">
                {{-- Nút xem biểu đồ --}}
                <a href="{{ route('admin.charts.index') }}" class="btn btn-primary">
                    <i class="bi bi-eye"></i> Xem biểu đồ thống kê
                </a>

                {{-- Dropdown xuất báo cáo --}}
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-download"></i> Tải xuống báo cáo
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">Chọn loại báo cáo</h6>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'tab1'])) }}">
                                <i class="bi bi-file-earmark-text"></i> Mẫu báo cáo 1
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'tab2'])) }}">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Mẫu báo cáo 2
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'tab3'])) }}">
                                <i class="bi bi-file-earmark-bar-graph"></i> Mẫu báo cáo 3
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'tab4'])) }}">
                                <i class="bi bi-file-earmark-person"></i> Mẫu báo cáo 4
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold text-primary"
                                href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'all'])) }}">
                                <i class="bi bi-file-earmark-zip"></i> Tải tất cả (4 báo cáo)
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab1">
                    <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                        <div class="text-center mb-4">
                            <div class="text-start mb-2">
                                <div style="display:inline-block; text-align:center;">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="text-uppercase text-decoration-underline mb-1">
                                        KHOA:{{ $facultyName ?? '............' }}</h6>
                                </div>
                            </div>
                            <h5 class="fw-bold text-decoration mb-0 mt-2">
                                BÁO CÁO TÌNH HÌNH VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}
                            </h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle"
                                style="font-size: 12px; min-width: 2000px;">
                                <thead class="align-middle">
                                    <tr>
                                        <th rowspan="3">TT</th>
                                        <th rowspan="3">Mã ngành<br><small>(Ghi theo mã ngành tuyển sinh theo thông tư số
                                                24/2017/TT-BGDDT. Khoa lấy thông tin mã ngành tại mẫu số 02)</small>
                                        </th>
                                        <th rowspan="3">Tên ngành đào tạo</th>
                                        <th colspan="2" rowspan="2"><br>Số sinh viên tốt nghiệp</th>
                                        <th colspan="2" rowspan="2"><br>Số sinh viên phản hồi</th>
                                        {{-- ĐÃ SỬA: Tăng colspan từ 5 lên 6 để chứa cột Tổng số Có việc làm --}}
                                        <th colspan="5">Tình hình việc làm</th>
                                        <th rowspan="3">Tỷ lệ có việc làm / phản hồi</th>
                                        <th rowspan="3">Tỷ lệ có việc làm / tốt nghiệp</th>
                                        <th colspan="4" rowspan="2">Khu vực làm việc</th>
                                        <th rowspan="3">Nơi làm việc<br>(Tỉnh/TP)<br>(Tập hợp theo danh sách sinh viên
                                            phản hồi ở mẫu số 3)</th>
                                    </tr>
                                    <tr>
                                        {{-- ĐÃ SỬA: Tăng colspan từ 3 lên 4 để chứa cột Tổng số Có việc làm --}}
                                        <th colspan="3">Có việc làm</th>
                                        <th rowspan="2">Tiếp tục học</th>
                                        <th rowspan="2">Chưa có việc làm</th>
                                    </tr>
                                    <tr>
                                        <th>Tổng số</th>
                                        <th>Nữ</th>
                                        <th>Tổng số</th>
                                        <th>Nữ</th>
                                        {{-- ĐÃ THÊM: Cột Tổng số cho nhóm Có việc làm --}}
                                        {{-- <th>Tổng số</th> --}}
                                        <th>Đúng ngành đào tạo</th>
                                        <th>Liên quan đến ngành đào tạo</th>
                                        <th>Không liên quan đến ngành đào tạo</th>

                                        <th>Nhà nước</th>
                                        <th>Tư nhân</th>
                                        <th>Tự tạo việc làm</th>
                                        <th>Có yếu tố nước ngoài</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Tổng số có việc làm = đúng ngành + liên quan + không liên quan
                                        $totalCoViecLam =
                                            ($r1_trained_field->dung_nganh ?? 0) +
                                            ($r1_trained_field->lien_quan ?? 0) +
                                            ($r1_trained_field->khong_lien_quan ?? 0);

                                        // Tỷ lệ có việc làm / phản hồi
                                        $tyLeCoViecPhanHoi =
                                            $r1['total_res'] > 0
                                                ? round(($totalCoViecLam / $r1['total_res']) * 100, 2)
                                                : 0;

                                        // Tỷ lệ có việc làm / tốt nghiệp
                                        $tyLeCoViecTotNghiep =
                                            $r1['total_student'] > 0
                                                ? round(($totalCoViecLam / $r1['total_student']) * 100, 2)
                                                : 0;
                                    @endphp
                                    <tr>
                                        <td>1</td>
                                        <td></td>
                                        <td>Tổng hợp</td>
                                        <td>{{ $r1['total_student'] }}</td>
                                        <td>{{ $r1['total_nu'] }}</td>
                                        <td>{{ $r1['total_res'] }}</td>
                                        <td>{{ $r1['total_res_nu'] }}</td>
                                        {{-- CỘT TỔNG SỐ CÓ VIỆC LÀM ĐÃ ĐƯỢC CHUYỂN VÀO ĐÂY --}}
                                        <td>{{ $totalCoViecLam }}</td>
                                        <td>{{ $r1_trained_field->dung_nganh ?? 0 }}</td>
                                        <td>{{ $r1_trained_field->lien_quan ?? 0 }}</td>
                                        <td>{{ $r1_trained_field->khong_lien_quan ?? 0 }}</td>
                                        <td>{{ $r2->where('employment_status', 2)->count() }}</td>
                                        <td>{{ $r2->where('employment_status', 3)->count() }}</td>
                                        <td>{{ $tyLeCoViecPhanHoi }}%</td>
                                        <td>{{ $tyLeCoViecTotNghiep }}%</td>
                                        <td>{{ $r1_work_area->nha_nuoc ?? 0 }}</td>
                                        <td>{{ $r1_work_area->tu_nhan ?? 0 }}</td>
                                        <td>{{ $r1_work_area->tu_tao ?? 0 }}</td>
                                        <td>{{ $r1_work_area->nuoc_ngoai ?? 0 }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab2">
                    <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                        <div class="text-center mb-4">
                            <div class="text-start mb-2">
                                <div style="display:inline-block; text-align:center;">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="text-uppercase text-decoration-underline mb-1">
                                        KHOA:{{ $facultyName ?? '............' }}</h6>
                                </div>
                            </div>
                            <h5 class="fw-bold text-decoration mb-0 mt-2">
                                DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}
                            </h5>
                        </div>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center align-middle mb-0"
                                style="font-size: 13px; min-width: 1500px;">
                                <thead class="align-middle">
                                    <tr>
                                        <th rowspan="2">TT</th>
                                        <th rowspan="2">Mã sinh viên</th>
                                        <th rowspan="2">Họ và tên</th>
                                        <th rowspan="2">Nữ</th>
                                        <th rowspan="2">
                                            Số thẻ CCCD <br>(Do Ban QLĐT, CTCT&CTSV cung cấp. Khoa bổ sung thông tin CCCD
                                            đối với sinh viên chưa có CCCD. Trường hợp CCCD của sinh viên bị sai, Khoa đính
                                            chính thông tin CCCD vào cột ghi chú)
                                        </th>
                                        <th rowspan="2">Mã ngành đào tạo</th>
                                        <th colspan="2">Quyết định tốt nghiệp</th>
                                        <th colspan="2">Thông tin liên hệ</th>
                                        <th rowspan="2">Hình thức khảo sát<br>(Online, điện thoại, email, phỏng vấn, gửi
                                            tài liệu qua bưu điện…)</th>
                                        <th rowspan="2">Có phản hồi<br>(Có phản hồi đánh dấu X)</th>
                                        <th rowspan="2">Ghi chú</th>
                                        <th rowspan="2">Ngành</th>
                                        <th rowspan="2">Khoa</th>
                                    </tr>
                                    <tr>
                                        <th>Số Quyết định</th>
                                        <th>Ngày ký Quyết định</th>
                                        <th>Số điện thoại <br>(Do Ban QLĐT, CTCT&CTSV cung cấp. Khoa bổ sung thông tin SĐT
                                            đối với sinh viên chưa có SĐT. Trường hợp SĐT của sinh viên bị sai, Khoa đính
                                            chính thông tin SĐT vào cột ghi chú)</th>
                                        <th>Email <br>(KHÔNG điền thông tin email của sinh viên do HVN cấp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($studentTab2 as $item)
                                        @php
                                            $graduation = $graduationData->get($item->id);
                                            $res = $responsesByCode->get($item->code);
                                            $major = $majors->get($item->training_industry_id);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->full_name }}</td>
                                            <td>{{ $item->gender == 'female' ? 'x' : '' }}</td>
                                            <td>{{ $item->citizen_identification }}</td>
                                            <td>{{ optional($major)->code }}</td>
                                            <td>{{ optional($graduation)->certification }}</td>
                                            <td>{{ optional($graduation)->certification_date ? date('d-m-Y', strtotime($graduation->certification_date)) : '' }}
                                            </td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td></td>
                                            <td>{{ $res ? 'Có' : 'Không' }}</td>
                                            <td>{{ optional($major)->name }}</td>
                                            <td></td> {{-- Cột "Khóa học" bị thiếu dữ liệu --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center">Không có dữ liệu sinh viên cho đợt khảo
                                                sát này.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab3">
                    <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                        <div class="text-center mb-4">
                            <div class="text-start mb-2">
                                <div style="display:inline-block; text-align:center;">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="text-uppercase text-decoration-underline mb-1">
                                        KHOA:{{ $facultyName ?? '............' }}</h6>
                                </div>
                            </div>
                            <h5 class="fw-bold text-decoration mb-0 mt-2">
                                DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM
                                {{ $schoolYear }} PHẢN HỒI VỀ TÌNH HÌNH VIỆC LÀM
                            </h5>
                        </div>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center align-middle mb-0"
                                style="font-size: 13px; min-width: 3400px;">
                                <thead>
                                    <tr>
                                        <th rowspan="3">TT</th>
                                        <th rowspan="3">Mã sinh viên</th>
                                        <th rowspan="3">Họ và tên</th>
                                        <th rowspan="3">Ngày sinh</th>
                                        <th rowspan="3">Giới tính</th>
                                        <th rowspan="3">Số thẻ CCCD/CMTND</th>
                                        <th rowspan="3">Mã ngành đào tạo<br><small>(Ghi bằng số theo mã ngành tuyển
                                                sinh)</small></th>
                                        <th rowspan="3">Điện thoại</th>
                                        <th rowspan="3">Email</th>
                                        <th colspan="5">Tình hình việc làm</th>
                                        <th colspan="4">Khu vực làm việc</th>
                                        <th rowspan="3">Nơi làm việc<br>(Tỉnh/ Tp)<br>Ghi tên tỉnh</th>
                                        <th colspan="4">Thời gian tìm được việc làm sau tốt nghiệp</th>
                                        <th colspan="3">Sinh viên có học được kiến thức, kỹ năng cần thiết từ nhà trường
                                        </th>
                                        <th rowspan="3">Mức lương khởi điểm/1 tháng (triệu đồng)</th>
                                        <th colspan="4">Thu nhập bình quân/1 tháng</th>
                                        <th colspan="5">Hình thức tìm việc làm</th>
                                        <th colspan="6">Hình thức tuyển dụng</th>
                                        <th colspan="9">Kỹ năng mềm cần thiết cho công việc</th>
                                        <th colspan="6">Khóa học đã tham gia sau khi tốt nghiệp để đáp ứng yêu cầu công
                                            việc
                                        </th>
                                        <th colspan="6">Giải pháp tăng tỷ lệ sinh viên có việc làm đúng ngành đào tạo
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Có việc làm</th>
                                        <th rowspan="2">Tiếp tục học</th>
                                        <th rowspan="2">Chưa có việc làm</th>
                                        <th rowspan="2">Nhà nước</th>
                                        <th rowspan="2">Tư nhân</th>
                                        <th rowspan="2">Tự tạo việc làm</th>
                                        <th rowspan="2">Có yếu tố nước ngoài</th>
                                        <th rowspan="2">Dưới 3 tháng</th>
                                        <th rowspan="2">Từ 3 tháng đến dưới 6 tháng</th>
                                        <th rowspan="2">Từ 6 tháng đến dưới 12 tháng</th>
                                        <th rowspan="2">Từ 12 tháng trở lên</th>
                                        <th rowspan="2">Đã học được</th>
                                        <th rowspan="2">Chỉ học được một phần</th>
                                        <th rowspan="2">Không học được</th>
                                        <th rowspan="2">Dưới 5 triệu đồng</th>
                                        <th rowspan="2">Từ 5 triệu đến 10 triệu đồng</th>
                                        <th rowspan="2">Từ trên 10 triệu đến 15 triệu đồng</th>
                                        <th rowspan="2">Từ 15 triệu đồng trở lên</th>
                                        <th rowspan="2">Do Học viện/khoa giới thiệu</th>
                                        <th rowspan="2">Bạn bè, người quen giới thiệu</th>
                                        <th rowspan="2">Tự tìm việc làm</th>
                                        <th rowspan="2">Tự tạo việc làm</th>
                                        <th rowspan="2">Hình thức khác</th>
                                        <th rowspan="2">Thi tuyển</th>
                                        <th rowspan="2">Hợp đồng</th>
                                        <th rowspan="2">Điều động</th>
                                        <th rowspan="2">Xét tuyển</th>
                                        <th rowspan="2">Biệt phái</th>
                                        <th rowspan="2">Hình thức khác</th>
                                        <th rowspan="2">Kỹ năng giao tiếp</th>
                                        <th rowspan="2">Kỹ năng thuyết trình</th>
                                        <th rowspan="2">Kỹ năng làm việc nhóm</th>
                                        <th rowspan="2">Kỹ năng viết báo cáo tài liệu</th>
                                        <th rowspan="2">Kỹ năng lãnh đạo</th>
                                        <th rowspan="2">Kỹ năng Tiếng Anh</th>
                                        <th rowspan="2">Kỹ năng Tin học</th>
                                        <th rowspan="2">Kỹ năng hội nhập quốc tế</th>
                                        <th rowspan="2">Kỹ năng khác</th>
                                        <th rowspan="2">Nâng cao kiến thức chuyên môn</th>
                                        <th rowspan="2">Nâng cao kỹ năng chuyên môn nghiệp vụ</th>
                                        <th rowspan="2">Nâng cao về kỹ năng công nghệ thông tin</th>
                                        <th rowspan="2">Nâng cao kỹ năng ngoại ngữ</th>
                                        <th rowspan="2">Phát triển kỹ năng quản lý</th>
                                        <th rowspan="2">Tiếp tục học thạc sĩ, tiến sĩ</th>
                                        <th rowspan="2">Học viện tổ chức các buổi trao đổi, chia sẻ kinh nghiệm tìm kiếm
                                            việc làm giữa
                                            cựu sinh viên với sinh viên</th>
                                        <th rowspan="2">Học viện tổ chức các buổi trao đổi giữa đơn vị sử dụng lao động
                                            với sinh viên</th>
                                        </th>
                                        <th rowspan="2">Đơn vị sử dụng lao động tham gia vào quá trình đào tạo</th>
                                        <th rowspan="2">Chương trình đào tạo được điều chỉnh và cập nhật theo nhu cầu
                                            của thị trường lao
                                            động</th>
                                        <th rowspan="2">Tăng cường các hoạt động thực hành và chuyên môn tại cơ sở</th>
                                        <th rowspan="2">Giải pháp khác</th>
                                    </tr>
                                    <tr>
                                        <th>Đúng ngành đào tạo</th>
                                        <th>Liên quan đến ngành đào tạo</th>
                                        <th>Không liên quan đến ngành đào tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($r2 as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->code_student }}</td>
                                            <td>{{ $item->full_name }}</td>
                                            <td>{{ !empty($item->dob) ? date('d-m-Y', strtotime($item->dob)) : '' }}</td>
                                            <td>{{ $item->gender == 'male' ? 'Nam' : 'Nữ' }}</td>
                                            <td>{{ $item->identification_card_number }}</td>
                                            <td>{{ optional($majors->get($item->training_industry_id))->code }}</td>
                                            <td>{{ $item->phone_number }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->trained_field == 1 ? 'x' : '' }}</td>
                                            <td>{{ $item->trained_field == 2 ? 'x' : '' }}</td>
                                            <td>{{ $item->trained_field == 3 ? 'x' : '' }}</td>
                                            <td>{{ $item->employment_status == 2 ? 'x' : '' }}</td>
                                            <td>{{ $item->employment_status == 3 ? 'x' : '' }}</td>
                                            <td>{{ $item->work_area == '1' ? 'x' : '' }}</td>
                                            <td>{{ $item->work_area == '2' ? 'x' : '' }}</td>
                                            <td>{{ $item->work_area == '3' ? 'x' : '' }}</td>
                                            <td>{{ $item->work_area == '4' ? 'x' : '' }}</td>
                                            <td>{{ $item->city_work_id }}</td>
                                            @foreach (config('config.employed_since', []) as $k => $v)
                                                <td>
                                                    {{ $k == $item->employed_since ? 'x' : '' }}</td>
                                            @endforeach
                                            @foreach (config('config.level_knowledge_acquired', []) as $k => $v)
                                                <td>
                                                    {{ $k == $item->level_knowledge_acquired ? 'x' : '' }}</td>
                                            @endforeach
                                            @foreach (config('config.average_income', []) as $k => $v)
                                                <td>
                                                    {{ $k == $item->average_income ? 'x' : '' }}</td>
                                            @endforeach

                                            @foreach (config('config.recruitment_type', []) as $k => $v)
                                                @php $data = json_decode($item->recruitment_type, true); @endphp <td>
                                                    {{ in_array($k, data_get($data, 'value', [])) ? 'x' : '' }}</td>
                                            @endforeach
                                            @foreach (config('config.recruitment_type', []) as $k => $v)
                                                @php $data = json_decode($item->recruitment_type, true); @endphp <td>
                                                    {{ in_array($k, data_get($data, 'value', [])) ? 'x' : '' }}</td>
                                            @endforeach
                                            @foreach (config('job_search_method', []) as $k => $v)
                                                @php $data = json_decode($item->soft_skills_required, true); @endphp <td>
                                                    {{ in_array($k, data_get($data, 'value', [])) ? 'x' : '' }}</td>
                                            @endforeach
                                            @foreach (config('config.soft_skills_required', []) as $k => $v)
                                                @php $data = json_decode($item->must_attended_courses, true); @endphp <td>
                                                    {{ in_array($k, data_get($data, 'value', [])) ? 'x' : '' }}</td>
                                            @endforeach
                                            @foreach (config('config.must_attended_courses', []) as $k => $v)
                                                @php $data = json_decode($item->must_attended_courses, true); @endphp <td>
                                                    {{ in_array($k, data_get($data, 'value', [])) ? 'x' : '' }}</td>
                                            @endforeach
                                            @foreach (config('config.solutions_get_job', []) as $k => $v)
                                                @php $data = json_decode($item->solutions_get_job, true); @endphp <td>
                                                    {{ in_array($k, data_get($data, 'value', [])) ? 'x' : '' }}</td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="45" class="text-center">Không có sinh viên nào phản hồi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab4">
                    <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                        <div class="text-center mb-4">
                            <h5 class="fw-bold text-uppercase mb-0">
                                THÔNG TIN CỰU SINH VIÊN KHOA CÔNG NGHỆ THÔNG TIN
                            </h5>
                        </div>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center align-middle mb-0"
                                style="font-size: 13px; min-width: 4500px;">
                                <thead class="align-middle">
                                    <tr>
                                        <th rowspan="2">STT</th>
                                        <th rowspan="2">Mã SV</th>
                                        <th rowspan="2">Họ và Tên</th>
                                        <th rowspan="2">Ngày sinh</th>
                                        <th rowspan="2">Giới tính</th>
                                        <th rowspan="2">Dân tộc</th>
                                        <th rowspan="2">Nơi ở hiện tại</th>
                                        <th rowspan="2">Quốc tịch</th>
                                        <th rowspan="2">Tên lớp</th>
                                        <th rowspan="2">Khóa học, niên khoá</th>
                                        <th rowspan="2">Tên khoa</th>
                                        <th rowspan="2">Ngành học</th>
                                        <th rowspan="2">Hệ đào tạo</th>
                                        <th colspan="5">Các bậc đã học tại Học viện</th>
                                        <th rowspan="2">SĐT đang dùng</th>
                                        <th rowspan="2">Email (Nếu có)</th>
                                        <th colspan="2">Tình trạng công việc hiện tại</th>
                                        <th rowspan="2">Đơn vị công tác hiện tại</th>
                                        <th rowspan="2">Chức vụ, chức danh hiện tại</th>
                                        <th rowspan="2">Phần thưởng, giải thưởng, bằng khen</th>
                                        <th colspan="2">Tình trạng kết nối với cá nhân, tập thể, đơn vị thuộc Học viện
                                            Nông
                                            nghiệp Việt Nam</th>
                                    </tr>
                                    <tr>
                                        <th>Trung cấp</th>
                                        <th>Cao đẳng</th>
                                        <th>Đại học</th>
                                        <th>Thạc sĩ</th>
                                        <th>Tiến sĩ</th>

                                        <th>Đang công tác</th>
                                        <th>Nghỉ hưu</th>

                                        <th>Chưa kết nối</th>
                                        <th>Đã kết nối theo nhóm lớp, khoá, khoa, Học viện</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($alumniData as $index => $item)
                                        <tr>
                                            {{-- Cols 1-13: Static Info --}}
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->student_code ?? '' }}</td>
                                            <td>{{ $item->full_name ?? '' }}</td>
                                            <td>{{ $item->date_of_birth ? date('d/m/Y', strtotime($item->date_of_birth)) : '' }}
                                            </td>
                                            <td>{{ $item->gender == 'male' ? 'Nam' : ($item->gender == 'female' ? 'Nữ' : '') }}
                                            </td>
                                            <td>{{ $item->ethnicity ?? '' }}</td>
                                            <td>{{ $item->address ?? '' }}</td>
                                            <td>{{ $item->nationality ?? 'Việt Nam' }}</td>
                                            <td>{{ $item->class_name ?? '' }}</td>
                                            <td>{{ $item->course ?? '' }}</td>
                                            <td>{{ $item->faculty_name ?? '' }}</td>
                                            <td>{{ $item->major_name ?? '' }}</td>
                                            <td>{{ $item->training_system ?? '' }}</td>

                                            {{-- Cols 14-18: Các bậc đã học tại Học viện (5 cột) --}}
                                            <td></td> {{-- Trung cấp (14) --}}
                                            <td></td> {{-- Cao đẳng (15) --}}
                                            <td></td> {{-- Đại học (16) --}}
                                            <td></td> {{-- Thạc sĩ (17) --}}
                                            <td></td> {{-- Tiến sĩ (18) --}}

                                            {{-- Cols 19-20: Thông tin liên hệ --}}
                                            <td>{{ $item->phone ?? '' }}</td> {{-- SĐT đang dùng (19) --}}
                                            <td>{{ $item->email ?? '' }}</td> {{-- Email (20) --}}

                                            {{-- Cols 21-22: Tình trạng công việc hiện tại (2 cột) --}}
                                            <td></td> {{-- Đang công tác (21) --}}
                                            <td></td> {{-- Nghỉ hưu (22) --}}

                                            {{-- Cols 23-25: Công việc chi tiết --}}
                                            <td>{{ $item->company_name ?? '' }}</td> {{-- Đơn vị công tác hiện tại (23) --}}
                                            <td>{{ $item->position ?? '' }}</td> {{-- Chức vụ, chức danh hiện tại (24) --}}
                                            <td>{{ $item->awards ?? '' }}</td> {{-- Phần thưởng, giải thưởng, bằng khen (25) --}}

                                            {{-- Cols 26-27: Tình trạng kết nối (2 cột) --}}
                                            <td></td> {{-- Chưa kết nối (26) --}}
                                            <td></td> {{-- Đã kết nối theo nhóm lớp, khoá, khoa, Học viện (27) --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="27" class="text-center py-4">
                                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                                <p class="text-muted mt-2 mb-0">Chưa có dữ liệu cựu sinh viên.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <div class="alert alert-info mt-5">Vui lòng chọn một cuộc khảo sát để xem báo cáo.</div>
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
