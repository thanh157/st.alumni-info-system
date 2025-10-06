@php
    // Gán biến cho dễ sử dụng, thêm giá trị mặc định để tránh lỗi
    $data = $data ?? [];
    $schoolYear = $data['schoolYear'] ?? '';
    $r1 = $data['r1'] ?? [];
    $r2 = $data['r2'] ?? collect();
    $studentTab2 = $data['studentTab2'] ?? collect();
    $r1_trained_field = $data['r1_trained_field'] ?? null;
    $r1_work_area = $data['r1_work_area'] ?? null;
    $report1 = $data['report1'] ?? collect();

    // Chuẩn bị dữ liệu một lần để tối ưu, tránh truy vấn N+1 trong vòng lặp
    $majors = \App\Models\Major::all()->keyBy('id');
    $graduationData = collect();
    if ($studentTab2->isNotEmpty()) {
        $graduationData = \Illuminate\Support\Facades\DB::table('graduation_student')
            ->join('graduation', 'graduation_student.graduation_id', '=', 'graduation.id')
            ->whereIn('graduation_student.student_id', $studentTab2->pluck('id'))
            ->select('graduation_student.student_id', 'graduation.certification', 'graduation.certification_date')
            ->get()->keyBy('student_id');
    }
@endphp

@if($type == 'report1')
    {{-- ================================================================= --}}
    {{-- BÁO CÁO MẪU 1: TỔNG HỢP --}}
    {{-- ================================================================= --}}
    <table>
        <thead>
        <tr><th colspan="20" style="font-size: 16px; font-weight: bold; text-align: center;">BÁO CÁO TÌNH HÌNH VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}</th></tr>
        <tr>
            <th rowspan="3">TT</th>
            <th rowspan="3">Mã ngành</th>
            <th rowspan="3">Tên ngành đào tạo</th>
            <th colspan="2" rowspan="2">Số sinh viên tốt nghiệp</th>
            <th colspan="2" rowspan="2">Số sinh viên phản hồi</th>
            <th colspan="5">Tình hình việc làm</th>
            <th rowspan="3">Tỷ lệ có việc làm / phản hồi</th>
            <th rowspan="3">Tỷ lệ có việc làm / tốt nghiệp</th>
            <th colspan="4" rowspan="2">Khu vực làm việc</th>
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
        @forelse($report1 as $key => $row)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>'{{ $row->training_industry_id }}</td>
                <td>{{ $row->ten_nganh }}</td>
                <td>{{ $row->sv_tot_nghiep }}</td>
                <td>{{ $row->sv_nu_tot_nghiep }}</td>
                <td>{{ $row->tong_phan_hoi }}</td>
                <td>{{ $row->nu_phan_hoi }}</td>
                <td>{{ $row->co_viec_lam }}</td>
                <td>{{ $row->viec_lam_dung_nganh }}</td>
                <td>{{ $row->viec_lam_lien_quan }}</td>
                <td>{{ $row->viec_lam_khong_lien_quan }}</td>
                <td>{{ $row->tiep_tuc_hoc }}</td>
                <td>{{ $row->chua_co_viec }}</td>
                <td>{{ $row->ty_le_co_viec_phan_hoi }}%</td>
                <td>{{ $row->ty_le_co_viec_tot_nghiep }}%</td>
                <td>{{ $row->lam_viec_nha_nuoc }}</td>
                <td>{{ $row->lam_viec_tu_nhan }}</td>
                <td>{{ $row->tu_tao_viec_lam }}</td>
                <td>{{ $row->yeu_to_nuoc_ngoai }}</td>
            </tr>
        @empty
            <tr><td colspan="19">Không có dữ liệu.</td></tr>
        @endforelse
        </tbody>
    </table>

