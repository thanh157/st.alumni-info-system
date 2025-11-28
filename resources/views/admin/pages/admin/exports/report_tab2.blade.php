<table>
    <thead>
        <tr>
            <th colspan="4" style="text-align: left;font-size:14px; text-align:center;">
                HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</th>
        </tr>
        <tr>
            <th colspan="4"
                style="text-align: left; font-weight:bold;text-align:center;font-size:14px;text-decoration: underline;">
                KHOA CÔNG NGHỆ THÔNG TIN 
            </th>
        </tr>
        <tr>
            <th colspan="13" style="font-weight: bold; text-align: center; font-size: 16px">
                DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}
            </th>
        </tr>
        <tr>
            <th colspan="13"></th>
        </tr>
        <tr style="font-weight: bold;">
            <th rowspan="2" style="text-align: center; vertical-align: middle;">TT</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Mã sinh viên</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Họ và tên</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Nữ</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">
                Số thẻ CCCD
            </th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Mã ngành đào tạo</th>
            <th colspan="2" style="text-align: center; vertical-align: middle;">Quyết định tốt nghiệp</th>
            <th colspan="2" style="text-align: center; vertical-align: middle;">Thông tin liên hệ</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Hình thức khảo sát<br>(Online, điện
                thoại, email, phỏng vấn, gửi tài liệu qua bưu điện…)</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Có phản hồi<br>(Có phản hồi đánh dấu
                X)</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Ghi chú</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Ngành</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Khoa</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="text-align: center; vertical-align: middle;">Số Quyết định</th>
            <th style="text-align: center; vertical-align: middle;">Ngày ký Quyết định</th>
            <th style="text-align: center; vertical-align: middle;">Số điện thoại</th>
            <th style="text-align: center; vertical-align: middle;">Email<br>(KHÔNG điền thông tin email của sinh viên do HVN cấp)</th>
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
                <td>{{ $loop->iteration }}</td>
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
                <td>{{ $item->survey_method }}</td>
                <td>{{ $res ? 'x' : '' }}</td>
                <td>{{ $item->notes }}</td>
                <td>{{ optional($major)->name }}</td>
                <td>{{ $item->department }}</td>

            </tr>
        @empty
            <tr>
                <td colspan="13" style="text-align: center;">Không có dữ liệu sinh viên cho đợt khảo sát này.</td>
            </tr>
        @endforelse
    </tbody>
</table>
