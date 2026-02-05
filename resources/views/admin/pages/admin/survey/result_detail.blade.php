@extends('admin.layouts.no-master')

@section('title', 'Chi tiết phản hồi')

@section('content')
    <style>
        body {
            background-color: #f1f3f4;
        }

        .google-form-style {
            max-width: 800px;
            margin: auto;
            background-color: white;
            padding: 3rem 5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .google-form-style .form-section {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-header-logo {
            width: 60%;
            height: auto;
        }

        .google-form-style label {
            font-weight: 500;
            margin-bottom: 0.4rem;
        }

        .google-form-style input {
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        .google-form-style input:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
        }

        .first-line-indent {
            text-indent: 30px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            font-style: italic;
            font-size: 0.85rem;
        }

        .contact-info span:first-child {
            text-align: center !important;
            padding-right: 0;
        }

        /* Ẩn các câu hỏi employment-details và question-27 mặc định */
        .employment-details,
        .question-27 {
            display: none;
        }

        /* --- Responsive --- */
        @media (max-width: 992px) {
            .google-form-style {
                padding: 2rem 3rem;
            }

            .form-header-logo {
                width: 90px !important;
            }

            .form-header-logo-col {
                flex: 0 0 30%;
                max-width: 30%;
            }

            .form-header-right-col {
                flex: 0 0 70%;
                max-width: 70%;
                text-align: center !important;
            }

            .form-header-right div,
            .form-header-right p {
                font-size: 0.7rem !important;
            }

            .form-header-right h6 {
                font-size: 0.65rem !important;
            }

            .contact-info {
                font-size: 0.65rem;
                align-items: center;
            }

            .first-line-indent {
                text-indent: 18px;
            }
        }

        @media (max-width: 576px) {
            .google-form-style {
                padding: 1rem 1rem;
            }

            .form-header-logo {
                width: 70px !important;
            }

            .form-header-logo-col {
                flex: 0 0 25%;
                max-width: 25%;
            }

            .form-header-right-col {
                flex: 0 0 75%;
                max-width: 75%;
                text-align: center !important;
            }

            .form-header-right div,
            .form-header-right p {
                font-size: 0.5rem !important;
            }

            .form-header-right h6 {
                font-size: 0.6rem !important;
            }

            .contact-info {
                font-size: 0.4rem;
                align-items: center;
            }

            .first-line-indent {
                text-indent: 12px;
            }

            .text-center.mt-5 {
                margin-top: 1rem !important;
            }
        }
    </style>

    <div class="container py-4">
        <div class="google-form-style">
            <!-- Header -->
            <div class="container mt-3 mb-4">
                <div class="row align-items-center">
                    <!-- Cột logo -->
                    <div class="col-5 d-flex justify-content-center align-items-center form-header-logo-col"
                        style="min-height: 130px;">
                        <img src="{{ asset('assets/client/images/logo-vnua.jpg') }}" class="img-fluid form-header-logo"
                            alt="Logo Học viện">
                    </div>

                    <!-- Cột nội dung bên phải -->
                    <div class="col-7 text-end mb-2 position-relative form-header-right form-header-right-col">
                        <div class="fst-italic mb-3" style="font-size: 13px;">
                            Ngày {{ now()->format('d') }} / {{ now()->format('m') }} / {{ now()->format('Y') }}
                        </div>

                        <p class="mb-1 fw-semibold text-uppercase" style="font-size: 15px;">
                            BỘ NÔNG NGHIỆP VÀ PHÁT TRIỂN NÔNG THÔN
                        </p>
                        <h6 class="mb-1 text-uppercase fw-bold">
                            HỌC VIỆN NÔNG NGHIỆP VIỆT NAM
                        </h6>
                        <small class="fst-italic d-flex flex-column contact-info">
                            <span>Trâu Quỳ, Gia Lâm, Hà Nội</span>
                            <span>Điện thoại: 024.62617586 - Fax: 024.62617586</span>
                        </small>
                    </div>

                    <!-- Tiêu đề phiếu (Dynamic) -->
                    <div class="text-center mt-5 form-title">
                        <h5 class="fw-bold text-center mb-3">{{ $survey->title }}</h5>
                        <p class="fw-semibold fst-italic ms-4 mt-4">
                            Thân gửi Anh/Chị cựu sinh viên của Học viện Nông Nghiệp Việt Nam!
                        </p>
                        <p class="text-justify" style="line-height: 1.7; color: #4a5568;">
                            {{ $survey->description }}
                        </p>
                    </div>

                    <!-- Phần thân gửi -->
                    <div class="mt-2">
                        {{-- <p class="fw-semibold fst-italic text-center mt-4">
                            Thân gửi Anh/Chị cựu sinh viên của Học viện Nông Nghiệp Việt Nam!
                        </p> --}}

                        <p class="fst-italic text-center mt-2">
                            Trân trọng cảm ơn sự cộng tác của các Anh/Chị!
                        </p>
                    </div>
                </div>

                <!-- Form Start -->
                <form>
                    <div class="form-section">
                        <h6 class="fw-bold">Phần I. Thông tin cá nhân</h6>

                        <div class="mb-3">
                            <label for="ma_sv">1. Mã sinh viên</label>
                            <input type="text" class="form-control" value="{{ $response->code_student }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="ho_ten">2. Họ và tên</label>
                            <input type="text" class="form-control" value="{{ $response->full_name }}" readonly>
                        </div>

                        <div class="row">
                            {{-- <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">3. Giới tính</label>
                                <input type="text" class="form-control"
                                    value="{{ $response->gender == 'male' ? 'Nam' : 'Nữ' }}" readonly>
                            </div> --}}
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">3. Giới tính</label>
                                <input type="text" class="form-control"
                                    value="{{ $response->gender == 'male' ? 'Nam' : ($response->gender == 'female' ? 'Nữ' : $response->gender) }}"
                                    readonly>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">4. Ngày sinh</label>
                                <input type="date" class="form-control" value="{{ $response->dob }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ma_nghanh_dao_tao">5. Mã ngành đào tạo</label>
                            @php
                                $maNganh = $response->training_industry_id == 1 ? '7480201' : '7480102';
                            @endphp
                            <input type="text" class="form-control" value="{{ $maNganh }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">6. Số căn cước công dân</label>
                            <input type="text" class="form-control mb-2"
                                value="{{ $response->identification_card_number }}" readonly>
                            <label class="form-label">Ngày cấp</label>
                            <input type="date" class="form-control mb-2"
                                value="{{ $response->identification_issuance_date }}" readonly>
                            <label class="form-label">Nơi cấp</label>
                            <input type="text" class="form-control"
                                value="{{ $response->identification_issuance_place }}" readonly>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">7. Khóa học</label>
                                <input type="text" class="form-control" value="{{ $response->course }}" readonly>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">8. Tên ngành được đào tạo</label>
                                @php
                                    $tenNganh =
                                        $response->training_industry_id == 1
                                            ? 'Công Nghệ Thông Tin'
                                            : 'Mạng Máy Tính Và Truyền Thông Dữ Liệu';
                                @endphp
                                <input type="text" class="form-control" readonly value="{{ $tenNganh }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">9. Số điện thoại</label>
                                <input type="text" class="form-control" value="{{ $response->phone_number }}" readonly>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">10. Email</label>
                                <input type="email" class="form-control" value="{{ $response->email }}" readonly>
                            </div>
                        </div>

                        <!-- Câu 11: Tình trạng việc làm -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">11. Anh/Chị vui lòng cho biết tình trạng việc làm hiện tại của
                                Anh/Chị</label>
                            @php
                                $tinh_trang = config('config.tinh_trang');
                                $employedValue = array_key_first($tinh_trang);
                            @endphp
                            @foreach ($tinh_trang as $index => $value)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" disabled type="radio" id="tt_{{ $index }}"
                                        {{ $index == $response->employment_status ? 'checked' : '' }}>
                                    <label class="form-check-label fw-normal" for="tt_{{ $index }}">
                                        {{ $value }}
                                    </label>
                                </div>
                            @endforeach
                            <i class="text-muted small">*Nếu chưa có việc làm hoặc đang tiếp tục học, câu trả lời sẽ chuyển
                                đến câu 27</i>
                        </div>

                        <!-- Các câu hỏi 12-26: Chỉ hiện nếu có việc làm -->
                        <div class="employment-details">
                            <div class="mb-3">
                                <label class="form-label">12. Tên đơn vị tuyển dụng</label>
                                <input type="text" class="form-control" value="{{ $response->recruit_partner_name }}"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">13. Địa chỉ đơn vị</label>
                                <input type="text" class="form-control"
                                    value="{{ $response->recruit_partner_address }}" readonly>
                                <label class="form-label">Tỉnh/Thành phố</label>
                                <input type="text" class="form-control mb-1"
                                    value="{{ $response->recruit_partner_city}}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">14. Thời gian tuyển dụng</label>
                                <input type="date" class="form-control" value="{{ $response->recruit_partner_date }}"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">15. Chức vụ, vị trí việc làm</label>
                                <input type="text" class="form-control"
                                    value="{{ $response->recruit_partner_position }}" readonly>
                            </div>

                            {{-- PHẦN II: NỘI DUNG KHẢO SÁT --}}
                            <h6 class="mb-4 fw-bold">Phần II: Nội dung khảo sát</h6>

                            <!-- Câu 16: Khu vực làm việc -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">16. Đơn vị Anh/Chị đang làm việc thuộc khu vực làm việc
                                    nào?</label>
                                @foreach (config('config.work_area') as $index => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            {{ $response->work_area == $index ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Câu 17: Thời gian có việc -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">17. Sau khi tốt nghiệp, Anh/Chị có việc làm từ khi
                                    nào?</label>
                                @foreach (config('config.employed_since') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            {{ $response->employed_since == $key ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Câu 18: Phù hợp ngành -->
                            {{-- <div class="mb-4">
                                <label class="form-label fw-bold">18. Công việc Anh/Chị đang đảm nhận có phù hợp với ngành
                                    đào tạo không?</label>
                                @foreach (config('config.trained_field') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            {{ $response->trained_field == $key ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div> --}}
                            <div class="mb-4" id="group-question-18">
                                <label class="form-label fw-bold">18. Công việc Anh/Chị đang đảm nhận có phù hợp với ngành
                                    đào tạo không?</label>

                                <div id="q18-data" data-current-value="{{ $response->trained_field }}"></div>

                                @foreach (config('config.trained_field') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input q18-radio" type="radio" name="trained_field"
                                            id="tf_{{ $key }}" value="{{ $key }}"
                                            {{ $response->trained_field == $key ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal" for="tf_{{ $key }}">
                                            {{ $item }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Câu 19: Phù hợp trình độ -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">19. Công việc Anh/Chị đang đảm nhận có phù hợp với trình
                                    độ chuyên môn không?</label>
                                @foreach (config('config.professional_qualification_field') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            {{ $response->professional_qualification_field == $key ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Câu 20: Kiến thức kỹ năng -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">20. Anh/chị có học được các kiến thức và kỹ năng cần
                                    thiết từ nhà trường cho công việc theo ngành tốt nghiệp không?</label>
                                @foreach (config('config.level_knowledge_acquired') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            {{ $response->level_knowledge_acquired == $key ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Câu 21: Lương khởi điểm -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">21. Mức lương khởi điểm của Anh/Chị (triệu
                                    đồng/tháng)</label>
                                <input type="text" class="form-control" value="{{ $response->starting_salary }}"
                                    readonly>
                            </div>

                            <!-- Câu 22: Thu nhập hiện tại -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">22. Mức thu nhập bình quân/tháng tính theo VNĐ của
                                    Anh/Chị hiện nay</label>
                                @foreach (config('config.average_income') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" disabled type="radio"
                                            {{ $response->average_income == $key ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Câu 23: Hình thức tìm việc -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">23. Anh/Chị tìm được việc làm thông qua những hình thức
                                    nào? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                                @php
                                    $tim_viec = config('config.recruitment_type');
                                    $recruit = json_decode($response->recruitment_type, true);
                                @endphp
                                @foreach ($tim_viec as $index => $value)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" disabled
                                            {{ in_array($index, $recruit['value']) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $value }}</label>
                                    </div>
                                @endforeach
                                @if (!empty($recruit['content_other']))
                                    <div class="mt-2">
                                        <input type="text" readonly class="form-control"
                                            value="{{ $recruit['content_other'] }}">
                                    </div>
                                @endif
                            </div>

                            <!-- Câu 24: Hình thức tuyển dụng -->
                            <div class="mb-4">
                                @php
                                    $job_search_method_value = json_decode($response->job_search_method, true);
                                @endphp
                                <label class="form-label fw-bold">24. Anh/chị được tuyển dụng theo hình thức nào?</label>
                                @foreach (config('config.job_search_method') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" disabled
                                            {{ in_array($key, $job_search_method_value['value']) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $item }}</label>
                                    </div>
                                @endforeach
                                @if (!empty($job_search_method_value['content_other']))
                                    <div class="mt-2">
                                        <input type="text" readonly class="form-control"
                                            value="{{ $job_search_method_value['content_other'] }}">
                                    </div>
                                @endif
                            </div>

                            <!-- Câu 25: Kỹ năng mềm -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">25. Trong quá trình làm việc, Anh/Chị cần những kỹ năng
                                    mềm nào sau đây? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                                @php
                                    $ky_nang = config('config.soft_skills_required');
                                    $soft_skills_required = json_decode($response->soft_skills_required, true);
                                @endphp
                                @foreach ($ky_nang as $index => $value)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" disabled
                                            {{ in_array($index, $soft_skills_required['value']) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $value }}</label>
                                    </div>
                                @endforeach
                                @if (!empty($soft_skills_required['content_other']))
                                    <div class="mt-2">
                                        <input type="text" readonly class="form-control"
                                            value="{{ $soft_skills_required['content_other'] }}">
                                    </div>
                                @endif
                            </div>

                            <!-- Câu 26: Khóa học nâng cao -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">26. Sau khi được tuyển dụng, Anh/Chị có phải tham gia
                                    khóa học nâng cao nào dưới đây để đáp ứng công việc không <span class="fw-normal">(Có
                                        thể chọn nhiều lựa chọn)</span></label>
                                @php
                                    $nang_cao = config('config.must_attended_courses');
                                    $must_attended_courses = json_decode($response->must_attended_courses, true);
                                @endphp
                                @foreach ($nang_cao as $index => $value)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" disabled
                                            {{ in_array($index, $must_attended_courses['value']) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $value }}</label>
                                    </div>
                                @endforeach
                                @if (!empty($must_attended_courses['content_other']))
                                    <div class="mt-2">
                                        <input type="text" readonly class="form-control"
                                            value="{{ $must_attended_courses['content_other'] }}">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Câu 27: Luôn hiển thị -->
                        <div class="question-27">
                            <div class="mb-4">
                                <label class="form-label fw-bold">27. Theo Anh/Chị, những giải pháp nào sau đây giúp tăng
                                    tỷ lệ có việc làm đúng ngành của sinh viên tốt nghiệp từ chương trình đào tạo mà Anh/Chị
                                    đã học? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                                @php
                                    $giai_phap = config('config.solutions_get_job');
                                    $solutions_get_job = json_decode($response->solutions_get_job, true);
                                @endphp
                                @foreach ($giai_phap as $index => $value)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" disabled
                                            {{ in_array($index, $solutions_get_job['value']) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal">{{ $value }}</label>
                                    </div>
                                @endforeach
                                @if (!empty($solutions_get_job['content_other']))
                                    <div class="mt-2">
                                        <input type="text" readonly class="form-control"
                                            value="{{ $solutions_get_job['content_other'] }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-4">
                    <button class="btn btn-primary" onclick="downloadPdf()">
                        <i class="bi bi-download"></i> Xuất PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmChangeModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-danger">Xác nhận thay đổi</h5>
                        <button type="button" class="btn-close" id="btn-close-x" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn đang thay đổi câu trả lời cho câu số 18.</p>
                        <p>Hệ thống sẽ cập nhật trực tiếp vào cơ sở dữ liệu.</p>
                        <p class="fw-bold">Bạn có chắc chắn muốn thay đổi không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-cancel-change">Hủy bỏ</button>
                        <button type="button" class="btn btn-primary" id="btn-confirm-change">Đồng ý cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function downloadPdf() {
            const link = document.createElement('a');
            link.href = '{{ route('export_pdf_v2', ['resultId' => $response->id]) }}';
            link.download = '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Logic hiển thị câu hỏi dựa trên employment_status
        document.addEventListener('DOMContentLoaded', function() {
            const employedValue = '{{ array_key_first(config('config.tinh_trang')) }}';
            const selectedStatus = '{{ $response->employment_status }}';

            if (selectedStatus == employedValue) {
                // Nếu chọn "Đang đi làm" -> hiện câu 12-27
                document.querySelector('.employment-details').style.display = 'block';
                document.querySelector('.question-27').style.display = 'block';
            } else {
                // Nếu chọn các lựa chọn khác -> chỉ hiện câu 27
                document.querySelector('.employment-details').style.display = 'none';
                document.querySelector('.question-27').style.display = 'block';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // --- LOGIC CẬP NHẬT CÂU 18 (RADIO) ---
            const radios = document.querySelectorAll('.q18-radio');
            const dataDiv = document.getElementById('q18-data');

            // Lấy Modal và các nút
            const confirmModalEl = document.getElementById('confirmChangeModal');
            const confirmModal = new bootstrap.Modal(confirmModalEl);
            const btnConfirm = document.getElementById('btn-confirm-change');
            const btnCancel = document.getElementById('btn-cancel-change');
            const btnCloseX = document.getElementById('btn-close-x');

            // Biến lưu trạng thái
            let savedValue = dataDiv.getAttribute('data-current-value'); // Giá trị đang có trong DB
            let pendingValue = null; // Giá trị mới người dùng vừa bấm (chờ xác nhận)

            // 1. Bắt sự kiện khi click vào bất kỳ radio nào của câu 18
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Nếu click vào cái đang chọn rồi thì thôi (dù radio mặc định ko trigger change nếu click lại cái cũ)
                    if (this.value == savedValue) return;

                    pendingValue = this.value;
                    confirmModal.show();
                });
            });

            // 2. Hàm khôi phục lại lựa chọn cũ (Dùng khi bấm Hủy)
            function revertSelection() {
                // Tìm radio có value = savedValue và check nó
                const originalRadio = document.querySelector(`.q18-radio[value="${savedValue}"]`);
                if (originalRadio) {
                    originalRadio.checked = true;
                }
            }

            // 3. Xử lý nút Hủy hoặc nút đóng X
            const handleCancel = () => {
                revertSelection();
                confirmModal.hide();
            };

            btnCancel.addEventListener('click', handleCancel);
            btnCloseX.addEventListener('click', handleCancel);

            // 4. Xử lý nút Xác nhận (Gọi AJAX)
            btnConfirm.addEventListener('click', function() {
                // Disable nút để tránh click nhiều lần
                btnConfirm.disabled = true;
                btnConfirm.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';

                // Gọi AJAX
                fetch('{{ route('api.update_question_18') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: '{{ $response->id }}',
                            trained_field: pendingValue
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // --- THÀNH CÔNG ---
                            // 1. Cập nhật giá trị gốc (savedValue) thành giá trị mới
                            savedValue = pendingValue;

                            // 2. Cập nhật lại thuộc tính data để đồng bộ
                            dataDiv.setAttribute('data-current-value', savedValue);

                            alert('Cập nhật thành công!');
                            confirmModal.hide();
                        } else {
                            // --- THẤT BẠI (Server trả về lỗi) ---
                            alert('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại'));
                            revertSelection(); // Quay lại radio cũ
                            confirmModal.hide();
                        }
                    })
                    .catch(error => {
                        // --- LỖI MẠNG HOẶC CODE ---
                        console.error('Error:', error);
                        alert('Lỗi hệ thống, không thể cập nhật.');
                        revertSelection(); // Quay lại radio cũ
                        confirmModal.hide();
                    })
                    .finally(() => {
                        // Reset lại nút bấm
                        btnConfirm.disabled = false;
                        btnConfirm.textContent = 'Đồng ý cập nhật';
                    });
            });

        });
    </script>
@endsection
