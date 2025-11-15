<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        body {
            background-color: #f1f3f4;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.6;
            margin: 25px;
        }

        body,
        input,
        textarea,
        label,
        div,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        span,
        small,
        i {
            font-family: 'DejaVu Sans', sans-serif;
        }

        html,
        body {
            height: auto;
            overflow-y: auto;
        }

        .container {
            padding: 20px;
        }

        .google-form-style {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .text-center {
            text-align: center;
        }

        .mb-4 {
            margin-bottom: 2rem;
        }

        h6 {
            margin-bottom: 20px;
            font-size: 14px;
            color: #1a237e;
            border-left: 4px solid #1a73e8;
            padding-left: 10px;
        }

        .form-section {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[readonly] {
            background-color: #f0f2f5;
            color: #555;
        }

        .form-check {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.6rem;
        }

        .form-check-input {
            margin-top: 0.25rem;
            margin-right: 0.5rem;
            position: static;
            flex-shrink: 0;
            width: 16px;
            height: 16px;
        }

        .form-check-label {
            margin: 0;
            font-weight: normal;
            flex: 1;
            line-height: 1.5;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .col-12 {
            flex: 1 1 100%;
        }

        .col-md-6 {
            flex: 1 1 calc(50% - 10px);
        }

        .fw-bold {
            font-weight: bold;
        }

        .fw-normal {
            font-weight: normal;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .mt-2 {
            margin-top: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            display: block;
        }

        @media (max-width: 768px) {
            .col-md-6 {
                flex: 1 1 100%;
            }

            .google-form-style {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="google-form-style">
            <!-- Header -->
            <div class="text-center mb-4">
                <div style="text-align: center; margin-bottom: 15px;">
                    <div>
                        <img src="{{ asset('assets/client/images/logo-vnua.jpg') }}"
                            style="width: 90px; height: 90px; margin: 0 auto; background-color: #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #666;">

                    </div>
                </div>

                <div
                    style="font-size: 16px;text-transform: uppercase;border-left: none;color: #1f2937;text-align: center; font-weight: 700; margin-bottom: 6px">
                    Bộ Nông nghiệp và Môi trường
                </div>
                <div
                    style="text-align: center; font-size: 14px; text-transform: uppercase; color: #1f2937; font-weight: 600; margin-bottom: 6px">
                    Học Viện Nông Nghiệp Việt Nam
                </div>
                <small style="display: block;text-align: center; font-size: 12px; color: #6b7280;">Xã Gia Lâm, Thành phố
                    Hà Nội | Điện thoại: 024.62617586 - Fax: 024.62617586</small>
            </div>

            <!-- Title -->
            <div class="form-section">
                <h5 style="text-align: center; font-size: 16px"> {{ $survey->title }}</h5>
                <p class="text-justify">
                    {{ $survey->description }}
                </p>
                <p class="text-end mt-2" style="text-align: right">
                    <small style="color: #6b7280;"><i>Thời gian khảo sát: {{ $survey->start_time }}
                            – {{ $survey->end_time }}</i></small>
                </p>
            </div>

            <form>
                <div class="form-section">
                    <h6 class="fw-bold">Phần I. Thông tin cá nhân</h6>

                    <div class="mb-3">
                        <label for="ma_sv">1. Mã sinh viên</label>
                        <input type="text" class="form-control" id="ma_sv" name="code_student"
                            value="{{ $response->code_student }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="ho_ten">2. Họ và tên</label>
                        <input type="text" class="form-control" id="ho_ten" name="full_name"
                            value="{{ $response->full_name }}" readonly>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">3. Giới tính</label>
                            <input type="text" class="form-control"
                                value="{{ $response->gender == 'male' ? 'Nam' : 'Nữ' }}" readonly>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">4. Ngày sinh</label>
                            <input type="date" class="form-control" value="{{ $response->dob }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ma_nganh">5. Mã ngành đào tạo</label>
                        @php
                            $maNganh = '';
                            if ($response->training_industry_id == 1) {
                                $maNganh = '4780201';
                            } else {
                                $maNganh = '7480102';
                            }
                        @endphp
                        <input type="text" class="form-control" value="{{ $maNganh}}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">6. Số căn cước công dân</label>
                        <input type="text" class="form-control mb-2" value="{{ $response->identification_card_number }}"
                            readonly>
                        <label class="form-label">Ngày cấp</label>
                        <input type="date" class="form-control mb-2"
                            value="{{ $response->identification_issuance_date }}" readonly>
                        <label class="form-label">Nơi cấp</label>
                        <input type="text" class="form-control" value="{{ $response->identification_issuance_place }}"
                            readonly>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">7. Khóa học</label>
                            <input type="text" class="form-control" value="{{ $response->course }}" readonly>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">8. Tên ngành đào tạo</label>
                            @php 
                              $tenganh = '';
                            if ($response->training_industry_id == 1) {
                                $tenNganh = 'Công Nghệ Thông Tin';
                            } else {
                                $temNganh = 'Mạng Máy Tính Và Truyền Thông Dữ Liệu';
                            }
                            @endphp
                            <input type="text" class="form-control" readonly
                                value="{{ $tenNganh }}">
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

                    <!-- 11. Tình trạng việc làm hiện tại -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">11. Anh/Chị vui lòng cho biết tình trạng việc làm hiện
                            tại</label>
                        @php $tinh_trang = config('config.tinh_trang'); @endphp
                        @foreach ($tinh_trang as $index => $value)
                            <div class="form-check mb-2">
                                <input class="form-check-input" disabled type="radio" name="employment_status"
                                    id="tt_{{ $index }}" value="{{ $index }}" {{ $index == $response->employment_status ? "checked" : "" }}>
                                <label class="form-check-label fw-normal" for="tt_{{ $index }}">{{ $value }}</label>
                            </div>
                        @endforeach

                    </div>

                    <div class="mb-3">
                        <label class="form-label">12. Tên đơn vị tuyển dụng</label>
                        <input type="text" class="form-control" value="{{ $response->recruit_partner_name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">13. Địa chỉ đơn vị</label>
                        <input type="text" class="form-control mb-1" value="{{ $response->recruit_partner_address }}"
                            readonly>
                        <label class="form-label">Tỉnh/Thành phố</label>
                        <input type="text" class="form-control mb-1"
                            value="{{ $response->recruit_partner_province ?? '' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">14. Thời gian tuyển dụng</label>
                        <input type="date" class="form-control" value="{{ $response->recruit_partner_date }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">15. Chức vụ, vị trí việc làm</label>
                        <input type="text" class="form-control" value="{{ $response->recruit_partner_position }}"
                            readonly>
                    </div>
                </div>

                <!-- PHẦN II: NỘI DUNG KHẢO SÁT -->
                <h6 class="mb-4 fw-bold">Phần II: Nội dung khảo sát</h6>

                <!-- 16. Khu vực làm việc -->
                <div class="mb-4">
                    <label class="form-label fw-bold">16. Đơn vị Anh/Chị đang làm việc thuộc khu vực nào?</label>
                    @foreach (config('config.work_area') as $index => $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="work_area" id="kv_{{ $index }}"
                                value="{{ $index }}" disabled {{ $response->work_area == $index ? "checked" : "" }}>
                            <label class="form-check-label fw-normal" for="kv_{{ $index }}">{{ $item }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- 17. Thời gian có việc sau tốt nghiệp -->
                <div class="mb-4">
                    <label class="form-label fw-bold">17. Sau khi tốt nghiệp, Anh/Chị có việc làm từ khi nào?</label>
                    @foreach (config('config.employed_since') as $key => $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="employed_since" id="tg_{{ $key }}"
                                value="{{ $key }}" disabled {{ $response->employed_since == $key ? "checked" : "" }}>
                            <label class="form-check-label fw-normal" for="tg_{{ $key }}">{{ $item }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- 18. Công việc có phù hợp với ngành đào tạo -->
                <div class="mb-4">
                    <label class="form-label fw-bold">18. Công việc Anh/Chị đang làm có phù hợp với ngành đào tạo
                        không?</label>
                    @foreach (config('config.trained_field') as $key => $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="trained_field" id="nganh_{{ $key }}"
                                value="{{ $key }}" disabled {{ $response->trained_field == $key ? "checked" : "" }}>
                            <label class="form-check-label fw-normal" for="nganh_{{ $key }}">{{ $item }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- 19. Công việc có phù hợp với trình độ chuyên môn -->
                <div class="mb-4">
                    <label class="form-label fw-bold">19. Công việc Anh/Chị đang đảm nhận có phù hợp với trình độ chuyên
                        môn không?</label>
                    @foreach (config('config.professional_qualification_field') as $key => $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="professional_qualification_field"
                                id="trinhdo_{{ $key }}" value="{{ $key }}" disabled {{ $response->professional_qualification_field == $key ? "checked" : "" }}>
                            <label class="form-check-label fw-normal" for="trinhdo_{{ $key }}">{{ $item }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- 20. Kiến thức kỹ năng từ trường có phù hợp công việc -->
                <div class="mb-4">
                    <label class="form-label fw-bold">20. Anh/Chị có học được kiến thức/kỹ năng cần thiết từ trường cho
                        công việc không?</label>
                    @foreach (config('config.level_knowledge_acquired') as $key => $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="level_knowledge_acquired" id="kt_{{ $key }}"
                                value="{{ $key }}" disabled {{ $response->level_knowledge_acquired == $key ? "checked" : "" }}>
                            <label class="form-check-label fw-normal" for="kt_{{ $key }}">{{ $item }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- 21. Mức lương khởi điểm -->
                <div class="mb-4">
                    <label class="form-label fw-bold">21. Mức lương khởi điểm (triệu đồng/tháng)</label>
                    <input type="text" class="form-control" name="starting_salary"
                        value="{{ $response->starting_salary }}" readonly>
                </div>

                <!-- 22. Mức thu nhập hiện tại -->
                <div class="mb-4">
                    <label class="form-label fw-bold">22. Mức thu nhập bình quân/tháng hiện nay (triệu đồng)</label>
                    @foreach (config('config.average_income') as $key => $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input" disabled type="radio" name="average_income" {{ $response->average_income == $key ? "checked" : "" }} id="tn_{{ $key }}" value="{{ $key }}">
                            <label class="form-check-label fw-normal" for="tn_{{ $key }}">{{ $item }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- 23. Hình thức tìm được việc làm -->
                <div class="mb-4">
                    <label class="form-label fw-bold">23. Anh/Chị tìm được việc làm thông qua những hình thức nào? <span
                            class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                    @php
                        $tim_viec = config('config.recruitment_type');
                        $recruit = json_decode($response->recruitment_type, true);
                    @endphp
                    @foreach ($tim_viec as $index => $value)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="recruitment_type[]" disabled
                                id="ht_{{ $index }}" value="{{ $index }}" {{ in_array($index, $recruit['value']) ? "checked" : "" }}>
                            <label class="form-check-label fw-normal" for="ht_{{ $index }}">{{ $value }}</label>
                        </div>
                    @endforeach
                    @if (!empty($recruit['content_other']))
                        <div class="mt-2">
                            <input type="text" class="form-control" readonly value="{{ $recruit['content_other'] }}">
                        </div>
                    @endif
                </div>

                <!-- 24. Hình thức tuyển -->
                <div class="mb-4">
                    @php
                        $job_search_method_value = json_decode($response->job_search_method, true);
                    @endphp
                    <label class="form-label fw-bold">24. Anh/Chị được tuyển theo hình thức nào?</label>
                    @foreach (config('config.job_search_method') as $key => $item)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="job_search_method" disabled
                                id="ht_tuyen_{{ $key }}" value="{{ $key }}" {{ in_array($key, $job_search_method_value['value']) ? "checked" : "" }}>
                            <label class="form-check-label fw-normal" for="ht_tuyen_{{ $key }}">{{ $item }}</label>
                        </div>
                    @endforeach
                    @if (!empty($job_search_method_value['content_other']))
                        <div class="mt-2">
                            <input type="text" class="form-control" readonly
                                value="{{ $job_search_method_value['content_other'] }}">
                        </div>
                    @endif
                </div>

                <!-- 25. Kỹ năng mềm -->
                <div class="mb-4">
                    <label class="form-label fw-bold">25. Trong quá trình làm việc, Anh/Chị cần những kỹ năng mềm nào
                        sau đây? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                    @php
                        $ky_nang = config('config.soft_skills_required');
                        $soft_skills_required = json_decode($response->soft_skills_required, true);
                    @endphp
                    @foreach ($ky_nang as $index => $value)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="soft_skills_required[]" disabled
                                id="kn_{{ $index }}" value="{{ $index }}" {{ in_array($index, $soft_skills_required['value']) ? "checked" : "" }}>
                            <label class="form-check-label fw-normal" for="kn_{{ $index }}">{{ $value }}</label>
                        </div>
                    @endforeach
                    @if (!empty($soft_skills_required['content_other']))
                        <div class="mt-2">
                            <input type="text" class="form-control" readonly
                                value="{{ $soft_skills_required['content_other'] }}">
                        </div>
                    @endif
                </div>

                <!-- 26. Khóa học nâng cao -->
                <div class="mb-4">
                    <label class="form-label fw-bold">26. Sau khi được tuyển dụng, Anh/Chị có phải tham gia khóa học
                        nâng cao nào dưới đây để đáp ứng công việc không? <span class="fw-normal">(Có thể chọn nhiều lựa
                            chọn)</span></label>
                    @php
                        $nang_cao = config('config.must_attended_courses');
                        $must_attended_courses = json_decode($response->must_attended_courses, true);
                    @endphp
                    @foreach ($nang_cao as $index => $value)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="must_attended_courses[]" disabled {{ in_array($index, $must_attended_courses['value']) ? "checked" : "" }} id="nc_{{ $index }}"
                                value="{{ $index }}">
                            <label class="form-check-label fw-normal" for="nc_{{ $index }}">{{ $value }}</label>
                        </div>
                    @endforeach
                    @if (!empty($must_attended_courses['content_other']))
                        <div class="mt-2">
                            <input type="text" class="form-control" readonly
                                value="{{ $must_attended_courses['content_other'] }}">
                        </div>
                    @endif
                </div>

                <!-- 27. Giải pháp nâng tỷ lệ có việc làm -->
                <div class="mb-4">
                    <label class="form-label fw-bold">27. Theo Anh/Chị, những giải pháp nào sau đây giúp tăng tỷ lệ có
                        việc làm đúng ngành của sinh viên tốt nghiệp từ chương trình đào tạo mà Anh/Chị đã học? <span
                            class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                    @php
                        $giai_phap = config('config.solutions_get_job');
                        $solutions_get_job = json_decode($response->solutions_get_job, true);
                    @endphp
                    @foreach ($giai_phap as $index => $value)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="solutions_get_job[]" disabled {{ in_array($index, $solutions_get_job['value']) ? "checked" : "" }} id="gp_{{ $index }}"
                                value="{{ $index }}">
                            <label class="form-check-label fw-normal" for="gp_{{ $index }}">{{ $value }}</label>
                        </div>
                    @endforeach
                    @if (!empty($solutions_get_job['content_other']))
                        <div class="mt-2">
                            <input type="text" class="form-control" readonly
                                value="{{ $solutions_get_job['content_other'] }}">
                        </div>
                    @endif
                </div>

                <!-- Lời cảm ơn -->
                <div class="text-center mt-4">
                    <p class="fw-semibold mb-1" style="font-style: italic;">Xin trân trọng cảm ơn sự hợp tác của
                        Anh/Chị!</p>
                    <p style="font-style: italic; color: #6b7280; margin-top: 0.5rem;">Kính chúc Anh/Chị sức khỏe và
                        thành công!</p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>