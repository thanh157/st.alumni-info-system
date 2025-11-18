@extends('admin.layouts.master')

@section('title', 'Đợt khảo sát')

@section('content')
    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Khảo sát - Khảo sát việc làm</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Khảo sát việc làm</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách đợt khảo sát</li>
                    </ol>
                </nav>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('admin.survey.create') }}" class="btn btn-primary mt-2 mt-sm-0">
                    <i class="bi bi-plus-lg me-1"></i> Tạo mới
                </a>
            </div>
        </div>

        @include('admin.layouts.noti')

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-bordered">
                    <thead>
                        <tr>
                            <td><strong>Tiêu đề khảo sát</strong></td>
                            <td><strong>Trạng thái</strong></td>
                            <td><strong>Đợt tốt nghiệp</strong></td>
                            <td><strong>Bắt đầu</strong></td>
                            <td><strong>Kết thúc</strong></td>
                            <td><strong>Phản hồi</strong></td>
                            <td><strong>Hành động</strong></td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->isActive() ? 'Hoạt động' : 'Ẩn' }}</td>

                                <td>
<<<<<<< Updated upstream
                                    @if ($item->graduations->count() > 0)
                                        @foreach ($item->graduations as $dot)
                                            <a target="_blank"
                                                href="{{ route('admin.graduation-student.show', ['id' => $dot->id]) }}"
                                                class="d-block text-decoration-none text-primary mb-1">
                                                {{ $dot->name }}
=======
                                    {{ $item->total_graduations }} sinh viên
                                </td>

                                <!-- Bắt đầu -->
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->start_time)->format('d/m/Y') }}
                                </td>

                                <!-- Kết thúc -->
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->end_time)->format('d/m/Y') }}
                                </td>

                                <!-- Phản hồi (Badge với màu động) -->
                                <td class="text-center">
                                    <div class="response-wrapper">
                                        <span class="response-percentage {{ $colorClass }}">{{ $percentage }}%</span>
                                        <span class="response-tooltip">
                                            <span class="tooltip-count">{{ $totalPhanHoi }}</span> /
                                            {{ $item->total_graduations }} sinh
                                            viên
                                        </span>
                                    </div>
                                </td>

                                <!-- HÀNH ĐỘNG -->
                                <td>
                                    <div class="action-group">
                                        <!-- 1. Xem trước form -->
                                        <a href="{{ route('admin.survey.form', ['id' => $item->id]) }}"
                                            class="btn btn-sm btn-outline-primary btn-action" title="Xem trước form"
                                            target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- 2. Lấy link khảo sát -->
                                        <button class="btn btn-sm btn-outline-info btn-action" title="Lấy link khảo sát"
                                            data-link="{{ route('my_form', ['survey_id' => $item->id]) }}"
                                            onclick="copySurveyLink(this)">
                                            <i class="bi bi-link-45deg"></i>
                                        </button>

                                        <!-- 3. Xem kết quả với số lượng -->
                                        @if ($totalPhanHoi > 0)
                                            <a href="{{ route('admin.survey.result', ['id' => $item->id]) }}"
                                                class="btn btn-sm btn-outline-success btn-action btn-result-count"
                                                title="Xem kết quả ({{ $totalPhanHoi }} phản hồi)">
                                                <i class="bi bi-bar-chart-fill"></i>
                                                <span class="result-badge">{{ $totalPhanHoi }}</span>
>>>>>>> Stashed changes
                                            </a>
                                            <hr />
                                        @endforeach
                                    @endif
                                </td>

                                <td>{{ $item->start_time }}</td>
                                <td>{{ $item->end_time }}</td>
                                <td>
                                    @php
                                        $totalPhanHoi = App\Models\EmploymentSurveyResponse::where(
                                            'survey_period_id',
                                            $item->id,
                                        )->count();
                                        $countDot = $item->graduations()->pluck('id')->toArray();
                                        $countStudent = \App\Models\GraduationStudent::query()
                                            ->whereIn('graduation_id', $countDot)
                                            ->count();
                                    @endphp

                                    <strong class="text-primary">
                                        {{ $totalPhanHoi }} / {{ $countStudent }}
                                    </strong>
                                </td>

