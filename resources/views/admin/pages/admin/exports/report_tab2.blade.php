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

        <tr>
            <td>{{ $item->code }}</td>
            <td>{{ $item->full_name }}</td>
            <td>{{ $item->gender == 'female' ? 'x' : '' }}</td>
            <td>'{{ $item->citizen_identification }}</td>
            <td>{{ optional($major)->code }}</td>
            <td>{{ optional($graduation)->certification }}</td>
            <td>{{ optional($graduation)->certification_date ? date('d-m-Y', strtotime($graduation->certification_date)) : '' }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ $item->email }}</td>
            <td></td>
            <td>{{ $res ? 'Có' : 'Không' }}</td>
            <td>{{ optional($major)->name }}</td>
            <td>{{ $item->school_year_end ?? '' }}</td>
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
