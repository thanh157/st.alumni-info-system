@php
    // Gán biến cho dễ sử dụng
    $schoolYear = $data['schoolYear'];
    $r1 = $data['r1'];
    $r2 = $data['r2'];
    $studentTab2 = $data['studentTab2'];
    $r1_trained_field = $data['r1_trained_field'];
    $r1_work_area = $data['r1_work_area'];
    $majors = \App\Models\Major::all()->keyBy('id');
    $graduationData = \Illuminate\Support\Facades\DB::table('graduation_student')
        ->join('graduation', 'graduation_student.graduation_id', '=', 'graduation.id')
        ->whereIn('graduation_student.student_id', $studentTab2->pluck('id'))
        ->select('graduation_student.student_id', 'graduation.certification', 'graduation.certification_date')
        ->get()->keyBy('student_id');
@endphp

{{-- BÁO CÁO MẪU 1 --}}
<table>
    <thead>
    <tr><th colspan="20" style="font-size: 16px; font-weight: bold; text-align: center;">BÁO CÁO TÌNH HÌNH VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}</th></tr>
    <tr>
        <th rowspan="2">TT</th><th rowspan="2">Mã ngành</th><th rowspan="2">Tên ngành đào tạo</th>
        <th colspan="2">Số SV tốt nghiệp</th><th colspan="2">Số SV phản hồi</th>
        <th colspan="3">Có việc làm</th><th rowspan="2">Tiếp tục học</th><th rowspan="2">Chưa có việc làm</th>
        <th colspan="2">Tỷ lệ có việc làm</th><th colspan="4">Khu vực làm việc</th>
    </tr>
    <tr>
        <th>Tổng số</th><th>Nữ</th><th>Tổng số</th><th>Nữ</th>
        <th>Đúng ngành</th><th>Liên quan</th><th>Không liên quan</th>
        <th>/ phản hồi</th><th>/ tốt nghiệp</th>
        <th>Nhà nước</th><th>Tư nhân</th><th>Tự tạo</th><th>Nước ngoài</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td><td></td><td>Tổng hợp</td>
        <td>{{ $r1['total_student'] ?? 0 }}</td><td>{{ $r1['total_nu'] ?? 0 }}</td>
        <td>{{ $r1['total_res'] ?? 0 }}</td><td>{{ $r1['total_res_nu'] ?? 0 }}</td>
        <td>{{ $r1_trained_field->dung_nganh ?? 0 }}</td><td>{{ $r1_trained_field->lien_quan ?? 0 }}</td><td>{{ $r1_trained_field->khong_lien_quan ?? 0 }}</td>
        <td>{{ $r2->where('employment_status', 2)->count() }}</td><td>{{ $r2->where('employment_status', 3)->count() }}</td>
        @php $totalCoViecLam = ($r1_trained_field->dung_nganh ?? 0) + ($r1_trained_field->lien_quan ?? 0) + ($r1_trained_field->khong_lien_quan ?? 0); @endphp
        <td>{{ !empty($r1['total_res']) ? round($totalCoViecLam / $r1['total_res'] * 100, 2) : 0 }}%</td>
        <td>{{ !empty($r1['total_student']) ? round($totalCoViecLam / $r1['total_student'] * 100, 2) : 0 }}%</td>
        <td>{{ $r1_work_area->nha_nuoc ?? 0 }}</td><td>{{ $r1_work_area->tu_nhan ?? 0 }}</td>
        <td>{{ $r1_work_area->tu_tao ?? 0 }}</td><td>{{ $r1_work_area->nuoc_ngoai ?? 0 }}</td>
    </tr>
    </tbody>
</table>

<table><tr><td colspan="20"></td></tr></table>

{{-- BÁO CÁO MẪU 2 --}}
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
