@extends('admin.layouts.no-master')

@section('title', 'Form-student')

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

        /* Thụt dòng đầu tiên của mô tả */
        .first-line-indent {
            text-indent: 30px;
        }

        /* Dòng địa chỉ và điện thoại */
        .contact-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            font-style: italic;
            font-size: 0.85rem;
        }

        .contact-info span:first-child {
            text-align: center !important
                /* align-self: center; */
                padding-right: 0;
        }

        /* --- Responsive --- */
        @media (max-width: 992px) {

            /* Tablet */
            .google-form-style {
                padding: 2rem 3rem;
            }

            /* Logo và cột phải co nhỏ */
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

            /* Font co nhỏ */
            .form-header-right div,
            .form-header-right p {
                font-size: 0.7rem !important;
            }

            ..form-header-right p {
                text-align: center !important
            }

            .form-header-right h6 {
                font-size: 0.65rem !important;
            }

            .contact-info {
                font-size: 0.65rem;
                align-items: center;
            }

            /* .form-title h5 {
                                            font-size: 0.9rem;
                                        } */

            .first-line-indent {
                text-indent: 18px;
            }
        }

        @media (max-width: 576px) {

            /* Điện thoại */
            .google-form-style {
                padding: 1rem 1rem;
            }

            /* Logo và cột phải co nhỏ */
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

            /* Font co nhỏ */
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

            /* .form-title h5 {
                                            font-size: 0.4rem;
                                        } */

            .first-line-indent {
                text-indent: 12px;
            }

            .text-center.mt-5 {
                margin-top: 1rem !important;
            }
        }
    </style>
    <!-- Form khảo sát chính (ẩn lúc đầu) -->

    <div class="container py-4">
        <div class="google-form-style ">
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
                        <h5 style="font-weight: bold; text-transform: uppercase; font-size: 16px; margin: 0 0 5px 0;">
                            {{ $survey->title }}</h5>
                    </div>

                    {{-- <div class="text-center mt-5 form-title">
                        <h5 class="fw-bold text-center">{{ $survey->title }}</h5>
                        <p class="text-justify">
                            {{ $survey->description }}
                        </p>
                    </div> --}}

                    <!-- Phần thân gửi -->
                    <div class="mt-2">
                        <p class="fw-semibold fst-italic ms-4 mt-4">
                            Thân gửi Anh/Chị cựu sinh viên của Học viện Nông Nghiệp Việt Nam!
                        </p>

                        <p class="text-justify fst-italic first-line-indent">
                            {{ $survey->description }}
                        </p>

                        <p class="fst-italic ms-4 mt-2">
                            Trân trọng cảm ơn sự cộng tác của các Anh/Chị!
                        </p>

                        @if ($outDate)
                            <div class="text-danger text-center fw-bold">ĐÃ HẾT HẠN KHẢO SÁT</div>
                        @endif
                    </div>
                </div>

                <form action="{{ route('survey.submit') }}" method="POST" id="form-wrapper">
                    @csrf
                    @method('POST')
                    @include('admin.layouts.noti')
                    <input type="hidden" name="mssv_verified" value="{{ old('mssv_verified') }}">

                    <input type="hidden" name="survey_id" value="{{ $survey->id }}">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">

                    <div class="form-section">
                        <h6 class="fw-bold">Phần I. Thông tin cá nhân</h6>

                        <div class="mb-3">
                            <label for="ma_sv">1. Mã sinh viên</label>
                            <input type="text" class="form-control" id="code_student" name="code_student" value="" readonly
                                required placeholder="Nhập mã sinh viên">
                        </div>

                        <div class="mb-3">
                            <label for="ho_ten">2. Họ và tên</label>
                            <input type="text" class="form-control" id="full_names" name="full_name" required
                                placeholder="Nhập họ và tên đầy đủ">
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">3. Giới tính</label>
                                <input type="text" class="form-control" placeholder="Nam / Nữ" name="gender" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">4. Ngày sinh</label>
                                <input type="date" class="form-control" name="dob">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ma_nghanh_dao_tao">5. Mã ngành đào tạo</label>
                            <select class="form-control" id="ma_nghanh_dao_tao" required>
                                <option value="" selected>-- Chọn mã ngành đào tạo --</option>
                                <option value="7480201">7480201 - Công nghệ thông tin</option>
                                <option value="7480102">7480102 - Mạng máy tính và truyền thông dữ liệu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">6. Số căn cước công dân</label>
                            <input type="text" class="form-control mb-2" placeholder="Nhập số CCCD"
                                name="identification_card_number" required>
                            <label class="form-label">Cấp ngày:</label>
                            <input type="date" class="form-control mb-2" name="identification_issuance_date" required>
                            <label class="form-label">Tại:</label>
                            <input type="text" class="form-control" placeholder="Nhập nơi cấp"
                                name="identification_issuance_place" required>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">7. Khóa học</label>
                                <input type="text" class="form-control" placeholder="Nhập khóa học" name="course" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">8. Tên ngành được đào tạo</label>
                                <input type="text" id="ten_nganh_hien_thi" class="form-control" placeholder="" readonly>
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

                                maNgheSelect.addEventListener("change", function () {
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

                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">9. Số điện thoại</label>
                                    <input type="text" class="form-control" placeholder="Nhập số điện thoại"
                                        name="phone_number" pattern="^(0[3|5|7|8|9])([0-9]{8})$"
                                        title="Vui lòng nhập số điện thoại Việt Nam hợp lệ (10 chữ số, bắt đầu bằng 0)"
                                        required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">10. Email</label>
                                    <input type="email" class="form-control" placeholder="Nhập email" name="email" required>
                                </div>
                            </div>

                            <!-- 10. Tình trạng việc làm hiện tại -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">11. Anh/Chị vui lòng cho biết tình trạng việc làm hiện
                                    tại của Anh/Chị
                                </label>

                                @php $tinh_trang = config('config.tinh_trang'); @endphp
                                @foreach ($tinh_trang as $index => $value)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input employment-status-radio" type="radio"
                                            name="employment_status" required id="tt_{{ $index }}" value="{{ $index }}">
                                        <label class="form-check-label fw-normal" for="tt_{{ $index }}">{{ $value }}</label>
                                    </div>
                                @endforeach
                                <i class="question-10" style="font-size: 14px; color:rgb(94, 6, 6)"> *Nếu chưa có việc làm
                                    hoặc
                                    đang tiếp tục học, anh/chị trả lời tiếp câu 27.
                                    Nếu đã có việc làm, anh/chị trả lời tiếp các câu sau</i>
                            </div>

                            <div class="employment-details">
                                <div class="mb-3">
                                    <label class="form-label">12. Tên đơn vị tuyển dụng</label>
                                    <input type="text" class="form-control" placeholder="Nhập tên công ty / tổ chức"
                                        name="recruit_partner_name" required>
                                </div>

                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label class="form-label">13. Địa chỉ đơn vị</label>
                                        <input type="text" class="form-control mb-1" id="vn-address-autocomplete"
                                            placeholder="VD: Khu 2 Hoàng Khương, Thanh Ba, Phú Thọ"
                                            name="recruit_partner_address" autocomplete="o" required>
                                        <div id="vn-suggestions" class="list-group"
                                            style="display: none; max-height: 200px; overflow-y: auto;"></div>
                                    </div>

                                    @push('script')
                                        <script>
                                            let vietnamAddresses = [];

                                            $(document).ready(function () {
                                                 $.ajax({
                                                    url: 'https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json',
                                                    method: 'GET',
                                                    dataType: 'json',
                                                    success: function (provinces) {
                                                         provinces.forEach(function (province) {
                                                            vietnamAddresses.push(province.Name);

                                                            if (province.Districts) {
                                                                province.Districts.forEach(function (district) {
                                                                    vietnamAddresses.push(`${district.Name}, ${province.Name}`);

                                                                    if (district.Wards) {
                                                                        district.Wards.forEach(function (ward) {
                                                                            vietnamAddresses.push(`${ward.Name}, ${district.Name}, ${province.Name}`);
                                                                        });
                                                                    }
                                                                });
                                                            }
                                                        });

                                                        console.log('Đã load', vietnamAddresses.length, 'địa chỉ');
                                                    },
                                                    error: function () {
                                                        console.error('Không thể load địa chỉ');
                                                    }
                                                });

                                                // Autocomplete
                                                $('#vn-address-autocomplete').on('input', function () {
                                                    const query = $(this).val().toLowerCase().trim();
                                                    const $suggestions = $('#vn-suggestions');

                                                    if (query.length < 2) {
                                                        $suggestions.hide().empty();
                                                        return;
                                                    }

                                                    // Tìm kiếm
                                                    const matches = vietnamAddresses.filter(addr =>
                                                        addr.toLowerCase().includes(query)
                                                    ).slice(0, 10); // Giới hạn 10 kết quả

                                                    if (matches.length === 0) {
                                                        $suggestions.hide().empty();
                                                        return;
                                                    }

                                                    // Hiển thị gợi ý
                                                    $suggestions.empty();
                                                    matches.forEach(function (address) {
                                                        const $item = $('<a href="#" class="list-group-item list-group-item-action small">')
                                                            .text(address)
                                                            .on('click', function (e) {
                                                                e.preventDefault();
                                                                $('#vn-address-autocomplete').val(address);
                                                                $suggestions.hide().empty();
                                                            });
                                                        $suggestions.append($item);
                                                    });

                                                    $suggestions.show();
                                                });

                                                // Ẩn khi click ra ngoài
                                                $(document).on('click', function (e) {
                                                    if (!$(e.target).closest('#vn-address-autocomplete, #vn-suggestions').length) {
                                                        $('#vn-suggestions').hide();
                                                    }
                                                });
                                            });
                                        </script>

                                        <style>
                                            #vn-suggestions {
                                                position: absolute;
                                                z-index: 1000;
                                                width: calc(100% - 30px);
                                                margin-top: -8px;
                                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                                            }

                                            #vn-suggestions .list-group-item {
                                                cursor: pointer;
                                                padding: 8px 12px;
                                            }

                                            #vn-suggestions .list-group-item:hover {
                                                background-color: #f0f0f0;
                                            }
                                        </style>
                                    @endpush
                                    <label class="form-label">Tỉnh/Thành phố</label>
                                    <input type="text" class="form-control mb-1" placeholder="VD: Hà Nội"
                                        name="recruit_partner_city" required>

                                </div>

                                <div class="mb-3">
                                    <label class="form-label">14. Thời gian tuyển dụng</label>
                                    <input type="date" class="form-control"
                                        placeholder="Nhập ngày/tháng/năm tuyển dụng (vd: 01/01/2025)"
                                        name="recruit_partner_date" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">15. Chức vụ, vị trí việc làm</label>
                                    <input type="text" class="form-control" placeholder="" name="recruit_partner_position"
                                        required>
                                </div>

                                {{-- PHẦN II: NỘI DUNG KHẢO SÁT --}}
                                <h6 class="mb-4 fw-bold">Phần II: Nội dung khảo sát</h6>

                                <!-- 15. Khu vực làm việc -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">16. Đơn vị Anh/Chị đang làm việc thuộc khu vực làm
                                        việc
                                        nào?</label>
                                    @foreach (config('config.work_area') as $key => $item)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="work_area" required
                                                id="kv_{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label fw-normal" for="kv_{{ $key }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- 16. Thời gian có việc sau tốt nghiệp -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">17. Sau khi tốt nghiệp, Anh/Chị có việc làm từ khi
                                        nào?</label>
                                    @foreach (config('config.employed_since') as $key => $item)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="employed_since" required
                                                id="tg_{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label fw-normal" for="tg_{{ $key }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- 17. Công việc có phù hợp với ngành đào tạo -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">18. Công việc Anh/Chị đang đảm nhận có phù hợp với
                                        ngành
                                        đào
                                        tạo
                                        không?</label>
                                    @foreach (config('config.trained_field') as $key => $item)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="trained_field" required
                                                id="nganh_{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label fw-normal" for="nganh_{{ $key }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- 18. Công việc có phù hợp với trình độ chuyên môn -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">19. Công việc Anh/Chị đang đảm nhận có phù hợp với
                                        trình
                                        độ
                                        chuyên môn không?</label>
                                    @foreach (config('config.professional_qualification_field') as $key => $item)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="professional_qualification_field"
                                                required id="trinhdo_{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label fw-normal"
                                                for="trinhdo_{{ $key }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- 19. Kiến thức kỹ năng từ trường có phù hợp công việc -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">20. Anh/chị có học được các kiến thức và kỹ năng cần
                                        thiết từ
                                        nhà trường cho công việc theo ngành tốt nghiệp không?</label>
                                    @foreach (config('config.level_knowledge_acquired') as $key => $item)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="level_knowledge_acquired"
                                                required id="kt_{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label fw-normal" for="kt_{{ $key }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- 20. Mức lương khởi điểm -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">21. Mức lương khởi điểm của Anh/Chị (triệu
                                        đồng/tháng)</label>
                                    <input type="text" class="form-control" name="starting_salary" placeholder="10">
                                </div>

                                <!-- 21. Mức thu nhập hiện tại -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">22. Mức thu nhập bình quân/tháng tính theo VNĐ của
                                        Anh/Chị
                                        hiện nay</label>
                                    @foreach (config('config.average_income') as $key => $item)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="average_income"
                                                id="tn_{{ $key }}" value="{{ $key }}">
                                            <label class="form-check-label fw-normal" for="tn_{{ $key }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- 23. Hình thức tìm được việc làm -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">23. Anh/Chị tìm được việc làm thông qua những hình
                                        thức
                                        nào?

                                        <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                                    @php $tim_viec = config('config.recruitment_type'); @endphp
                                    @foreach ($tim_viec as $index => $value)
                                        @if ($value == 'Hình thức khác')
                                            <div class="form-check mb-2">
                                                <input class="form-check-input recruitment_type_other" type="checkbox"
                                                    name="recruitment_type[]" id="ht_{{ $index }}" value="{{ $index }}">
                                                <label class="form-check-label fw-normal" for="ht_{{ $index }}">Hình thức
                                                    khác</label>
                                            </div>
                                        @else
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="recruitment_type[]"
                                                    id="ht_{{ $index }}" value="{{ $index }}">
                                                <label class="form-check-label fw-normal" for="ht_{{ $index }}">{{ $value }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div id="recruitment_type_other_wrapper" style="display: none;" class="mt-2">
                                        <input type="text" name="recruitment_type_other" id="recruitment_type_other"
                                            class="form-control other-input" placeholder="Nhập hình thức tìm việc làm..."
                                            autocomplete="off">
                                    </div>

                                    <div id="recruitment_type_error" class="text-danger small d-none"></div>
                                </div>

                                <!-- 23. Hình thức tuyển -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">24. Anh/chị được tuyển dụng theo hình thức
                                        nào?</label>
                                    @foreach (config('config.job_search_method') as $key => $item)
                                        @if ($item == 'Hình thức khác')
                                            <div class="form-check mb-2">
                                                <input class="form-check-input job_search_method_other" type="radio"
                                                    name="job_search_method[]" id="ht23_{{ $key }}" value="{{ $key }}">
                                                <label class="form-check-label fw-normal" for="ht23_{{ $key }}">Hình thức
                                                    khác</label>
                                            </div>
                                        @else
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="job_search_method[]"
                                                    id="ht23_{{ $key }}" value="{{ $key }}">
                                                <label class="form-check-label fw-normal" for="ht23_{{ $key }}">{{ $item }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div id="job_search_method_other_wrapper" style="display: none;" class="mt-2">
                                        <input type="text" name="job_search_method_other" id="job_search_method_other"
                                            class="form-control other-input" placeholder="Nhập hình thức tuyển dụng...">
                                    </div>
                                    <div id="job_search_method_error" class="text-danger small d-none"></div>
                                </div>

                                <!-- 24. Kỹ năng mềm -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">25. Trong quá trình làm việc, Anh/Chị cần những kỹ
                                        năng
                                        mềm
                                        nào
                                        sau đây? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                                    @php $ky_nang = config('config.soft_skills_required'); @endphp
                                    @foreach ($ky_nang as $index => $value)
                                        @if ($value == 'Khác')
                                            <div class="form-check mb-2">
                                                <input class="form-check-input soft_skills_required_other" type="checkbox"
                                                    name="soft_skills_required[]" id="ht_{{ $index }}" value="{{ $index }}">
                                                <label class="form-check-label fw-normal" for="ht_{{ $index }}">Khác</label>
                                            </div>
                                        @else
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="soft_skills_required[]"
                                                    id="kn_{{ $index }}" value="{{ $index }}">
                                                <label class="form-check-label fw-normal" for="kn_{{ $index }}">{{ $value }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div id="soft_skills_required_other_wrapper" style="display: none;" class="mt-2">
                                        <input type="text" name="soft_skills_required_other" id="soft_skills_required_other"
                                            class="form-control other-input" placeholder="Nhập kỹ năng mềm..."
                                            autocomplete="off">
                                    </div>
                                    <div id="soft_skills_required_error" class="text-danger small d-none"></div>
                                </div>

                                <!-- 25. Khóa học nâng cao -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">26. Sau khi được tuyển dụng, Anh/Chị có phải tham gia
                                        khóa
                                        học
                                        nâng cao nào dưới đây để đáp ứng công việc không <span class="fw-normal">(Có thể
                                            chọn
                                            nhiều
                                            lựa
                                            chọn)</span></label>
                                    @php $nang_cao = config('config.must_attended_courses'); @endphp
                                    @foreach ($nang_cao as $index => $value)
                                        @if ($value == 'Khóa học khác(xin ghi rõ)')
                                            <div class="form-check mb-2">
                                                <input class="form-check-input must_attended_courses_other" type="checkbox"
                                                    name="must_attended_courses[]" id="ht_{{ $index }}" value="{{ $index }}">
                                                <label class="form-check-label fw-normal" for="ht_{{ $index }}">Khóa học khác(xin
                                                    ghi rõ)</label>
                                            </div>
                                        @else
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="must_attended_courses[]"
                                                    id="nc_{{ $index }}" value="{{ $index }}">
                                                <label class="form-check-label fw-normal" for="nc_{{ $index }}">{{ $value }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div id="must_attended_courses_other_wrapper" style="display: none;" class="mt-2">
                                        <input type="text" name="must_attended_courses_other"
                                            id="must_attended_courses_other" class="form-control other-input"
                                            placeholder="Nhập khóa học.." autocomplete="off">
                                    </div>
                                    <div id="must_attended_courses_error" class="text-danger small d-none"></div>
                                </div>

                            </div>


                            <!-- 26. Giải pháp nâng tỷ lệ có việc làm -->
                            <div class="mb-4" id="question-26">
                                <label class="form-label fw-bold">27. Theo Anh/Chị, những giải pháp nào sau đây giúp tăng
                                    tỷ lệ
                                    có
                                    việc làm đúng ngành của sinh viên tốt nghiệp từ chương trình đào tạo mà Anh/Chị đã học?
                                    <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                                @php $giai_phap = config('config.solutions_get_job'); @endphp
                                @foreach ($giai_phap as $index => $value)
                                    @if ($value == 'Các giải pháp khác (xin ghi rõ)')
                                        <div class="form-check mb-2">
                                            <input class="form-check-input solutions_get_job_other" type="checkbox"
                                                name="solutions_get_job[]" id="ht26_{{ $index }}"
                                                value="{{ $index }}">
                                            <label class="form-check-label fw-normal" for="ht26_{{ $index }}">Các
                                                giải pháp khác (xin ghi rõ)</label>
                                        </div>
                                    @else
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="solutions_get_job[]"
                                                id="ht26_{{ $index }}" value="{{ $index }}">
                                            <label class="form-check-label fw-normal" for="ht26_{{ $index }}">{{ $value }}</label>
                                        </div>
                                    @endif
                                @endforeach
                                <div id="solutions_get_job_other_wrapper" style="display: none;" class="mt-2">
                                    <input type="text" name="solutions_get_job_other" id="solutions_get_job_other"
                                        class="form-control other-input" placeholder="Nhập giải pháp khác tại đây..."
                                        autocomplete="off">
                                </div>
                                <div id="solutions_get_job_error" class="text-danger small d-none"></div>
                            </div>

                            <div>
                                <!-- Thông báo -->
                                @if ($outDate)
                                    <div class="alert alert-danger mt-4" role="alert">
                                        <i class="bi bi-x-circle-fill me-2"></i>
                                        Thời gian khảo sát đã kết thúc. Bạn không thể gửi phiếu khảo sát này.
                                    </div>
                                @else
                                    <div class="alert alert-warning mt-4" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Vui lòng kiểm tra kỹ thông tin trước khi gửi. Mỗi sinh viên chỉ được gửi phiếu khảo
                                        sát
                                        một lần.
                                    </div>
                                @endif

                                <!-- Cảm ơn -->
                                <div class="text-center mt-4">
                                    <p class="fw-semibold mb-1">Xin trân trọng cảm ơn sự hợp tác của Anh/Chị!</p>
                                    <p class="text-muted fst-italic mb-3">Kính chúc Anh/Chị sức khỏe và thành công!</p>
                                </div>
                            </div>

                            <!-- Nút gửi -->
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn {{ $outDate ? 'btn-danger' : 'btn-primary' }}" {{ $outDate ? 'disabled' : '' }}>
                                    {{ $outDate ? 'Hết hạn gửi' : 'Gửi' }}
                                </button>
                            </div>

                        </div>
                </form>
            </div>

        </div>


        <!-- 🛡️ Modal xác thực sinh viên -->
        <div class="modal fade" id="verifyStudentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content p-4 border-0 shadow-sm rounded-4">

                    <!-- Header: Logo + Tiêu đề -->
                    <div class="text-center mb-3 modal-header-professional">
                        <img src="{{ asset('assets/client/images/logo-vnua.jpg') }}" width="60" class="logo-professional"
                            alt="Logo Học viện Nông nghiệp Việt Nam">
                        {{-- <h6 class="school-name">Học viện Nông nghiệp Việt Nam</h6> --}}
                        <h5 class="fw-bold modal-title-professional">Xác thực thông tin sinh viên</h5>
                        <small class="text-muted fst-italic note-professional">
                            (Anh/Chị vui lòng điền đúng ít nhất 2 thông tin để xác nhận <br>là sinh viên của Học
                            viện)
                        </small>
                        <hr class="hr-professional">
                    </div>

                    <!-- Tổng lỗi -->
                    <div class="alert alert-danger py-1 small d-none" id="total-error"></div>

                    <!-- Form xác thực -->
                    <form id="verifyStudentForm">
                        <!-- Họ và tên -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label fw-semibold">Họ và tên</label>
                            <input type="text" id="full_name" name="m_full_name" class="form-control rounded-3"
                                placeholder="Nhập họ và tên của Anh/Chị">
                        </div>

                        <!-- Mã sinh viên -->
                        <div class="mb-3">
                            <label for="input-mssv" class="form-label fw-semibold">Mã sinh viên</label>
                            <input type="text" id="input-mssv" name="m_mssv" class="form-control rounded-3"
                                placeholder="Nhập mã sinh viên (nếu nhớ)">
                        </div>

                        <!-- Email -->
                        {{-- <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" id="email" name="m_email" class="form-control rounded-3"
                                placeholder="Nhập email Anh/Chị được cấp khi học">
                            <div class="form-text text-muted small">Ví dụ: 647081@sv.vnua.edu.vn</div>
                        </div> --}}

                        <!-- Số điện thoại -->
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" id="phone" name="m_phone" class="form-control rounded-3"
                                placeholder="Nhập số điện thoại liên hệ">
                        </div>

                        <!-- Ngày sinh -->
                        <div class="mb-3">
                            <label for="dob" class="form-label fw-semibold">Ngày sinh</label>
                            <input type="date" id="dob" name="m_dob" class="form-control rounded-3">
                        </div>

                        <!-- Nút xác nhận -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary w-50 rounded-pill">Xác nhận</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- CSS animation -->
        <style>
            .modal-header-professional img {
                border-radius: 50%;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                opacity: 0;
                transform: translateY(-20px) scale(0.8);
                transition: all 0.6s ease-out;
            }

            .school-name {
                opacity: 0;
                transform: translateY(-10px);
                font-size: 0.95rem;
                color: #033e15;
                font-weight: 500;
                transition: all 0.6s ease-out;
                margin-bottom: 0.25rem;
            }

            .modal-title-professional {
                opacity: 0;
                transform: translateY(-5px);
                transition: all 0.6s ease-out;
                font-size: 1.25rem;
                color: #1a1a1a;
            }

            .note-professional {
                opacity: 0;
                transition: opacity 0.8s ease-out;
                font-size: 0.85rem;
                display: block;
                margin-bottom: 0;
            }

            .hr-professional {
                width: 0;
                border-top: 1px solid #dee2e6;
                opacity: 0;
                transition: width 0.5s ease-out, opacity 0.5s ease-out;
                margin-top: 0.8rem;
            }

            .logo-professional:hover,
            .modal-title-professional:hover {
                transform: scale(1.05);
                transition: all 0.3s ease;
            }
        </style>

        <!-- JS -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('verifyStudentModal');
                const modal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();

                // Animation modal header
                modalEl.addEventListener('shown.bs.modal', function () {
                    const logo = modalEl.querySelector('.logo-professional');
                    const schoolName = modalEl.querySelector('.school-name');
                    const title = modalEl.querySelector('.modal-title-professional');
                    const note = modalEl.querySelector('.note-professional');
                    const hr = modalEl.querySelector('.hr-professional');

                    setTimeout(() => {
                        logo.style.opacity = 1;
                        logo.style.transform = 'translateY(0) scale(1)';
                    }, 100);
                    setTimeout(() => {
                        schoolName.style.opacity = 1;
                        schoolName.style.transform = 'translateY(0)';
                    }, 300);
                    setTimeout(() => {
                        title.style.opacity = 1;
                        title.style.transform = 'translateY(0)';
                    }, 500);
                    setTimeout(() => {
                        note.style.opacity = 1;
                    }, 700);
                    setTimeout(() => {
                        hr.style.width = '100%';
                        hr.style.opacity = 1;
                    }, 600);
                });




                // Xử lý submit form xác thực
                $('#verifyStudentForm').on('submit', function (e) {
                    e.preventDefault(); // Ngăn reload trang

                    const survey_id = $('input[name="survey_id"]').val().trim();
                    const full_name = $('#full_name').val().trim();
                    const mssv = $('#input-mssv').val().trim();
                    // const email = $('#email').val().trim();
                    const phone = $('#phone').val().trim();
                    const dob = $('#dob').val().trim();

                    const filledCount = [full_name, mssv, phone, dob].filter(v => v !== '').length;
                    console.log('filledCount: ', full_name);
                    console.log('filledCount: ', phone);
                    console.log('filledCount: ', dob);
                    if (filledCount < 2) {
                        $('#total-error').text('Vui lòng nhập ít nhất 2 thông tin để xác thực.').removeClass(
                            'd-none');
                        return;
                    } else {
                        $('#total-error').addClass('d-none');
                    }

                    $.ajax({
                        url: '/api/khao-sat/verify-student',
                        method: 'POST',
                        data: {
                            survey_id,
                            full_name,
                            mssv,
                            // email,
                            phone,
                            dob,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            if (res.success) {
                                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                                modalInstance.hide();
                                $('#form-wrapper').fadeIn();

                                const data = res.student;
                                if (data) {
                                    $('input[name="full_name"]').val(data.full_name);
                                    $('input[name="code_student"]').val(data.code);
                                    // $('input[name="email"]').val(data.email);
                                    $('input[name="phone_number"]').val(data.phone);
                                    $('input[name="dob"]').val(data.dob);

                                    var gender = data.gender == 'male' ? 'Nam' : 'Nữ';
                                    $('input[name="gender"]').val(gender);

                                    // Lấy 2 số đầu của mã sinh viên để xác định khóa học
                                    var mssv = data.code; // Mã sinh viên
                                    var course = '' + mssv.substring(0,
                                        2); // Lấy 2 chữ số đầu
                                    $('input[name="course"]').val(
                                        course); // Điền khóa học vào trường input
                                }

                                // const response = res.survey_response;

                                // if (response) {
                                //     // --- 1. ĐIỀN THÔNG TIN CÁ NHÂN (PHẦN I) ---

                                //     // Các input text/date đơn giản
                                //     $('#code_student').val(response.code_student); // ID: code_student
                                //     $('#full_names').val(response.full_name);      // ID: full_names
                                //     $('input[name="gender"]').val(response.gender);
                                //     $('input[name="dob"]').val(response.dob);
                                //     $('input[name="identification_card_number"]').val(response.identification_card_number);
                                //     $('input[name="identification_issuance_place"]').val(response.identification_issuance_place);
                                //     $('input[name="identification_issuance_date"]').val(response.identification_issuance_date);
                                //     $('input[name="course"]').val(response.course);
                                //     $('input[name="phone_number"]').val(response.phone_number);
                                //     $('input[name="email"]').val(response.email);

                                //     // Xử lý Select: Mã ngành đào tạo
                                //     // Logic: Map từ ID (trong DB) sang Value (trong Option) để select chọn đúng
                                //     if (response.training_industry_id) {
                                //         let codeToSelect = "";
                                //         if (response.training_industry_id == 1) codeToSelect = "7480201"; // CNTT
                                //         if (response.training_industry_id == 2) codeToSelect = "7480102"; // Mạng

                                //         if (codeToSelect) {
                                //             $('#ma_nghanh_dao_tao').val(codeToSelect).trigger('change'); 
                                //             // trigger change để script có sẵn cập nhật input #ten_nganh_hien_thi
                                //         }
                                //     }

                                //     // --- 2. ĐIỀN THÔNG TIN VIỆC LÀM (PHẦN II) ---

                                //     // Câu 11: Tình trạng việc làm (Quan trọng: Trigger change để hiện/ẩn phần chi tiết)
                                //     $(`input[name="employment_status"][value="${response.employment_status}"]`)
                                //         .prop('checked', true)
                                //         .trigger('change');

                                //     // Các thông tin chi tiết về công ty
                                //     $('input[name="recruit_partner_name"]').val(response.recruit_partner_name);
                                //     $('input[name="recruit_partner_address"]').val(response.recruit_partner_address);
                                //     // Lưu ý: Input "Tỉnh/Thành phố" trong HTML của bạn chưa có name, nên không fill được
                                //     $('input[name="recruit_partner_date"]').val(response.recruit_partner_date);
                                //     $('input[name="recruit_partner_position"]').val(response.recruit_partner_position);
                                //     $('input[name="starting_salary"]').val(response.starting_salary);

                                //     // Điền Radio buttons (Chọn 1)
                                //     const radioFields = [
                                //         'work_area', 
                                //         'employed_since', 
                                //         'trained_field', 
                                //         'professional_qualification_field', 
                                //         'level_knowledge_acquired', 
                                //         'average_income'
                                //     ];
                                //     radioFields.forEach(name => {
                                //         $(`input[name="${name}"][value="${response.work_area}"]`).prop('checked', true);
                                //         // Lưu ý: với radio đơn giản không có logic ẩn hiện thì không cần trigger('change')
                                //         if(response[name]) {
                                //             $(`input[name="${name}"][value="${response[name]}"]`).prop('checked', true);
                                //         }
                                //     });


                                //     // --- 3. ĐIỀN CHECKBOX & RADIO NHIỀU LỰA CHỌN (KÈM Ô "KHÁC") ---

                                //     // Hàm hỗ trợ check mảng và kích hoạt sự kiện change để hiện ô "Khác" nếu cần
                                //     function checkMulti(name, values) {
                                //         // Reset trước
                                //         $(`input[name="${name}[]"]`).prop('checked', false);

                                //         if (Array.isArray(values)) {
                                //             values.forEach(val => {
                                //                 // Check và Trigger change để logic toggleOther hoạt động
                                //                 $(`input[name="${name}[]"][value="${val}"]`)
                                //                     .prop('checked', true)
                                //                     .trigger('change'); 
                                //             });
                                //         }
                                //     }

                                //     // 23. Hình thức tìm việc
                                //     checkMulti('recruitment_type', response.recruitment_type);
                                //     if (response.recruitment_type_other) {
                                //         $('#recruitment_type_other').val(response.recruitment_type_other).removeClass('d-none');
                                //         $('#recruitment_type_other_wrapper').show();
                                //     }

                                //     // 24. Hình thức tuyển (Radio group name[])
                                //     checkMulti('job_search_method', response.job_search_method);
                                //     if (response.job_search_method_other) {
                                //         $('#job_search_method_other').val(response.job_search_method_other).removeClass('d-none');
                                //         $('#job_search_method_other_wrapper').show();
                                //     }

                                //     // 25. Kỹ năng mềm
                                //     checkMulti('soft_skills_required', response.soft_skills_required);
                                //     if (response.soft_skills_required_other) {
                                //         $('#soft_skills_required_other').val(response.soft_skills_required_other).removeClass('d-none');
                                //         $('#soft_skills_required_other_wrapper').show();
                                //     }

                                //     // 26. Khóa học nâng cao
                                //     checkMulti('must_attended_courses', response.must_attended_courses);
                                //     if (response.must_attended_courses_other) {
                                //         $('#must_attended_courses_other').val(response.must_attended_courses_other).removeClass('d-none');
                                //         $('#must_attended_courses_other_wrapper').show();
                                //     }

                                //     // 27. Giải pháp
                                //     checkMulti('solutions_get_job', response.solutions_get_job);
                                //     if (response.solutions_get_job_other) {
                                //         $('#solutions_get_job_other').val(response.solutions_get_job_other).removeClass('d-none');
                                //         $('#solutions_get_job_other_wrapper').show();
                                //     }
                                // }
                                // Thêm flag hidden để form biết đã xác thực
                                if ($('input[name="mssv_verified"]').length === 0) {
                                    $('<input>').attr({
                                        type: 'hidden',
                                        name: 'mssv_verified',
                                        value: '1'
                                    }).appendTo("#form-wrapper form");
                                }
                            } else {
                                $('#total-error').text(res.message || 'Thông tin không hợp lệ')
                                    .removeClass('d-none');
                            }
                        },
                        error: function () {
                            $('#total-error').text('Đã có lỗi xảy ra, vui lòng thử lại.')
                                .removeClass('d-none');
                        }
                    });
                });
            });
        </script>
@endsection

    @push('script')
        <script>
            $(document).on('change', 'input[type=radio], input[type=checkbox]', function () {
                const $input = $(this);
                const isOther = $input.data('is-other') === true || $input.data('is-other') === 'true';

                // Với radio: ẩn tất cả các ô "Khác" cùng nhóm trước
                if ($input.attr('type') === 'radio') {
                    const name = $input.attr('name');
                    $(`input[name="${name}"]`).each(function () {
                        $(this).closest('.form-check').find('.other-input').addClass('d-none');
                    });
                }

                // Nếu là "Khác" và được chọn → show ô nhập
                if (isOther && $input.is(':checked')) {
                    $input.closest('.form-check').find('.other-input').removeClass('d-none').focus();
                } else if (!isOther && $input.attr('type') === 'radio') {
                    // Nếu chọn lại đáp án thường → ẩn lại
                    $input.closest('.form-check').find('.other-input').addClass('d-none');
                }

                // Với checkbox: toggle ô input ngay cùng nhóm theo checked
                if ($input.attr('type') === 'checkbox' && isOther) {
                    const otherInput = $input.closest('.form-check').find('.other-input');
                    if ($input.is(':checked')) {
                        otherInput.removeClass('d-none').focus();
                    } else {
                        otherInput.addClass('d-none');
                    }
                }
            });
        </script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap JS (bundle includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const verified = '{{ old('mssv_verified') }}';
                console.log(verified, '//verified')
                if (!verified) {
                    const modalEl = document.getElementById('mssvModal');
                    const modal = new bootstrap.Modal(modalEl, {
                        backdrop: 'static',
                        keyboard: false
                    });
                    modal.show();
                }
            });

            $(document).ready(function () {
                $('#verify-mssv-btn').on('click', function () {
                    const survey_id = $('input[name="survey_id"]').val().trim();
                    const mssv = $('input[name="m_mssv"]').val().trim();
                    const email = $('input[name="m_email"]').val().trim();
                    const phone = $('input[name="m_phone"]').val().trim();
                    const dob = $('input[name="m_dob"]').val().trim();
                    const cccd = $('input[name="m_citizen_identification"]').val().trim();
                    const nganh = $('select[name="m_training_industry_id"]').val().trim();

                    const $error = $('#mssv-error');
                    const $totalError = $('#total-error');
                    $error.addClass('d-none');

                    let filled = 0;

                    if (email !== '') filled++;
                    if (phone !== '') filled++;
                    if (cccd !== '') filled++;
                    if (dob !== '') filled++;
                    if (nganh !== '') filled++;

                    if (mssv === '') {
                        alert('Vui lòng nhập mã sinh viên (MSSV).');
                        return;
                    }

                    if (filled < 1) {
                        alert('Vui lòng nhập thêm ít nhất 1 thông tin ngoài MSSV để xác thực.');
                        return;
                    }

                    $.ajax({
                        url: '/api/khao-sat/verify-student',
                        method: 'POST',
                        data: {
                            survey_id: survey_id,
                            mssv: mssv,
                            // email: email,
                            phone: phone,
                            dob: dob,
                            citizen_identification: cccd,
                            training_industry_id: nganh,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            if (res.success) {
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'mssvModal'));
                                modal.hide();
                                $('#form-wrapper').fadeIn();

                                // Đổ dữ liệu vào form
                                const data = res.student;
                                if (data) {
                                    $('input[name="code_student"]').val(data.code);
                                    $('input[name="full_name"]').val(data.full_name);
                                    // $('input[name="email"]').val(data.email);
                                    $('input[name="phone_number"]').val(data.phone);
                                    $('input[name="gender"]').val(data.gender);
                                    $('input[name="dob"]').val(data.dob);
                                }

                                // Truyền 1 hidden input flag
                                $('<input>').attr({
                                    type: 'hidden',
                                    name: 'mssv_verified',
                                    value: '1'
                                }).appendTo("form");

                                $('#p2').find(
                                    'input[type=radio], input[type=checkbox], input[type=text], textarea'
                                ).prop('checked', false).val('');
                            } else {
                                $totalError.text(res.message || 'Không hợp lệ').removeClass(
                                    'd-none');
                            }
                        },
                        error: function () {
                            $totalError.text('Đã có lỗi xảy ra, vui lòng thử lại.').removeClass(
                                'd-none');
                        }
                    });
                });

                // 22
                $('.recruitment_type_other').on('change', function () {
                    const wrapper = $('#recruitment_type_other_wrapper');
                    const input = $('#recruitment_type_other');

                    if ($(this).is(':checked')) {
                        wrapper.show();
                    } else {
                        wrapper.hide();
                        input.val('');
                    }
                });
                // 23
                $('.job_search_method_other').on('change', function () {
                    const wrapper = $('#job_search_method_other_wrapper');
                    const input = $('#job_search_method_other');

                    if ($(this).is(':checked')) {
                        wrapper.show();
                    } else {
                        wrapper.hide();
                        input.val('');
                    }
                });

                // 24
                $('.soft_skills_required_other').on('change', function () {
                    const wrapper = $('#soft_skills_required_other_wrapper');
                    const input = $('#soft_skills_required_other');

                    if ($(this).is(':checked')) {
                        wrapper.show();
                    } else {
                        wrapper.hide();
                        input.val('');
                    }
                });

                // 25
                $('.must_attended_courses_other').on('change', function () {
                    const wrapper = $('#must_attended_courses_other_wrapper');
                    const input = $('#must_attended_courses_other');

                    if ($(this).is(':checked')) {
                        wrapper.show();
                    } else {
                        wrapper.hide();
                        input.val('');
                    }
                });

                // 26
                $('.solutions_get_job_other').on('change', function () {
                    const wrapper = $('#solutions_get_job_other_wrapper');
                    const input = $('#solutions_get_job_other');

                    if ($(this).is(':checked')) {
                        wrapper.show();
                    } else {
                        wrapper.hide();
                        input.val('');
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function () {
                const otherGroups = [{
                    checkboxClass: '.recruitment_type_other',
                    wrapperId: '#recruitment_type_other_wrapper',
                    inputId: '#recruitment_type_other',
                    groupName: 'recruitment_type[]',
                    errorId: '#recruitment_type_error'
                },
                {
                    checkboxClass: '.job_search_method_other',
                    wrapperId: '#job_search_method_other_wrapper',
                    inputId: '#job_search_method_other',
                    groupName: 'job_search_method[]',
                    errorId: '#job_search_method_error'
                },
                {
                    checkboxClass: '.soft_skills_required_other',
                    wrapperId: '#soft_skills_required_other_wrapper',
                    inputId: '#soft_skills_required_other',
                    groupName: 'soft_skills_required[]',
                    errorId: '#soft_skills_required_error'
                },
                {
                    checkboxClass: '.must_attended_courses_other',
                    wrapperId: '#must_attended_courses_other_wrapper',
                    inputId: '#must_attended_courses_other',
                    groupName: 'must_attended_courses[]',
                    errorId: '#must_attended_courses_error'
                },
                {
                    checkboxClass: '.solutions_get_job_other',
                    wrapperId: '#solutions_get_job_other_wrapper',
                    inputId: '#solutions_get_job_other',
                    groupName: 'solutions_get_job[]',
                    errorId: '#solutions_get_job_error'
                }
                ];

                // Toggle input "Khác"
                otherGroups.forEach(group => {
                    const $checkbox = $(group.checkboxClass);
                    const input = $(group.inputId);

                    // Hàm xử lý hiển thị và required
                    const toggleGroup = () => {
                        if ($checkbox.is(':checked')) {
                            $(group.wrapperId).show();
                        } else {
                            $(group.wrapperId).hide();
                            $(group.inputId).val('');
                        }
                    };

                    // 1. Chạy ngay khi page load / code chạy
                    // toggleGroup();

                    // 2. Gắn sự kiện change để cập nhật khi người dùng click
                    $checkbox.on('change', toggleGroup);
                });






                // Validate khi submit
                $('#form-wrapper').on('submit', function (e) {
                    let hasError = false;
                    otherGroups.forEach(group => {
                        const checkedCount = $(`input[name="${group.groupName}"]:checked`).length;
                        const isOtherChecked = $(group.checkboxClass).is(':checked');
                        const otherText = $(group.inputId).val().trim();

                        $(group.errorId).addClass('d-none').text('');

                        if (checkedCount < 1) {
                            $(group.errorId).removeClass('d-none').text(
                                'Vui lòng chọn ít nhất một lựa chọn.');
                            hasError = true;
                        }

                        if (isOtherChecked && otherText === '') {
                            $(group.errorId).removeClass('d-none').text(
                                'Vui lòng nhập ghi chú nếu chọn "Khác".');
                            hasError = true;
                        }
                    });

                    if (hasError) {
                        console.llog('hasError');
                        e.preventDefault();
                    }

                });
            });

            $(document).ready(function () {
                function toggleEmploymentDetails() {
                    var employedValue =
                        '{{ array_key_first(config('config.tinh_trang')) }}';
                    var selected = $('input[name="employment_status"]:checked').val();

                    // xóa required theo option : đang đi làm thì giữ nguynn, còn lại xóa
                    if (selected == employedValue) {
                        $('.employment-details').find('input, select, textarea').attr('required', true);
                    } else {
                        $('.employment-details').find('input, select, textarea').removeAttr('required');
                    }

                    $('.employment-details')
                        .find('.other-input, input[type="checkbox"]')
                        .removeAttr('required');


                    if (selected == employedValue) {
                        $('.employment-details').show();
                        $('#question-26').show();
                    } else if (selected) {
                        $('.employment-details').hide();
                        $('#question-26').show();
                    } else {
                        $('.employment-details').hide();
                        $('#question-26').hide();
                    }
                }

                $('.employment-details').hide();
                $('#question-26').hide();

                $(document).on('change', '.employment-status-radio', function () {
                    toggleEmploymentDetails();
                });

                toggleEmploymentDetails();
            });
        </script>
    @endpush