@extends('admin.layouts.master')

@section('title', 'Xem trước - Form Khảo Sát')

@section('content')
    <style>
        body {
            background-color: #f1f3f4;
        }

        .preview-mode .btn {
            pointer-events: none;
            opacity: 0.6;
        }

        /* CHO PHÉP nút quay lại hoạt động */
        .preview-mode .btn-back {
            pointer-events: auto !important;
            opacity: 1 !important;
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

        .google-form-style input,
        .google-form-style select {
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        .google-form-style input:focus,
        .google-form-style select:focus {
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

        /* Preview Badge */
        .preview-badge {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            font-weight: 600;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .preview-badge i {
            font-size: 1.2rem;
        }

        /* Disabled form style */
        .preview-mode input,
        .preview-mode select,
        .preview-mode textarea {
            background-color: #f8f9fa !important;
            cursor: not-allowed;
        }

        .preview-mode .btn {
            pointer-events: none;
            opacity: 0.6;
        }

        /* Responsive */
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

            .preview-badge {
                top: 10px;
                right: 10px;
                padding: 8px 16px;
                font-size: 0.85rem;
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

            .preview-badge {
                top: 5px;
                right: 5px;
                padding: 6px 12px;
                font-size: 0.75rem;
            }
        }
    </style>

    <!-- Preview Badge -->
    <div class="preview-badge">
        <i class="bi bi-eye-fill"></i>
        <span>CHẾ ĐỘ XEM TRƯỚC</span>
    </div>

    <div class="container py-4">
        <div class="google-form-style preview-mode">
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
                            BỘ NÔNG NGHIỆP VÀ MÔI TRƯỜNG
                        </p>
                        <h6 class="mb-1 text-uppercase fw-bold">
                            HỌC VIỆN NÔNG NGHIỆP VIỆT NAM
                        </h6>
                        <small class="fst-italic d-flex flex-column contact-info">
                            <span>Xã Gia Lâm, Thành phố Hà Nội</span>
                            <span>Điện thoại: 024.62617586 - Fax: 024.62617586</span>
                        </small>
                    </div>

                    <!-- Tiêu đề phiếu -->
                    <div class="text-center mt-5 form-title">
                         <h5 class="fw-bold text-center mb-3">{{ $survey->title }}</h5>
                    </div>

                    <!-- Phần thân gửi -->
                    <div class="mt-2">
                        <p class="fw-semibold fst-italic ms-4 mt-4">
                            Thân gửi Anh/Chị cựu sinh viên của Học viện Nông Nghiệp Việt Nam!
                        </p>

                        <p class="text-justify fst-italic first-line-indent">
                            {{ $survey->description}}
                        </p>

                        <p class="fst-italic ms-4 mt-2">
                            Trân trọng cảm ơn sự cộng tác của các Anh/Chị!
                        </p>
                    </div>
                </div>

                <form id="preview-form">
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Đây là chế độ xem trước dành cho quản trị viên. Tất cả các trường đều bị vô hiệu hóa.
                    </div>

                    <div class="form-section">
                        <h6 class="fw-bold">Phần I. Thông tin cá nhân</h6>

                        <div class="mb-3">
                            <label for="ma_sv">1. Mã sinh viên</label>
                            <input type="text" class="form-control" disabled placeholder="">
                        </div>

                        <div class="mb-3">
                            <label for="ho_ten">2. Họ và tên</label>
                            <input type="text" class="form-control" disabled placeholder="">
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">3. Giới tính</label>
                                <input type="text" class="form-control" disabled placeholder="">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">4. Ngày sinh</label>
                                <input type="date" class="form-control" disabled placeholder="">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ma_nghanh_dao_tao">5. Mã ngành đào tạo</label>
                            <select class="form-control" disabled>
                                <option value="" selected>-- Chọn mã ngành đào tạo --</option>
                                <option value="7480201">7480201 - Công nghệ thông tin</option>
                                <option value="7480102">7480102 - Mạng máy tính và truyền thông dữ liệu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">6. Số căn cước công dân</label>
                            <input type="text" class="form-control mb-2" disabled placeholder="Nhập số CCCD">
                            <label class="form-label">Cấp ngày:</label>
                            <input type="date" class="form-control mb-2" disabled>
                            <label class="form-label">Tại:</label>
                            <input type="text" class="form-control" disabled placeholder="Nhập nơi cấp">
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">7. Khóa học</label>
                                  <input type="text" class="form-control" placeholder="Nhập khóa học" name="course"
                                    required>
                            </div>
                             <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">8. Tên ngành được đào tạo</label>
                                <input type="text" id="ten_nganh_hien_thi" class="form-control" placeholder=""
                                    readonly>
                                <input type="hidden" name="training_industry_id" id="training_industry_id">
                            </div>
                            <script>
                                const maNgheSelect = document.getElementById('ma_nghanh_dao_tao'); // mã ngành bạn đã có ở câu 5
                                const tenNganhDisplay = document.getElementById('ten_nganh_hien_thi'); // hiển thị
                                const tenNganhHidden = document.getElementById('training_industry_id'); // gửi về server

                                // Map mã ngành -> tên ngành + ID trong DB
                                const majorMap = {
                                    "7480201": {
                                        id: 1,
                                        name: "Công nghệ thông tin"
                                    },
                                    "7480102": {
                                        id: 2,
                                        name: "Mạng máy tính và truyền thông dữ liệu"
                                    }
                                };

                                maNgheSelect.addEventListener("change", function() {
                                    const code = this.value;

                                    if (majorMap[code]) {
                                        tenNganhDisplay.value = majorMap[code].name; // hiện tên ngành
                                        tenNganhHidden.value = majorMap[code].id; // gửi ID về server
                                    } else {
                                        tenNganhDisplay.value = "";
                                        tenNganhHidden.value = "";
                                    }
                                });
                            </script>

                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">9. Số điện thoại</label>
                              <input type="text" class="form-control" placeholder="Nhập số điện thoại">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">10. Email</label>
                                 <input type="email" class="form-control" placeholder="Nhập email" name="email">
                            </div>
                        </div>

                        <!-- 11. Tình trạng việc làm -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">11. Anh/Chị vui lòng cho biết tình trạng việc làm hiện
                                    tại của Anh/Chị</label>
                            @php $tinh_trang = config('config.tinh_trang', ['Đang đi làm', 'Chưa có việc làm', 'Đang học tiếp']); @endphp
                            @foreach ($tinh_trang as $index => $value)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" disabled id="tt_{{ $index }}">
                                    <label class="form-check-label fw-normal"
                                        for="tt_{{ $index }}">{{ $value }}</label>
                                </div>
                            @endforeach
                            <i class="question-10" style="font-size: 14px; color:rgb(94, 6, 6)">
                              *Nếu chưa có việc làm
                                    hoặc
                                    đang tiếp tục học, anh/chị trả lời tiếp câu 27.
                                    Nếu đã có việc làm, anh/chị trả lời tiếp các câu sau
                            </i>
                        </div>

                        <div class="employment-details">
                            <div class="mb-3">
                                <label class="form-label">12. Tên đơn vị tuyển dụng</label>
                               <input type="text" class="form-control" placeholder="Nhập tên công ty / tổ chức">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">13. Địa chỉ đơn vị</label>
                                <input type="text" class="form-control mb-1" disabled
                                    placeholder="VD: Khu 2 Hoàng Khương, Thanh Ba, Phú Thọ">
                                <label class="form-label">Tỉnh/Thành phố</label>
                                <input type="text" class="form-control mb-1" disabled placeholder="VD: Hà Nội">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">14. Thời gian tuyển dụng</label>
                                <input type="date" class="form-control" disabled
                                    placeholder="Nhập ngày/tháng/năm tuyển dụng (vd: 01/01/2025)">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">15. Chức vụ, vị trí việc làm</label>
                                <input type="text" class="form-control" disabled placeholder="">
                            </div>

                            <h6 class="mb-4 fw-bold">Phần II: Nội dung khảo sát</h6>

                            <div class="mb-4">
                                <label class="form-label fw-bold">16. Đơn vị Anh/Chị đang làm việc thuộc khu vực làm
                                        việc
                                        nào?</label>
                                @foreach (config('config.work_area') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">17. Sau khi tốt nghiệp, Anh/Chị có việc làm từ khi
                                    nào?</label>
                                @foreach (config('config.employed_since') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">18. Công việc Anh/Chị đang đảm nhận có phù hợp với
                                    ngành
                                    đào
                                    tạo
                                    không?</label>
                                @foreach (config('config.trained_field') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">19. Công việc Anh/Chị đang đảm nhận có phù hợp với
                                    trình
                                    độ
                                    chuyên môn không?</label>
                                @foreach (config('config.professional_qualification_field') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>


                            <div class="mb-4">
                                <label class="form-label fw-bold">20. Anh/chị có học được các kiến thức và kỹ năng cần
                                    thiết từ
                                    nhà trường cho công việc theo ngành tốt nghiệp không?</label>
                                @foreach (config('config.level_knowledge_acquired') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">21. Mức lương khởi điểm của Anh/Chị (triệu
                                    đồng/tháng)</label>
                                <input type="text" class="form-control" disabled placeholder="">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">22. Mức thu nhập bình quân/tháng tính theo VNĐ của
                                        Anh/Chị
                                        hiện nay</label>
                                @foreach (config('config.average_income') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">23. Anh/Chị tìm được việc làm thông qua những hình
                                    thức
                                    nào?
                                    <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span>
                                </label>
                                @foreach (config('config.recruitment_type') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4"><label class="form-label fw-bold">24. Anh/chị được tuyển dụng theo hình
                                    thức
                                    nào?</label>
                                @foreach (config('config.job_search_method') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">25. Trong quá trình làm việc, Anh/Chị cần những kỹ
                                    năng
                                    mềm
                                    nào
                                    sau đây? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                                @foreach (config('config.soft_skills_required') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">26. Sau khi được tuyển dụng, Anh/Chị có phải tham gia
                                    khóa
                                    học
                                    nâng cao nào dưới đây để đáp ứng công việc không <span class="fw-normal">(Có thể
                                        chọn
                                        nhiều
                                        lựa
                                        chọn)</span></label>
                                @foreach (config('config.must_attended_courses') as $key => $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                            id="kv_{{ $key }}">
                                        <label class="form-check-label fw-normal"
                                            for="kv_{{ $key }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">27. Theo Anh/Chị, những giải pháp nào sau đây giúp tăng
                                tỷ lệ
                                có
                                việc làm đúng ngành của sinh viên tốt nghiệp từ chương trình đào tạo mà Anh/Chị đã học?
                                <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span>
                            </label>
                            @foreach (config('config.solutions_get_job') as $key => $item)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" disabled id="kv_{{ $key }}">
                                    <label class="form-check-label fw-normal"
                                        for="kv_{{ $key }}">{{ $item }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="alert alert-info mt-4" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Đây là chế độ xem trước. Form thực tế sẽ yêu cầu sinh viên xác thực thông tin trước khi điền.
                    </div>

                    <div class="text-center mt-4">
                        <p class="fw-semibold mb-1">Xin trân trọng cảm ơn sự hợp tác của Anh/Chị!</p>
                        <p class="text-muted fst-italic mb-3">Kính chúc Anh/Chị sức khỏe và thành công!</p>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            <a href="{{ route('admin.survey.index') }}" class="btn btn-secondary btn-back">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </button>
                        <button type="button" class="btn btn-primary" disabled>
                            <i class="bi bi-send me-2"></i>Gửi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Vô hiệu hóa tất cả các tương tác trong chế độ xem trước
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('preview-form');

            // Ngăn chặn submit
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Chế độ xem trước - Không thể gửi form');
            });

            // Vô hiệu hóa tất cả input
            const inputs = form.querySelectorAll('input, select, textarea, button');
            inputs.forEach(input => {
                input.setAttribute('disabled', 'disabled');
            });
        });
    </script>
@endsection
