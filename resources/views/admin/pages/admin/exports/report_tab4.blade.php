<table>
    <thead>
        {{-- Tiêu đề --}}
        <tr>
            <th colspan="27" style="font-weight: bold; text-align: center; font-size: 16px;">
                THÔNG TIN CỰU SINH VIÊN KHOA CÔNG NGHỆ THÔNG TIN
            </th>
        </tr>
        <tr>
            <th colspan="27"></th> {{-- Dòng trống --}}
        </tr>

        {{-- Header của bảng --}}
        <tr style="font-weight: bold;">
            {{-- ĐÃ SỬA LỖI: rowspan_2" thành rowspan="2" --}}
            <th rowspan="2" style="text-align: center; vertical-align: middle;">STT</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Mã SV</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Họ và Tên</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Ngày sinh</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Giới tính</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Dân tộc</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Nơi ở hiện tại</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Quốc tịch</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Tên lớp</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Khóa học, niên khoá</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Tên khoa</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Ngành học</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Hệ đào tạo</th>
            <th colspan="5" style="text-align: center; vertical-align: middle;">Các bậc đã học tại Học viện</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">SĐT đang dùng</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Email (Nếu có)</th>
            <th colspan="2" style="text-align: center; vertical-align: middle;">Tình trạng công việc hiện tại</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Đơn vị công tác hiện tại</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Chức vụ, chức danh hiện tại</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Phần thưởng, giải thưởng, bằng khen</th>
            <th colspan="2" style="text-align: center; vertical-align: middle;">Tình trạng kết nối với cá nhân, tập thể,
                đơn vị thuộc Học viện Nông nghiệp Việt Nam</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="text-align: center; vertical-align: middle;">Trung cấp</th>
            <th style="text-align: center; vertical-align: middle;">Cao đẳng</th>
            <th style="text-align: center; vertical-align: middle;">Đại học</th>
            <th style="text-align: center; vertical-align: middle;">Thạc sĩ</th>
            <th style="text-align: center; vertical-align: middle;">Tiến sĩ</th>
            <th style="text-align: center; vertical-align: middle;">Đang công tác</th>
            <th style="text-align: center; vertical-align: middle;">Nghỉ hưu</th>
            <th style="text-align: center; vertical-align: middle;">Chưa kết nối</th>
            <th style="text-align: center; vertical-align: middle;">Đã kết nối theo nhóm lớp, khoá, khoa, Học viện</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($alumniData as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->student_code ?? '' }}</td>
                <td>{{ $item->full_name ?? '' }}</td>
                <td>{{ $item->date_of_birth ? date('d/m/Y', strtotime($item->date_of_birth)) : '' }}</td>
                <td>{{ $item->gender == 'male' ? 'Nam' : ($item->gender == 'female' ? 'Nữ' : '') }}</td>

                {{-- Bạn cần thay thế các 'item->...' cho đúng với cột DB của bạn --}}
                <td>{{ $item->ethnicity ?? '' }}</td>
                <td>{{ $item->address ?? '' }}</td>
                <td>{{ $item->nationality ?? 'Việt Nam' }}</td>
                <td>{{ $item->class_name ?? '' }}</td>
                <td>{{ $item->course ?? '' }}</td>
                <td>{{ $item->faculty_name ?? '' }}</td>
                <td>{{ $item->major_name ?? '' }}</td>
                <td>{{ $item->training_system ?? '' }}</td>

                {{-- Các bậc đã học (CẦN SỬA) --}}
                <td>{{-- $item->has_vocational ? 'x' : '' --}}</td>
                <td>{{-- $item->has_college ? 'x' : '' --}}</td>
                <td>{{-- $item->has_university ? 'x' : '' --}}</td>
                <td>{{-- $item->has_masters ? 'x' : '' --}}</td>
                <td>{{-- $item->has_phd ? 'x' : '' --}}</td>

                <td>{{ $item->phone ?? '' }}</td>
                <td>{{ $item->email ?? '' }}</td>

                {{-- Tình trạng công việc (CẦN SỬA) --}}
                <td>{{-- $item->is_working ? 'x' : '' --}}</td>
                <td>{{-- $item->is_retired ? 'x' : '' --}}</td>

                <td>{{ $item->company_name ?? '' }}</td>
                <td>{{ $item->position ?? '' }}</td>
                <td>{{ $item->awards ?? '' }}</td>

                {{-- Tình trạng kết nối (CẦN SỬA) --}}
                <td>{{-- $item->is_connected == false ? 'x' : '' --}}</td>
                <td>{{-- $item->is_connected == true ? 'x' : '' --}}</td>
            </tr>
        @empty
            <tr>
                <td colspan="27" style="text-align: center;">Chưa có dữ liệu cựu sinh viên.</td>
            </tr>
        @endforelse
    </tbody>
</table>