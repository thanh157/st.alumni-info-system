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

        .nav-tabs .nav-link {
            font-weight: 500;
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            font-weight: 700;
            color: #0d6efd;
        }

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
            {{-- Tabs --}}
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
                {{-- <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab4">Mẫu báo cáo 4</a>
                </li> --}}
            </ul>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.report.index') }}" class="ms-0 ms-md-auto">
                <div class="d-flex align-items-center gap-2">
                    <label for="survey_id" class="col-form-label fw-semibold mb-0">Khảo sát:</label>
                    <select style="width: 450px;" name="survey_id" id="survey_id" class="form-select custom-select"
                            onchange="this.form.submit()">
                        <option value="">-- Chọn khảo sát --</option>
                        @php
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
                // Dữ liệu chung dùng cho nhiều tab
                $responsesByCode = $r2->keyBy('code_student'); // dùng cho TAB 2
                $majors = \App\Models\Major::all()->keyBy('id'); // dùng cho TAB 3 (mã ngành)
            @endphp

            {{-- CÁC NÚT BẤM CHUNG --}}
            <div class="d-flex justify-content-end mb-3 gap-2 flex-wrap">
                <a href="{{ route('admin.charts.index') }}" class="btn btn-primary">
                    <i class="bi bi-eye"></i> Xem biểu đồ thống kê
                </a>

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
                               href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'tab1'])) }}"
                               download
                               onclick="handleFileDownload(event, this)">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Mẫu báo cáo 1</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'tab2'])) }}"
                               download
                               onclick="handleFileDownload(event, this)">
                                <i class="bi bi-file-earmark-spreadsheet"></i>
                                <span>Mẫu báo cáo 2</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'tab3'])) }}"
                               download
                               onclick="handleFileDownload(event, this)">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                <span>Mẫu báo cáo 3</span>
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item fw-bold text-primary download-link"
                               href="{{ route('surveys.export', array_merge(request()->all(), ['type' => 'all'])) }}"
                               download
                               onclick="handleFileDownload(event, this)">
                                <i class="bi bi-file-earmark-zip"></i>
                                <span>Tải tất cả (3 báo cáo)</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                {{-- TAB 1 --}}
                <div class="tab-pane fade show active" id="tab1">
                    <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                        <div class="text-center mb-4">
                            <div class="text-start mb-2">
                                <div style="display:inline-block; text-align:center;">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="text-uppercase text-decoration-underline mb-1">
                                        KHOA: CÔNG NGHỆ THÔNG TIN
                                    </h6>
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
                                        <th rowspan="3">Mã ngành<br>
                                            <small>(Ghi theo mã ngành tuyển sinh theo thông tư số
                                                24/2017/TT-BGDDT. Khoa lấy thông tin mã ngành tại mẫu số 02)</small>
                                        </th>
                                        <th rowspan="3">Tên ngành đào tạo</th>
                                        <th colspan="2" rowspan="2"><br>Số sinh viên tốt nghiệp</th>
                                        <th colspan="2" rowspan="2"><br>Số sinh viên phản hồi</th>
                                        <th colspan="5">Tình hình việc làm</th>
                                        <th rowspan="3">Tỷ lệ có việc làm / phản hồi</th>
                                        <th rowspan="3">Tỷ lệ có việc làm / tốt nghiệp</th>
                                        <th colspan="4" rowspan="2">Khu vực làm việc</th>
                                        <th rowspan="3">Nơi làm việc<br>(Tỉnh/TP)<br>
                                            (Tập hợp theo danh sách sinh viên phản hồi ở mẫu số 3)
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Có việc làm</th>
                                        <th rowspan="2">Tiếp tục học</th>
                                        <th rowspan="2">Chưa có việc làm</th>
                                    </tr>
                                    <tr>
                                        <th>Tổng số</th>
                                        <th>Nữ</th>
                                        <th>Tổng số</th>
                                        <th>Nữ</th>
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
                                        $majorsRows = collect($r1Majors);

                                        $tong_total_student  = $r1['total_student'] ?? 0;
                                        $tong_total_nu       = $r1['total_nu'] ?? 0;
                                        $tong_total_res      = $r1['total_res'] ?? 0;
                                        $tong_total_res_nu   = $r1['total_res_nu'] ?? 0;

                                        $tong_dung_nganh      = $majorsRows->sum('dung_nganh');
                                        $tong_lien_quan       = $majorsRows->sum('lien_quan');
                                        $tong_khong_lien_quan = $majorsRows->sum('khong_lien_quan');

                                        $tong_tiep_tuc_hoc = $majorsRows->sum('tiep_tuc_hoc');
                                        $tong_chua_co_viec = $majorsRows->sum('chua_co_viec');

                                        $tong_nha_nuoc   = $majorsRows->sum('nha_nuoc');
                                        $tong_tu_nhan    = $majorsRows->sum('tu_nhan');
                                        $tong_tu_tao     = $majorsRows->sum('tu_tao');
                                        $tong_nuoc_ngoai = $majorsRows->sum('nuoc_ngoai');

                                        $tong_co_viec_lam = $tong_dung_nganh + $tong_lien_quan + $tong_khong_lien_quan + $tong_tiep_tuc_hoc;

                                        $tong_ty_le_co_viec_phan_hoi =
                                            $tong_total_res > 0
                                            ? round(($tong_co_viec_lam / $tong_total_res) * 100, 2)
                                            : 0;

                                        $tong_ty_le_co_viec_tot_nghiep =
                                            $tong_total_student > 0
                                            ? round(($tong_co_viec_lam / $tong_total_student) * 100, 2)
                                            : 0;
                                    @endphp

                                    @foreach ($majorsRows as $index => $row)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $row['major_code'] }}</td>
                                            <td>{{ $row['major_name'] }}</td>

                                            <td>{{ $row['total_student'] }}</td>
                                            <td>{{ $row['total_nu'] }}</td>
                                            <td>{{ $row['total_res'] }}</td>
                                            <td>{{ $row['total_res_nu'] }}</td>

                                            <td>{{ $row['dung_nganh'] }}</td>
                                            <td>{{ $row['lien_quan'] }}</td>
                                            <td>{{ $row['khong_lien_quan'] }}</td>
                                            <td>{{ $row['tiep_tuc_hoc'] }}</td>
                                            <td>{{ $row['chua_co_viec'] }}</td>

                                            <td>{{ $row['ty_le_co_viec_phan_hoi'] }}%</td>
                                            <td>{{ $row['ty_le_co_viec_tot_nghiep'] }}%</td>

                                            <td>{{ $row['nha_nuoc'] }}</td>
                                            <td>{{ $row['tu_nhan'] }}</td>
                                            <td>{{ $row['tu_tao'] }}</td>
                                            <td>{{ $row['nuoc_ngoai'] }}</td>
                                            <td style="white-space: pre-line;">{{ $row['top_city'] }}</td>
                                        </tr>
                                    @endforeach

                                    <tr class="fw-bold">
                                        <td>{{ $majorsRows->count() + 1 }}</td>
                                        <td></td>
                                        <td>TỔNG HỢP</td>

                                        <td>{{ $tong_total_student }}</td>
                                        <td>{{ $tong_total_nu }}</td>
                                        <td>{{ $tong_total_res }}</td>
                                        <td>{{ $tong_total_res_nu }}</td>

                                        <td>{{ $tong_dung_nganh }}</td>
                                        <td>{{ $tong_lien_quan }}</td>
                                        <td>{{ $tong_khong_lien_quan }}</td>
                                        <td>{{ $tong_tiep_tuc_hoc }}</td>
                                        <td>{{ $tong_chua_co_viec }}</td>

                                        <td>{{ $tong_ty_le_co_viec_phan_hoi }}%</td>
                                        <td>{{ $tong_ty_le_co_viec_tot_nghiep }}%</td>

                                        <td>{{ $tong_nha_nuoc }}</td>
                                        <td>{{ $tong_tu_nhan }}</td>
                                        <td>{{ $tong_tu_tao }}</td>
                                        <td>{{ $tong_nuoc_ngoai }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- TAB 2 --}}
                <div class="tab-pane fade" id="tab2">
                    <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                        <div class="text-center mb-4">
                            <div class="text-start mb-2">
                                <div style="display:inline-block; text-align:center;">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="text-uppercase text-decoration-underline mb-1">
                                        KHOA: CÔNG NGHỆ THÔNG TIN</h6>
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
                                            Số thẻ CCCD <br>
                                            (Do Ban QLĐT, CTCT&CTSV cung cấp. Khoa bổ sung thông tin CCCD
                                            đối với sinh viên chưa có CCCD. Trường hợp CCCD của sinh viên bị sai,
                                            Khoa đính chính thông tin CCCD vào cột ghi chú)
                                        </th>
                                        <th rowspan="2">Mã ngành đào tạo</th>
                                        <th colspan="2">Quyết định tốt nghiệp</th>
                                        <th colspan="2">Thông tin liên hệ</th>
                                        <th rowspan="2">
                                            Hình thức khảo sát<br>
                                            (Online, điện thoại, email, phỏng vấn, gửi tài liệu qua bưu điện…)
                                        </th>
                                        <th rowspan="2">
                                            Có phản hồi<br>(Có phản hồi đánh dấu X)
                                        </th>
                                        <th rowspan="2">Ghi chú</th>
                                        <th rowspan="2">Ngành</th>
                                        <th rowspan="2">Khoa</th>
                                    </tr>
                                    <tr>
                                        <th>Số Quyết định</th>
                                        <th>Ngày ký Quyết định</th>
                                        <th>
                                            Số điện thoại <br>
                                            (Do Ban QLĐT, CTCT&CTSV cung cấp. Khoa bổ sung thông tin SĐT
                                            đối với sinh viên chưa có SĐT. Trường hợp SĐT của sinh viên bị sai,
                                            Khoa đính chính thông tin SĐT vào cột ghi chú)
                                        </th>
                                        <th>Email <br>(KHÔNG điền thông tin email của sinh viên do HVN cấp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($studentTab2 as $item)
                                    @php
                                        // Kiểm tra sinh viên này có phản hồi trong bảng employ không
                                        $hasResponse = $responsesByCode->has($item->code ?? '');

                                        $studentCode = $item->code ?? '';
        
                                        // Lấy object phản hồi từ RAM (đã keyBy ở trên)
                                        $responseItem = $responsesByCode->get($studentCode);
                                        $hasResponse = !is_null($responseItem);

                                        // --- 1. Xử lý CCCD ---
                                        $cccd = $item->citizen_identification; // Mặc định lấy API
                                        // Nếu API rỗng VÀ có phản hồi VÀ phản hồi có CCCD -> Lấy fallback từ DB
                                        if (empty($cccd) && $hasResponse && !empty($responseItem->identification_card_number)) {
                                            $cccd = $responseItem->identification_card_number;
                                        }

                                        // --- 2. Xử lý SĐT, Email và Ghi chú ---
                                        $noteParts = []; // Mảng để chứa text thêm vào ghi chú (ví dụ: ['SĐT', 'Email'])
                                        
                                        // -- Xử lý Phone --
                                        $phone = $item->phone ?? ''; // Mặc định lấy API
                                        // Nếu có phản hồi VÀ trong phản hồi có SĐT -> Lấy SĐT khảo sát & đánh dấu ghi chú
                                        if ($hasResponse && !empty($responseItem->phone_number)) {
                                            $phone = $responseItem->phone_number;
                                            $noteParts[] = 'SĐT';
                                        }

                                        // -- Xử lý Email --
                                        $email = $item->email ?? ''; // Mặc định lấy API
                                        // Nếu có phản hồi VÀ trong phản hồi có Email -> Lấy Email khảo sát & đánh dấu ghi chú
                                        if ($hasResponse && !empty($responseItem->email)) {
                                            $email = $responseItem->email;
                                            $noteParts[] = 'Email';
                                        }

                                        // -- Tạo chuỗi Ghi chú hoàn chỉnh --
                                        $originalNote = $item->note ?? ''; // Ghi chú gốc từ API
                                        $addedNote = implode(', ', $noteParts); // Nối mảng thành chuỗi: "SĐT, Email"

                                        $finalNote = $originalNote;
                                        if (!empty($addedNote)) {
                                            // Nếu có ghi chú gốc thì thêm dấu phẩy, không thì gán luôn
                                            $finalNote = !empty($finalNote) ? ($finalNote . ', ' . $addedNote) : $addedNote;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        {{-- Mã sinh viên --}}
                                        <td>{{ $item->code ?? '' }}</td>

                                        {{-- Họ và tên --}}
                                        <td>{{ $item->full_name ?? '' }}</td>

                                        {{-- Nữ --}}
                                        <td>{{ ($item->gender ?? '') === 'female' ? 'X' : '' }}</td>

                                        {{-- CCCD --}}
                                        <td>{{ $cccd }}</td>

                                        {{-- Mã ngành đào tạo (API trả về industry_code) --}}
                                        <td>{{ $item->industry_code ?? '' }}</td>

                                        <td>{{ $item->certification }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->certification_date)->format('d/m/Y') }}</td>

                                        {{-- Số điện thoại --}}
                                        <td>{{ $phone }}</td>

                                        {{-- Email --}}
                                        <td>{{ $email }}</td>

                                        {{-- Hình thức khảo sát (hiện tại chưa có nguồn) --}}
                                        <td>Online</td>

                                        {{-- Có phản hồi --}}
                                        <td>{{ $hasResponse ? 'X' : '' }}</td>

                                        {{-- Ghi chú --}}
                                        <td>{{ $finalNote }}</td>

                                        {{-- Ngành --}}
                                        <td>{{ $item->industry_name ?? '' }}</td>

                                        {{-- Khoa --}}
                                        <td>Công nghệ thông tin</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" class="text-center">
                                            Không có dữ liệu sinh viên cho đợt khảo sát này.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- TAB 3 --}}
                <div class="tab-pane fade" id="tab3">
                    <div class="border rounded p-3" style="max-height: 800px; overflow-y: auto;">
                        <div class="text-center mb-4">
                            <div class="text-start mb-2">
                                <div style="display:inline-block; text-align:center;">
                                    <h6 class="text-uppercase mb-1">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h6>
                                    <h6 class="text-uppercase text-decoration-underline mb-1">
                                        KHOA: CÔNG NGHỆ THÔNG TIN</h6>
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
                                        <th rowspan="3">Mã ngành đào tạo<br>
                                            <small>(Ghi bằng số theo mã ngành tuyển sinh)</small>
                                        </th>
                                        <th rowspan="3">Điện thoại</th>
                                        <th rowspan="3">Email</th>
                                        <th colspan="5">Tình hình việc làm</th>
                                        <th colspan="4">Khu vực làm việc</th>
                                        <th rowspan="3">Nơi làm việc<br>(Tỉnh/ Tp)<br>Ghi tên tỉnh</th>
                                        <th colspan="4">Thời gian tìm được việc làm sau tốt nghiệp</th>
                                        <th colspan="3">Sinh viên có học được kiến thức, kỹ năng cần thiết từ nhà trường</th>
                                        <th rowspan="3">Mức lương khởi điểm/1 tháng (triệu đồng)</th>
                                        <th colspan="4">Thu nhập bình quân/1 tháng</th>
                                        <th colspan="5">Hình thức tìm việc làm</th>
                                        <th colspan="6">Hình thức tuyển dụng</th>
                                        <th colspan="9">Kỹ năng mềm cần thiết cho công việc</th>
                                        <th colspan="6">Khóa học đã tham gia sau khi tốt nghiệp để đáp ứng yêu cầu công việc</th>
                                        <th colspan="6">Giải pháp tăng tỷ lệ sinh viên có việc làm đúng ngành đào tạo</th>
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
                                            việc làm giữa cựu sinh viên với sinh viên</th>
                                        <th rowspan="2">Học viện tổ chức các buổi trao đổi giữa đơn vị sử dụng lao động
                                            với sinh viên</th>
                                        <th rowspan="2">Đơn vị sử dụng lao động tham gia vào quá trình đào tạo</th>
                                        <th rowspan="2">Chương trình đào tạo được điều chỉnh và cập nhật theo nhu cầu
                                            của thị trường lao động</th>
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
                                        @php
                                            // Cắt tỉnh/thành phố từ địa chỉ nơi làm việc
                                            $city = '';
                                            if (!empty($item->recruit_partner_address)) {
                                                $parts = explode(',', $item->recruit_partner_address);
                                                $city = trim(end($parts));
                                            }

                                            $search    = json_decode($item->job_search_method ?? '[]', true) ?? [];
                                            $recruit   = json_decode($item->recruitment_type ?? '[]', true) ?? [];
                                            $skills    = json_decode($item->soft_skills_required ?? '[]', true) ?? [];
                                            $courses   = json_decode($item->must_attended_courses ?? '[]', true) ?? [];
                                            $solutions = json_decode($item->solutions_get_job ?? '[]', true) ?? [];

                                            $searchValues    = data_get($search, 'value', []);
                                            $recruitValues   = data_get($recruit, 'value', []);
                                            $skillValues     = data_get($skills, 'value', []);
                                            $courseValues    = data_get($courses, 'value', []);
                                            $solutionValues  = data_get($solutions, 'value', []);
                                        @endphp

                                        <tr>
                                            {{-- 1–9: Thông tin cá nhân --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->code_student }}</td>
                                            <td>{{ $item->full_name }}</td>
                                            <td>{{ !empty($item->dob) ? date('d/m/Y', strtotime($item->dob)) : '' }}</td>
                                            <td>{{ $item->gender == 'Nam' ? 'Nam' : 'Nữ' }}</td>
                                            <td>{{ $item->identification_card_number }}</td>
                                            <td>{{ optional($majors->get($item->training_industry_id))->code }}</td>
                                            <td>{{ $item->phone_number }}</td>
                                            <td>{{ $item->email }}</td>

                                            {{-- 10–14: Tình hình việc làm --}}
                                            <td>{{ $item->trained_field == 1 ? 'x' : '' }}</td>
                                            <td>{{ $item->trained_field == 2 ? 'x' : '' }}</td>
                                            <td>{{ $item->trained_field == 3 ? 'x' : '' }}</td>
                                            <td>{{ $item->employment_status == 2 ? 'x' : '' }}</td>
                                            <td>{{ $item->employment_status == 3 ? 'x' : '' }}</td>

                                            {{-- 15–18: Khu vực làm việc --}}
                                            @for ($i = 1; $i <= 4; $i++)
                                                <td>{{ $item->work_area == $i ? 'x' : '' }}</td>
                                            @endfor

                                            {{-- 19: Nơi làm việc (tỉnh/thành) --}}
                                            <td>{{ $city }}</td>

                                            {{-- 20–23: Thời gian tìm được việc làm --}}
                                            @for ($i = 1; $i <= 4; $i++)
                                                <td>{{ $item->employed_since == $i ? 'x' : '' }}</td>
                                            @endfor

                                            {{-- 24–26: Kiến thức, kỹ năng đã học --}}
                                            @for ($i = 1; $i <= 3; $i++)
                                                <td>{{ $item->level_knowledge_acquired == $i ? 'x' : '' }}</td>
                                            @endfor

                                            {{-- 27: Mức lương khởi điểm --}}
                                            <td>{{ $item->starting_salary ?? 0 }}</td>

                                            {{-- 28–31: Thu nhập bình quân --}}
                                            @for ($i = 1; $i <= 4; $i++)
                                                <td>{{ $item->average_income == $i ? 'x' : '' }}</td>
                                            @endfor

                                            {{-- 32–36: Hình thức tìm việc làm --}}
                                            @for ($i = 1; $i <= 5; $i++)
                                                <td>{{ in_array($i, $searchValues) ? 'x' : '' }}</td>
                                            @endfor

                                            {{-- 37–42: Hình thức tuyển dụng --}}
                                            @for ($i = 1; $i <= 6; $i++)
                                                <td>{{ in_array($i, $recruitValues) ? 'x' : '' }}</td>
                                            @endfor

                                            {{-- 43–51: Kỹ năng mềm --}}
                                            @for ($i = 1; $i <= 9; $i++)
                                                <td>{{ in_array($i, $skillValues) ? 'x' : '' }}</td>
                                            @endfor

                                            {{-- 52–57: Khóa học đã tham gia --}}
                                            @for ($i = 1; $i <= 6; $i++)
                                                <td>{{ in_array($i, $courseValues) ? 'x' : '' }}</td>
                                            @endfor

                                            {{-- 58–63: Giải pháp --}}
                                            @for ($i = 1; $i <= 6; $i++)
                                                <td>{{ in_array($i, $solutionValues) ? 'x' : '' }}</td>
                                            @endfor
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="63" class="text-center">Không có sinh viên nào phản hồi.</td>
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
        document.addEventListener("DOMContentLoaded", function () {
            const tabLinks = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
            tabLinks.forEach(link => {
                link.addEventListener('click', function () {
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