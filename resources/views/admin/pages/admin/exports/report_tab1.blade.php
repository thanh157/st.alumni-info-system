<table style="text-align: center;">
    <thead>
         <tr>
            <th colspan="20" style="font-weight: bold; text-align: center; font-size: 14px;">HỌC VIỆN NÔNG NGHIỆP VIỆT
                NAM</th>
        </tr>
        <tr>
            <th colspan="20" style="font-weight: bold; text-align: center; font-size: 14px;">BAN QUẢN LÝ ĐÀO TẠO.</th>
        </tr>
        <tr>
            <th colspan="20"
                style="font-weight: bold; text-align: center; font-size: 16px; text-decoration: underline;">
                BÁO CÁO TÌNH HÌNH VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}
            </th>
        </tr>
        <tr>
            <th colspan="20"></th> 
        </tr>

         <tr style="font-weight: bold;">
            <th rowspan="3" style="text-align: center; vertical-align: middle;">TT</th>
            <th rowspan="3" style="text-align: center; vertical-align: middle;">Mã ngành<br>(Ghi bằng số theo mã ngành
                tuyển sinh)</th>
            <th rowspan="3" style="text-align: center; vertical-align: middle;">Tên ngành đào tạo</th>
            <th colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;">(4)<br>Số sinh viên tốt
                nghiệp</th>
            <th colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;">(5)<br>Số sinh viên phản hồi
            </th>
            <th colspan="5" style="text-align: center; vertical-align: middle;">Tình hình việc làm</th>
            <th rowspan="3" style="text-align: center; vertical-align: middle;">Tỷ lệ có việc làm / phản hồi</th>
            <th rowspan="3" style="text-align: center; vertical-align: middle;">Tỷ lệ có việc làm / tốt nghiệp</th>
            <th colspan="4" rowspan="2" style="text-align: center; vertical-align: middle;">Khu vực làm việc</th>
            <th rowspan="3" style="text-align: center; vertical-align: middle;">Nơi làm việc<br>(Tỉnh/TP)</th>
        </tr>
        <tr style="font-weight: bold;">
            <th colspan="3" style="text-align: center; vertical-align: middle;">Có việc làm</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Tiếp tục học</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle;">Chưa có việc làm</th>
        </tr>
        <tr style="font-weight: bold;">
            <th style="text-align: center; vertical-align: middle;">Tổng số</th>
            <th style="text-align: center; vertical-align: middle;">Nữ</th>
            <th style="text-align: center; vertical-align: middle;">Tổng số</th>
            <th style="text-align: center; vertical-align: middle;">Nữ</th>
            <th style="text-align: center; vertical-align: middle;">Đúng ngành</th>
            <th style="text-align: center; vertical-align: middle;">Liên quan</th>
            <th style="text-align: center; vertical-align: middle;">Không liên quan</th>
            <th style="text-align: center; vertical-align: middle;">Nhà nước</th>
            <th style="text-align: center; vertical-align: middle;">Tư nhân</th>
            <th style="text-align: center; vertical-align: middle;">Tự tạo</th>
            <th style="text-align: center; vertical-align: middle;">Nước ngoài</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td></td>
            <td>Tổng hợp</td>
            <td>{{ $r1['total_student'] }}</td>
            <td>{{ $r1['total_nu'] }}</td>
            <td>{{ $r1['total_res'] }}</td>
            <td>{{ $r1['total_res_nu'] }}</td>
            <td>{{ $r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan }}
            </td>
            <td>{{ $r1_trained_field->dung_nganh }}</td>
            <td>{{ $r1_trained_field->lien_quan }}</td>
            <td>{{ $r1_trained_field->khong_lien_quan }}</td>
            <td>{{ $r2->where('employment_status', 2)->count() }}</td>
            <td>{{ $r2->where('employment_status', 3)->count() }}</td>
            <td>{{ $r1['total_res'] > 0 ? round(($r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan) / $r1['total_res'] * 100, 2) . '%' : '0%' }}
            </td>
            <td>{{ $r1['total_student'] > 0 ? round(($r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan) / $r1['total_student'] * 100, 2) . '%' : '0%' }}
            </td>
            <td>{{ $r1_work_area->nha_nuoc }}</td>
            <td>{{ $r1_work_area->tu_nhan }}</td>
            <td>{{ $r1_work_area->tu_tao }}</td>
            <td>{{ $r1_work_area->nuoc_ngoai }}</td>
            <td></td>
        </tr>
    </tbody>
</table>