<<<<<<< Updated upstream
                                <td class="text-center d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.survey.edit', ['id' => $item->id]) }}"
                                        class="btn btn-sm btn-outline-primary" title="Chỉnh sửa"
                                        style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <button class="btn btn-sm btn-outline-secondary" title="Sao chép đường dẫn"
                                        style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                                        data-link="{{ route('my_form', ['survey_id' => $item->id]) }}"
                                        onclick="copySurveyLink(this)">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
=======
                                                <!-- Tải PDF -->
                                                <li>
                                                    <a class="dropdown-item download-link"
                                                        href="{{ route('admin.surveys.downloadAllPdfs', $item->id) }}" onclick="handleZipDownload(event, this) download>


                                                                <span>Tải toàn bộ file PDF (ZIP)</span>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <hr class=" dropdown-divider">
                                                </li>
>>>>>>> Stashed changes

                                    <a href="{{ route('admin.survey.form', ['id' => $item->id]) }}"
                                        class="btn btn-sm btn-outline-primary" title="Xem trước form"
                                        style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-list-nested"></i>
                                    </a>

                                    <form action="{{ route('admin.survey.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Xác nhận xoá khảo sát này?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Xoá">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    {{-- @if ($totalPhanHoi > 0)
                                    <a href="{{ route('admin.survey.result', ['id' => $item->id]) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết khảo sát"
                                       style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </a>
                                    @endif --}}
                                    @if ($totalPhanHoi > 0)
                                        <a href="{{ route('admin.survey.result', ['id' => $item->id]) }}"
                                            class="btn btn-outline-primary btn-sm" title="Xem kết quả">
                                            <i class="bi bi-bar-chart-fill me-1"></i> ({{ $totalPhanHoi }})
                                        </a>
                                    @endif


                                    <form action="{{ route('send_mail', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Send mail?');" style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <button class="btn btn-sm btn-outline-success"
                                            title="Gửi biểu mẫu khảo sát qua mail"
                                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                                            {{--                                                 onclick="sendSurveyByEmail(this)" --}}>
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Phân trang --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $data->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function copySurveyLink(button) {
            const link = button.getAttribute('data-link') || '';
            if (!link) {
                alert('Không có đường dẫn để sao chép.');
                return;
            }
            navigator.clipboard.writeText(link).then(() => {
                alert('Đường dẫn khảo sát đã được sao chép!');
            }).catch(() => {
                alert('Sao chép thất bại, vui lòng thử lại.');
            });
        }

        function sendSurveyByEmail(button) {
            // Ví dụ đơn giản, bạn có thể hiện modal nhập email hoặc gọi API gửi mail
            alert('Chức năng gửi biểu mẫu khảo sát qua mail sẽ được phát triển sau.');
        }

        // Hàm toggleFilter nếu chưa có trong file bạn có thể thêm
        function toggleFilter(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'block') {
                el.style.display = 'none';
            } else {
                el.style.display = 'block';
            }
        }
<<<<<<< Updated upstream
=======

        document.addEventListener('DOMContentLoaded', function () {
            const confirmInput = document.getElementById('confirmSurveyName');
            if (confirmInput) {
                confirmInput.addEventListener('input', function () {
                    document.getElementById('errorMessage').classList.add('d-none');
                    this.classList.remove('is-invalid');
                });

                confirmInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        confirmDelete();
                    }
                });
            }
        });

        function showToast(message, type = 'success') {
            const bgColor = type === 'success' ? '#28a745' : '#dc3545';
            const icon = type === 'success' ? 'check-circle-fill' : 'x-circle-fill';

            const toastHTML = `
                                                    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
                                                        <div class="toast show align-items-center text-white border-0" 
                                                             style="background-color: ${bgColor}; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px;" 
                                                             role="alert">
                                                            <div class="d-flex">
                                                                <div class="toast-body d-flex align-items-center gap-2 py-3">
                                                                    <i class="bi bi-${icon}"></i>
                                                                    <span>${message}</span>
                                                                </div>
                                                                <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                                                                        onclick="this.closest('.toast').remove()"></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;

            document.body.insertAdjacentHTML('beforeend', toastHTML);

            setTimeout(() => {
                const toast = document.querySelector('.toast');
                if (toast) {
                    toast.style.transition = 'opacity 0.3s ease';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 3000);
        }
>>>>>>> Stashed changes
    </script>
@endsection
