@extends('admin.layouts.master')

@section('title', 'Form-student')

@section('content')
    <style>
        body {
            background-color: #f1f3f4;
        }

        .google-form-style {
            max-width: 900px;
            margin: auto;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .google-form-style .form-section {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .google-form-style label {
            font-weight: 500;
            margin-bottom: 0.4rem;
        }

        .google-form-style input,
        .google-form-style select,
        .google-form-style textarea {
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        .google-form-style input:focus,
        .google-form-style select:focus,
        .google-form-style textarea:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.12);
        }

        .other-input { display: none; }
    </style>

    <div class="container py-4">
        <div class="google-form-style">
            <!-- Header -->
            <div class="text-center mb-4">
                <img src="{{ asset('assets/client/images/logo-vnua.jpg') }}" width="90" class="mb-2">
                <h6 class="fw-bold mb-1 text-uppercase">Bộ Nông nghiệp và Môi trường</h6>
                <p class="mb-1 text-uppercase fw-semibold">Học Viện Nông Nghiệp Việt Nam</p>
                <small class="text-muted fst-italic">Xã Gia Lâm, Thành phố Hà Nội | Điện thoại: 024.62617586 - Fax:
                    024.62617586</small>
            </div>

            <!-- Title -->
            <div class="form-section">
               <h5 class="fw-bold text-center">{{ $survey->title }}</h5>
                <p class="fw-semibold fst-italic text-center mt-4">
                    Thân gửi Anh/Chị cựu sinh viên của Học viện Nông nghiệp Việt Nam!
                </p>
                <p class="text-justify">
                    {{ $survey->description }}
                </p>
                <p class="fst-italic text-muted text-center mt-2">
                    Trân trọng cảm ơn sự cộng tác của các Anh/Chị!
                </p>
                <p class="text-end mt-2">
                    <small class="text-muted fst-italic">
                        Ngày: {{ now()->format('d/m/Y') }}
                    </small>
                </p>
            </div>

            <!-- Form Start -->
            <form action="{{ route('survey.submit') }}" method="POST" id="form-wrapper">
                @csrf
                <input type="hidden" name="survey_id" value="{{ $survey->id ?? '' }}">

                {{-- PHẦN I: THÔNG TIN CÁ NHÂN (nếu cần) --}}
                <div class="mb-4">
                    <h6 class="fw-bold">Phần I: Thông tin cá nhân</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" name="full_name" value="{{ old('full_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã sinh viên</label>
                            <input type="text" class="form-control" name="code_student" id="ma_sv" value="{{ old('code_student') }}" oninput="setKhoaHocFromMaSV()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone_number" value="{{ old('phone_number') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giới tính</label>
                            <input type="text" class="form-control" name="gender" value="{{ old('gender') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Khóa học (tự động từ MSSV)</label>
                            <input type="text" class="form-control" name="khoa_hoc" id="khoa_hoc" value="{{ old('khoa_hoc') }}">
                        </div>
                    </div>
                </div>

                {{-- Câu 11 trở đi (giữ nguyên giao diện my_form) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">11. Anh/Chị vui lòng cho biết tình trạng việc làm hiện tại của Anh/Chị</label>

                    @php $tinh_trang = config('config.tinh_trang'); @endphp
                    @foreach ($tinh_trang as $index => $value)
                        @php
                            $isEmployed = (mb_strtolower($value) === mb_strtolower('Đã có việc làm')) ? 1 : 0;
                        @endphp
                        <div class="form-check mb-2">
                            <input class="form-check-input employment-status-radio" type="radio"
                                name="employment_status" required id="tt_{{ $index }}"
                                value="{{ $index }}" data-employed="{{ $isEmployed }}">
                            <label class="form-check-label fw-normal"
                                for="tt_{{ $index }}">{{ $value }}</label>
                        </div>
                    @endforeach
                    <i class="question-10" style="font-size: 14px; color:rgb(94, 6, 6)"> *Nếu chưa có việc làm hoặc đang tiếp tục học, anh/chị trả lời tiếp câu 27. Nếu đã có việc làm, anh/chị trả lời tiếp các câu sau</i>
                </div>

                {{-- BẮT ĐẦU: các câu 12 - 25, bọc chung để ẩn/hiện --}}
                <div class="employment-details">
                    <div class="mb-3">
                        <label class="form-label">12. Tên đơn vị tuyển dụng</label>
                        <input type="text" class="form-control" placeholder="Nhập tên công ty / tổ chức" name="recruit_partner_name" value="{{ old('recruit_partner_name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">13. Địa chỉ đơn vị</label>
                        <input type="text" class="form-control mb-1" placeholder="VD: Khu 2 Hoàng Khương, Thanh Ba, Phú Thọ" name="recruit_partner_address" value="{{ old('recruit_partner_address') }}">
                        <label class="form-label">Tỉnh/Thành phố</label>
                        <input type="text" class="form-control mb-1" placeholder="VD: Hà Nội" name="recruit_partner_city" value="{{ old('recruit_partner_city') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">14. Thời gian tuyển dụng</label>
                        <input type="date" class="form-control" name="recruit_partner_date" value="{{ old('recruit_partner_date') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">15. Chức vụ, vị trí việc làm</label>
                        <input type="text" class="form-control" placeholder="VD: Dev, BA, Design..." name="recruit_partner_position" value="{{ old('recruit_partner_position') }}">
                    </div>

                    {{-- PHẦN II: NỘI DUNG KHẢO SÁT --}}
                    <h6 class="mb-4 fw-bold">Phần II: Nội dung khảo sát</h6>

                    <!-- 16. Khu vực làm việc -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">16. Đơn vị Anh/Chị đang làm việc thuộc khu vực làm việc nào?</label>
                        @foreach (config('config.work_area') as $key => $item)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="work_area" id="kv_{{ $key }}" value="{{ $key }}">
                                <label class="form-check-label fw-normal" for="kv_{{ $key }}">{{ $item }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- 17. Thời gian có việc sau tốt nghiệp -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">17. Sau khi tốt nghiệp, Anh/Chị có việc làm từ khi nào?</label>
                        @foreach (config('config.employed_since') as $key => $item)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="employed_since" id="tg_{{ $key }}" value="{{ $key }}">
                                <label class="form-check-label fw-normal" for="tg_{{ $key }}">{{ $item }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- 18. Công việc có phù hợp với ngành đào tạo -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">18. Công việc Anh/Chị đang đảm nhận có phù hợp với ngành đào tạo không?</label>
                        @foreach (config('config.trained_field') as $key => $item)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="trained_field" id="nganh_{{ $key }}" value="{{ $key }}">
                                <label class="form-check-label fw-normal" for="nganh_{{ $key }}">{{ $item }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- 19. Công việc có phù hợp với trình độ chuyên môn -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">19. Công việc Anh/Chị đang đảm nhận có phù hợp với trình độ chuyên môn không?</label>
                        @foreach (config('config.professional_qualification_field') as $key => $item)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="professional_qualification_field" id="trinhdo_{{ $key }}" value="{{ $key }}">
                                <label class="form-check-label fw-normal" for="trinhdo_{{ $key }}">{{ $item }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- 20. Kiến thức kỹ năng từ trường có phù hợp công việc -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">20. Anh/chị có học được các kiến thức và kỹ năng cần thiết từ nhà trường cho công việc theo ngành tốt nghiệp không?</label>
                        @foreach (config('config.level_knowledge_acquired') as $key => $item)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="level_knowledge_acquired" id="kt_{{ $key }}" value="{{ $key }}">
                                <label class="form-check-label fw-normal" for="kt_{{ $key }}">{{ $item }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- 21. Mức lương khởi điểm -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">21. Mức lương khởi điểm của Anh/Chị (triệu đồng/tháng)</label>
                        <input type="text" class="form-control" name="starting_salary" placeholder="10" value="{{ old('starting_salary') }}">
                    </div>

                    <!-- 22. Mức thu nhập hiện tại -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">22. Mức thu nhập bình quân/tháng tính theo VNĐ của Anh/Chị hiện nay</label>
                        @foreach (config('config.average_income') as $key => $item)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="average_income" id="tn_{{ $key }}" value="{{ $key }}">
                                <label class="form-check-label fw-normal" for="tn_{{ $key }}">{{ $item }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- 23. Hình thức tìm được việc làm (checkbox multiple) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">23. Anh/Chị tìm được việc làm thông qua những hình thức nào? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                        @php $tim_viec = config('config.recruitment_type'); @endphp
                        @foreach ($tim_viec as $index => $value)
                            @if (mb_strtolower($value) == mb_strtolower('khác'))
                                <div class="form-check mb-2">
                                    <input class="form-check-input recruitment_type_other" type="checkbox" name="recruitment_type[]" id="ht_{{ $index }}" value="{{ $index }}">
                                    <label class="form-check-label fw-normal" for="ht_{{ $index }}">Khác</label>
                                </div>
                            @else
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="recruitment_type[]" id="ht_{{ $index }}" value="{{ $index }}">
                                    <label class="form-check-label fw-normal" for="ht_{{ $index }}">{{ $value }}</label>
                                </div>
                            @endif
                        @endforeach
                        <div id="recruitment_type_other_wrapper" class="mt-2 other-input">
                            <input type="text" name="recruitment_type_other" id="recruitment_type_other" class="form-control" autocomplete="off">
                        </div>
                        <div id="recruitment_type_error" class="text-danger small d-none"></div>
                    </div>

                    <!-- 24. Hình thức tuyển (chỉ chọn 1) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">24. Anh/chị được tuyển dụng theo hình thức nào?</label>
                        @foreach (config('config.job_search_method') as $key => $item)
                            @if (mb_strtolower($item) == mb_strtolower('khác'))
                                <div class="form-check mb-2">
                                    <input class="form-check-input job_search_method_other" type="radio" name="job_search_method" id="ht23_{{ $key }}" value="{{ $key }}">
                                    <label class="form-check-label fw-normal" for="ht23_{{ $key }}">Khác</label>
                                </div>
                            @else
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="job_search_method" id="ht23_{{ $key }}" value="{{ $key }}">
                                    <label class="form-check-label fw-normal" for="ht23_{{ $key }}">{{ $item }}</label>
                                </div>
                            @endif
                        @endforeach
                        <div id="job_search_method_other_wrapper" class="mt-2 other-input">
                            <input type="text" name="job_search_method_other" id="job_search_method_other" class="form-control" autocomplete="off">
                        </div>
                        <div id="job_search_method_error" class="text-danger small d-none"></div>
                    </div>

                    <!-- 25. Kỹ năng mềm (checkbox multiple) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">25. Trong quá trình làm việc, Anh/Chị cần những kỹ năng mềm nào sau đây? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                        @php $ky_nang = config('config.soft_skills_required'); @endphp
                        @foreach ($ky_nang as $index => $value)
                            @if (mb_strtolower($value) == mb_strtolower('khác'))
                                <div class="form-check mb-2">
                                    <input class="form-check-input soft_skills_required_other" type="checkbox" name="soft_skills_required[]" id="ht_kn_{{ $index }}" value="{{ $index }}">
                                    <label class="form-check-label fw-normal" for="ht_kn_{{ $index }}">Khác</label>
                                </div>
                            @else
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="soft_skills_required[]" id="kn_{{ $index }}" value="{{ $index }}">
                                    <label class="form-check-label fw-normal" for="kn_{{ $index }}">{{ $value }}</label>
                                </div>
                            @endif
                        @endforeach
                        <div id="soft_skills_required_other_wrapper" class="mt-2 other-input">
                            <input type="text" name="soft_skills_required_other" id="soft_skills_required_other" class="form-control" autocomplete="off">
                        </div>
                        <div id="soft_skills_required_error" class="text-danger small d-none"></div>
                    </div>

                    <!-- 26. Khóa học nâng cao (checkbox multiple) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">26. Sau khi được tuyển dụng, Anh/Chị có phải tham gia khóa học nâng cao nào dưới đây để đáp ứng công việc không <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                        @php $nang_cao = config('config.must_attended_courses'); @endphp
                        @foreach ($nang_cao as $index => $value)
                            @if (mb_strtolower($value) == mb_strtolower('khác'))
                                <div class="form-check mb-2">
                                    <input class="form-check-input must_attended_courses_other" type="checkbox" name="must_attended_courses[]" id="ht_nc_{{ $index }}" value="{{ $index }}">
                                    <label class="form-check-label fw-normal" for="ht_nc_{{ $index }}">Khác</label>
                                </div>
                            @else
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="must_attended_courses[]" id="nc_{{ $index }}" value="{{ $index }}">
                                    <label class="form-check-label fw-normal" for="nc_{{ $index }}">{{ $value }}</label>
                                </div>
                            @endif
                        @endforeach
                        <div id="must_attended_courses_other_wrapper" class="mt-2 other-input">
                            <input type="text" name="must_attended_courses_other" id="must_attended_courses_other" class="form-control" autocomplete="off">
                        </div>
                        <div id="must_attended_courses_error" class="text-danger small d-none"></div>
                    </div>
                </div>
                {{-- KẾT THÚC employment-details --}}

                <!-- 27 (question-26 id used previously) -->
                <div class="mb-4" id="question-26">
                    <label class="form-label fw-bold">27. Theo Anh/Chị, những giải pháp nào sau đây giúp tăng tỷ lệ có việc làm đúng ngành của sinh viên tốt nghiệp từ chương trình đào tạo mà Anh/Chị đã học? <span class="fw-normal">(Có thể chọn nhiều lựa chọn)</span></label>
                    @php $giai_phap = config('config.solutions_get_job'); @endphp
                    @foreach ($giai_phap as $index => $value)
                        @if (mb_strtolower($value) == mb_strtolower('khác'))
                            <div class="form-check mb-2">
                                <input class="form-check-input solutions_get_job_other" type="checkbox" name="solutions_get_job[]" id="ht26_{{ $index }}" value="{{ $index }}">
                                <label class="form-check-label fw-normal" for="ht26_{{ $index }}">Khác</label>
                            </div>
                        @else
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="solutions_get_job[]" id="ht26_{{ $index }}" value="{{ $index }}">
                                <label class="form-check-label fw-normal" for="ht26_{{ $index }}">{{ $value }}</label>
                            </div>
                        @endif
                    @endforeach
                    <div id="solutions_get_job_other_wrapper" class="mt-2 other-input">
                        <input type="text" name="solutions_get_job_other" id="solutions_get_job_other" class="form-control" autocomplete="off">
                    </div>
                </div>

                <!-- Reminder -->
                <div class="alert alert-warning mt-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Vui lòng kiểm tra kỹ thông tin trước khi gửi. Mỗi sinh viên chỉ được gửi phiếu khảo sát một lần.
                </div>

                <!-- Submit button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">Gửi khảo sát</button>
                </div>

                <!-- Cảm ơn -->
                <div class="text-center mt-4">
                    <p class="fw-semibold mb-1">Xin trân trọng cảm ơn sự hợp tác của Anh/Chị!</p>
                    <p class="text-muted fst-italic mb-3">Kính chúc Anh/Chị sức khỏe và thành công!</p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setKhoaHocFromMaSV() {
            const maSV = document.getElementById('ma_sv') ? document.getElementById('ma_sv').value : '';
            const khoaHocInput = document.getElementById('khoa_hoc');
            if (!khoaHocInput) return;
            if (maSV.length >= 2 && !isNaN(maSV.substring(0,2))) {
                const khoa = maSV.substring(0, 2);
                khoaHocInput.value = 'Khóa ' + khoa;
            } else {
                khoaHocInput.value = '';
            }
        }
    </script>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Hiển thị/ẩn employment-details dựa trên lựa chọn tình trạng việc làm
            function toggleEmploymentDetails() {
                const $selected = $('input[name="employment_status"]:checked');
                if (!$selected.length) {
                    $('.employment-details').hide();
                    $('#question-26').hide();
                    return;
                }
                const employedAttr = $selected.data('employed');
                // Nếu data-employed == 1 OR value == '0' (nếu config dùng 0 cho "Đã có việc làm")
                if (String(employedAttr) === '1' || String($selected.val()) === '0') {
                    $('.employment-details').show();
                    $('#question-26').show();
                } else {
                    $('.employment-details').hide();
                    $('#question-26').show();
                }
            }

            // Khởi tạo: ẩn employment-details nếu chưa chọn
            $('.employment-details').hide();
            $('#question-26').hide();

            $(document).on('change', '.employment-status-radio', function() {
                toggleEmploymentDetails();
            });

            // Handlers cho "Khác" inputs (hiển thị field khi chọn Khác)
            function toggleWrapper(checkboxSelector, wrapperSelector) {
                $(document).on('change', checkboxSelector, function() {
                    const $this = $(this);
                    const $wrapper = $(wrapperSelector);
                    if ($this.is(':checkbox')) {
                        // checkbox group: show wrapper if any checked with value "khác"
                        const anyChecked = $(checkboxSelector + ':checked').length > 0;
                        if (anyChecked && $this.val() && $this.val().toString().trim() !== '') {
                            // specifically show if the changed checkbox is the one labelled "Khác"
                            if ($this.is(':checked') && ($this.next('label').text().trim().toLowerCase() === 'khác' || $this.attr('class') && $this.attr('class').indexOf('other') !== -1)) {
                                $wrapper.show();
                            } else if (!$this.is(':checked')) {
                                // hide only if none of the group's "other" are checked
                                const still = $(checkboxSelector).filter(function() {
                                    return ($(this).next('label').text().trim().toLowerCase() === 'khác' || $(this).hasClass('recruitment_type_other') || $(this).hasClass('soft_skills_required_other') || $(this).hasClass('must_attended_courses_other') || $(this).hasClass('solutions_get_job_other'));
                                }).filter(':checked').length > 0;
                                if (!still) $wrapper.hide();
                            }
                        }
                    } else {
                        // radio: show wrapper when current radio (Khác) is selected
                        if ($this.is(':checked') && ($this.next('label').text().trim().toLowerCase() === 'khác' || $this.hasClass('job_search_method_other'))) {
                            $wrapper.show();
                        } else {
                            $wrapper.hide();
                        }
                    }
                });
            }

            // apply wrappers
            toggleWrapper('.recruitment_type_other', '#recruitment_type_other_wrapper');
            toggleWrapper('.job_search_method_other', '#job_search_method_other_wrapper');
            toggleWrapper('.soft_skills_required_other', '#soft_skills_required_other_wrapper');
            toggleWrapper('.must_attended_courses_other', '#must_attended_courses_other_wrapper');
            toggleWrapper('.solutions_get_job_other', '#solutions_get_job_other_wrapper');

            // Also handle generic show/hide for any radio/checkbox whose label text is "Khác"
            $(document).on('change', 'input[type=radio][name], input[type=checkbox][name]', function() {
                const $el = $(this);
                const labelText = $el.next('label').text().trim().toLowerCase();
                if (labelText === 'khác') {
                    const wrapper = $el.closest('.mb-4').find('.other-input');
                    if ($el.is(':checked')) wrapper.show(); else wrapper.hide();
                } else if ($el.attr('type') === 'radio') {
                    // hide other-inputs in same group
                    const name = $el.attr('name');
                    $(`input[name="${name}"]`).each(function() {
                        $(this).closest('.mb-4').find('.other-input').hide();
                    });
                }
            });

            // init: hide all other-input wrappers
            $('.other-input').hide();
        });
    </script>
@endpush