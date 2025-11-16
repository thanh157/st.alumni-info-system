<table style="width:100%; border-collapse: collapse;">

    <!-- ====== HEADER CĂN TRÁI ====== -->
    <tr>
        <th colspan="2" style="text-align: left;font-size:14px; text-align:center;">
            HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</th>
    </tr>
    <tr>
        <th colspan="2"
            style="text-align: left; font-weight:bold;text-align:center;font-size:14px;text-decoration: underline;">
            KHOA: {{ $facultyName ?? '............' }}
        </th>
    </tr>

    <tr>
        <td colspan="20" style="height:10px;"></td>
    </tr>

    <!-- ====== TIÊU ĐỀ MẪU SỐ ====== -->
    <tr>
        <td colspan="10" style="text-align:center; font-weight:bold; font-size:15px;">
            BÁO CÁO TÌNH HÌNH VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM {{ $schoolYear }}
        </td>
    </tr>

    <tr>
        <td colspan="20" style="height:10px;"></td>
    </tr>

    <!-- ====== HEADER TABLE ====== -->
    <thead>
        <tr style="font-weight: bold; text-align: center;">
            <th rowspan="3">TT</th>
            <th rowspan="3">Mã ngành<br>(Ghi theo mã ngành tuyển sinh theo thông tư số 24/2017/TT-BGDDT. Khoa lấy
                thông tin mã ngành tại mẫu số 02)</th>
            <th rowspan="3">Tên ngành đào tạo</th>

            <th colspan="2" rowspan="2"><br>Số sinh viên tốt nghiệp</th>
            <th colspan="2" rowspan="2"><br>Số sinh viên phản hồi</th>

            <th colspan="5">Tình hình việc làm</th>

            <th rowspan="3">Tỷ lệ sinh viên có việc làm/ Tổng số sinh viên phản hồi</th>
            <th rowspan="3">Tỷ lệ sinh viên có việc làm/ Tổng số sinh viên tốt nghiệp</th>

            <th colspan="4" rowspan="2">Khu vực làm việc</th>
            <th rowspan="3">Nơi làm việc<br>(Tỉnh/TP)<br>(Tập hợp theo danh sách sinh viên phản hồi ở mẫu số 3)</th>
        </tr>

        <tr style="font-weight: bold; text-align: center;">
            <th colspan="3">Có việc làm</th>
            <th rowspan="2">Tiếp tục học</th>
            <th rowspan="2">Chưa có việc làm</th>
        </tr>

        <tr style="font-weight: bold; text-align: center;">
            <th>Tổng số</th>
            <th>Nữ</th>

            <th>Tổng số</th>
            <th>Nữ</th>

            <th>Đúng ngành đào tạo</th>
            <th>Liên quan đến ngành đào tạo</th>
            <th>Không liên quan đến ngành đào tạo</th>

            <th>Nhà nước</th>
            <th>Tư nhân</th>
            <th>Tự tạo việc làm</th>
            <th>Có yếu tố nước ngoài</th>
        </tr>
    </thead>

    <!-- ====== BODY ====== -->
    <tbody>
        <tr style="text-align:center;">
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

            <td>{{ $r1['total_res'] > 0
                ? round(
                        (($r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan) /
                            $r1['total_res']) *
                            100,
                        2,
                    ) . '%'
                : '0%' }}
            </td>

            <td>{{ $r1['total_student'] > 0
                ? round(
                        (($r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan) /
                            $r1['total_student']) *
                            100,
                        2,
                    ) . '%'
                : '0%' }}
            </td>

            <td>{{ $r1_work_area->nha_nuoc }}</td>
            <td>{{ $r1_work_area->tu_nhan }}</td>
            <td>{{ $r1_work_area->tu_tao }}</td>
            <td>{{ $r1_work_area->nuoc_ngoai }}</td>

            <td></td>
        </tr>
    </tbody>

</table>