@elseif($type == 'report2')
    {{-- ================================================================= --}}
    {{-- BÁO CÁO MẪU 2: DANH SÁCH SINH VIÊN TỐT NGHIỆP --}}
    {{-- ================================================================= --}}
    <table>
        <thead>
        <tr><th colspan="12" style="font-size: 14px; font-weight: bold; text-align: center;">DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}</th></tr>
        <tr>
            <th>Mã SV</th><th>Họ và tên</th><th>Nữ</th><th>Số CCCD/CMND</th><th>Mã ngành</th><th>Số QĐTN</th><th>Ngày ký QĐTN</th><th>Điện thoại</th><th>Email</th><th>Có phản hồi</th><th>Ngành</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($studentTab2 as $item)
            @php
                $graduation = $graduationData->get($item->id);
                $res = $r2->firstWhere('code_student', $item->code);
                $major = $majors->get($item->training_industry_id);
            @endphp
            <tr>
                <td>'{{ $item->code }}</td><td>{{ $item->full_name }}</td><td>{{ $item->gender == 'female' ? 'x' : '' }}</td><td>'{{ $item->citizen_identification }}</td>
                <td>'{{ optional($major)->code }}</td><td>{{ optional($graduation)->certification }}</td><td>{{ optional($graduation)->certification_date ? date('d-m-Y', strtotime($graduation->certification_date)) : '' }}</td>
                <td>'{{ $item->phone }}</td><td>{{ $item->email }}</td><td>{{ $res ? 'Có' : 'Không' }}</td><td>{{ optional($major)->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@elseif($type == 'report3')
    {{-- ================================================================= --}}
    {{-- BÁO CÁO MẪU 3: CHI TIẾT PHẢN HỒI --}}
    {{-- ================================================================= --}}
    <table>
        <thead>
        <tr><th colspan="45" style="font-size: 14px; font-weight: bold; text-align: center;">DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }} PHẢN HỒI VỀ TÌNH HÌNH VIỆC LÀM</th></tr>
        <tr>
            <th rowspan="3">Mã sinh viên</th><th rowspan="3">Họ và tên</th><th rowspan="3">Ngày sinh</th><th rowspan="3">Giới tính</th>
            <th rowspan="3">Số thẻ CCCD/CMND</th><th rowspan="3">Mã ngành đào tạo</th>
            <th rowspan="3">Điện thoại</th><th rowspan="3">Email</th><th colspan="5">Tình hình việc làm</th>
            <th colspan="4">Khu vực làm việc</th><th rowspan="3">Nơi làm việc (Tỉnh/ Tp)</th>
            <th colspan="4">Thời gian có việc làm</th><th colspan="4">Thu nhập bình quân/1 tháng</th>
            <th colspan="3">Kiến thức, kỹ năng từ nhà trường</th><th colspan="5">Hình thức tìm việc làm</th>
            <th colspan="8">Kỹ năng mềm cần thiết</th><th colspan="7">Khóa học đã tham gia</th>
            <th colspan="7">Giải pháp nâng cao tỷ lệ việc làm</th>
        </tr>
        <tr>
            <th colspan="3">Có việc làm</th><th rowspan="2">Tiếp tục học</th><th rowspan="2">Chưa có việc làm</th>
            <th>Nhà nước</th><th>Tư nhân</th><th>Có yếu tố nước ngoài</th><th>Tự tạo việc làm</th>
            <th>Dưới 3 tháng</th><th>Từ 3-6 tháng</th><th>Từ 6-12 tháng</th><th>Trên 12 tháng</th>
            <th>Dưới 5 triệu</th><th>Từ 5-10 triệu</th><th>Từ 10-15 triệu</th><th>Trên 15 triệu</th>
            <th>Đã học được</th><th>Học được một phần</th><th>Không học được</th>
            <th>Trường/khoa giới thiệu</th><th>Bạn bè, người quen</th><th>Tự tìm</th><th>Tự tạo</th><th>Khác</th>
            <th>Giao tiếp</th><th>Lãnh đạo</th><th>Thuyết trình</th><th>Tiếng Anh</th>
            <th>Làm việc nhóm</th><th>Tin học</th><th>Viết báo cáo</th><th>Khác</th>
            <th>Chuyên môn</th><th>Nghiệp vụ</th><th>Công nghệ thông tin</th>
            <th>Ngoại ngữ</th><th>Quản lý</th><th>Học lên cao</th><th>Khác</th>
            <th>Trao đổi kinh nghiệm</th><th>Chia sẻ từ cựu SV</th>
            <th>Trao đổi với nhà tuyển dụng</th><th>DN tham gia đào tạo</th>
            <th>Cập nhật chương trình</th><th>Tăng cường thực hành</th><th>Khác</th>
        </tr>
        <tr><th>Đúng ngành</th><th>Liên quan</th><th>Không liên quan</th></tr>
        </thead>
        <tbody>
        @foreach ($r2 as $item)
            <tr>
                <td>'{{ $item->code_student }}</td><td>{{ $item->full_name }}</td><td>{{ !empty($item->dob) ? date('d-m-Y', strtotime($item->dob)) : '' }}</td>
                <td>{{ $item->gender == 'male' ? 'Nam' : 'Nữ' }}</td><td>'{{ $item->identification_card_number }}</td><td>'{{ optional($majors->get($item->training_industry_id))->code }}</td>
                <td>'{{ $item->phone_number }}</td><td>{{ $item->email }}</td>
                <td>{{ $item->trained_field == 1 ? 1 : 0 }}</td><td>{{ $item->trained_field == 2 ? 1 : 0 }}</td><td>{{ $item->trained_field == 3 ? 1 : 0 }}</td>
                <td>{{ $item->employment_status == 2 ? 1 : 0 }}</td><td>{{ $item->employment_status == 3 ? 1 : 0 }}</td>
                <td>{{ $item->work_area == '1' ? 1 : 0 }}</td><td>{{ $item->work_area == '2' ? 1 : 0 }}</td>
                <td>{{ $item->work_area == '4' ? 1 : 0 }}</td><td>{{ $item->work_area == '3' ? 1 : 0 }}</td>
                <td>{{ $item->city_work_id }}</td>
                @foreach (config('config.employed_since', []) as $k => $v) <td>{{ $k == $item->employed_since ? 1 : 0 }}</td> @endforeach
                @foreach (config('config.average_income', []) as $k => $v) <td>{{ $k == $item->average_income ? 1 : 0 }}</td> @endforeach
                @foreach (config('config.level_knowledge_acquired', []) as $k => $v) <td>{{ $k == $item->level_knowledge_acquired ? 1 : 0 }}</td> @endforeach
                @foreach (config('config.recruitment_type', []) as $k => $v) @php $data_json = json_decode($item->recruitment_type, true); @endphp <td>{{ in_array($k, data_get($data_json, 'value', [])) ? 1 : 0 }}</td> @endforeach
                @foreach (config('config.soft_skills_required', []) as $k => $v) @php $data_json = json_decode($item->soft_skills_required, true); @endphp <td>{{ in_array($k, data_get($data_json, 'value', [])) ? 1 : 0 }}</td> @endforeach
                @foreach (config('config.must_attended_courses', []) as $k => $v) @php $data_json = json_decode($item->must_attended_courses, true); @endphp <td>{{ in_array($k, data_get($data_json, 'value', [])) ? 1 : 0 }}</td> @endforeach
                @foreach (config('config.solutions_get_job', []) as $k => $v) @php $data_json = json_decode($item->solutions_get_job, true); @endphp <td>{{ in_array($k, data_get($data_json, 'value', [])) ? 1 : 0 }}</td> @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

@endif

