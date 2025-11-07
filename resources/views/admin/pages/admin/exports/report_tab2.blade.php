<table>
    <thead>
        <tr>
            <th colspan="13" style="font-weight: bold; text-align: center; font-size: 14px;">HỌC VIỆN NÔNG NGHIỆP VIỆT
                NAM</th>
        </tr>
        <tr>
            <th colspan="13" style="font-weight: bold; text-align: center; font-size: 14px;">BAN QUẢN LÝ ĐÀO TẠO</th>
        </tr>
        <tr>
            <th colspan="13"
                style="font-weight: bold; text-align: center; font-size: 16px; text-decoration: underline;">
                DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}
            </th>
        </tr>
        <tr>
            <th colspan="13"></th>
        </tr>
        <tr style="font-weight: bold;">
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Mã sinh viên</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Họ và tên</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Nữ</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Số thẻ CCCD/CMND</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Mã ngành đào tạo<br>(Ghi bằng số theo mã
                ngành tuyển sinh của Bộ Giáo dục và Đào tạo)</th>
            <th colspan="2" style="text-align: center; vertical-align: middle;">Quyết định tốt nghiệp</th>
            <th colspan="2" style="text-align: center; vertical-align: middle;">Thông tin liên hệ</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Hình thức khảo sát<br>(Online, điện
                thoại, email …)</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Có phản hồi</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Ngành</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Khóa học</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="text-align: center; vertical-align: middle;">Số Quyết định</th>
            <th style="text-align: center; vertical-align: middle;">Ngày ký Quyết định</th>
            <th style="text-align: center; vertical-align: middle;">Điện thoại</th>
            <th style="text-align: center; vertical-align: middle;">Email</th>
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
                <td>{{ $item->code }}</td>
                <td>{{ $item->full_name }}</td>
                <td>{{ $item->gender == 'female' ? 'x' : '' }}</td>
                <td>'{{ $item->citizen_identification }}</td>
                <td>{{ optional($major)->code }}</td>
                <td>{{ optional($graduation)->certification }}</td>
                <td>{{ optional($graduation)->certification_date ? date('d-m-Y', strtotime($graduation->certification_date)) : '' }}
                </td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $res ? 'Có' : 'Không' }}</td>
                <td>{{ optional($major)->name }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="13" style="text-align: center;">Không có dữ liệu sinh viên cho đợt khảo sát này.</td>
            </tr>
        @endforelse
    </tbody>
</table>
