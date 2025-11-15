@extends('admin.layouts.master')

@section('title', 'Đợt khảo sát')

@section('content')
    <style>
        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .action-group {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            align-items: center;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            padding: 0.5rem 0;
            min-width: 220px;
        }

        .dropdown-item {
            padding: 0.65rem 1.2rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            padding-left: 1.5rem;
        }

        .dropdown-item i {
            width: 18px;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
        }

        .dropdown-item.text-danger:hover {
            background-color: #fff5f5;
            color: #dc3545 !important;
        }

        /* Response Percentage - Compact like Badge */
        .response-wrapper {
            position: relative;
            display: inline-block;
        }

        .response-percentage {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            color: #28a745;
            cursor: help;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            transition: all 0.2s ease;
            min-width: auto;
        }

        .response-percentage:hover {
            background: #28a745;
            color: white;
            border-color: #28a745;
            transform: scale(1.05);
            box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
        }

        /* Compact Tooltip */
        .response-tooltip {
            visibility: hidden;
            position: absolute;
            z-index: 1000;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #2d3748;
            color: white;
            text-align: center;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .response-tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #2d3748 transparent transparent transparent;
        }

        .response-wrapper:hover .response-tooltip {
            visibility: visible;
            opacity: 1;
        }

        .tooltip-count {
            font-weight: 700;
            color: #4ade80;
        }

        /* Modal xác nhận xóa */
        .modal-delete-confirm .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .modal-delete-confirm .modal-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.2rem 1.5rem;
        }

        .modal-delete-confirm .modal-title {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-delete-confirm .btn-close {
            filter: brightness(0) invert(1);
        }

        .delete-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .delete-warning i {
            color: #856404;
            font-size: 1.2rem;
        }

        .confirm-input {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .confirm-input:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        }

        .survey-name-display {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
    </style>

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
                            <td><strong>Số lượng khảo sát</strong></td>
                            <td><strong>Bắt đầu</strong></td>
                            <td><strong>Kết thúc</strong></td>
                            <td><strong>Phản hồi</strong></td>
                            <td><strong>Hành động</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            @php
                                $totalPhanHoi = App\Models\EmploymentSurveyResponse::where(
                                    'survey_period_id',
                                    $item->id,
                                )->count();
                            
                                $percentage = $countStudent > 0 ? round(($totalPhanHoi /  $item->total_graduations) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->isActive() ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $item->isActive() ? 'Hoạt động' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->graduations->count() > 0)
                                        @foreach ($item->graduations as $dot)
                                            <a target="_blank"
                                                href="{{ route('admin.graduation-student.show', ['id' => $dot->id]) }}"
                                                class="d-block text-decoration-none text-primary mb-1">
                                                {{ $dot->name }}
                                            </a>
                                            @if (!$loop->last)
                                                <hr />
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                
                                <!-- Bắt đầu -->
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->start_time)->format('d/m/Y') }}
                                </td>
                                
                                <!-- Kết thúc -->
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->end_time)->format('d/m/Y') }}
                                </td>
                                
                                <!-- Phản hồi (Badge nhỏ giống Trạng thái) -->
                                <td class="text-center">
                                    <div class="response-wrapper">
                                        <span class="response-percentage">{{ $percentage }}%</span>
                                        <span class="response-tooltip">
                                            <span class="tooltip-count">{{ $totalPhanHoi }}</span> / {{  $item->total_graduations }} sinh viên
                                        </span>
                                    </div>
                                </td>

                                <!-- HÀNH ĐỘNG -->
                                <td>
                                    <div class="action-group">
                                        <!-- 1. Xem trước form -->
                                        <a href="{{ route('admin.survey.form', ['id' => $item->id]) }}"
                                            class="btn btn-sm btn-outline-primary btn-action" 
                                            title="Xem trước form"
                                            target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- 2. Lấy link khảo sát -->
                                        <button class="btn btn-sm btn-outline-info btn-action" 
                                            title="Lấy link khảo sát"
                                            data-link="{{ route('my_form', ['survey_id' => $item->id]) }}"
                                            onclick="copySurveyLink(this)">
                                            <i class="bi bi-link-45deg"></i>
                                        </button>

                                        <!-- 3. Xem kết quả (chỉ hiện khi có phản hồi) -->
                                        @if ($totalPhanHoi > 0)
                                            <a href="{{ route('admin.survey.result', ['id' => $item->id]) }}"
                                                class="btn btn-sm btn-outline-success btn-action" 
                                                title="Xem kết quả phản hồi">
                                                <i class="bi bi-bar-chart-fill"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary btn-action" 
                                                title="Chưa có phản hồi"
                                                disabled>
                                                <i class="bi bi-bar-chart"></i>
                                            </button>
                                        @endif

                                        <!-- Dropdown menu (3 chấm) -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary btn-action dropdown-toggle" 
                                                type="button" 
                                                id="dropdownMenu{{ $item->id }}" 
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false"
                                                title="Thêm tùy chọn">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu{{ $item->id }}">
                                                <!-- Gửi email (Chức năng hay dùng) -->
                                                <li>
                                                    <form action="{{ route('send_mail', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Xác nhận gửi email khảo sát đến sinh viên?');"
                                                        class="d-inline w-100">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-envelope text-success"></i>
                                                            <span>Gửi email khảo sát</span>
                                                        </button>
                                                    </form>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                <!-- Chỉnh sửa -->
                                                <li>
                                                    <a class="dropdown-item" 
                                                        href="{{ route('admin.survey.edit', ['id' => $item->id]) }}">
                                                        <i class="bi bi-pencil-square text-primary"></i>
                                                        <span>Chỉnh sửa</span>
                                                    </a>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                <!-- Xóa (Cuối cùng) -->
                                                <li>
                                                    <a class="dropdown-item text-danger" 
                                                        href="javascript:void(0);"
                                                        onclick="showDeleteModal('{{ $item->id }}', '{{ addslashes($item->title) }}')">
                                                        <i class="bi bi-trash"></i>
                                                        <span>Xóa khảo sát</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
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

    <!-- Modal xác nhận xóa -->
    <div class="modal fade modal-delete-confirm" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Xác nhận xóa khảo sát
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-warning">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác! 
                                <br>Tất cả dữ liệu phản hồi của đợt khảo sát này sẽ bị xóa vĩnh viễn.
                            </div>
                        </div>
                    </div>

                    <p class="mb-2"><strong>Tên đợt khảo sát:</strong></p>
                    <div class="survey-name-display" id="surveyNameDisplay"></div>

                    <p class="mb-2">
                        <strong>Để xác nhận, vui lòng nhập chính xác tên đợt khảo sát bên trên:</strong>
                    </p>
                    <input type="text" 
                        class="form-control confirm-input" 
                        id="confirmSurveyName" 
                        placeholder="Nhập tên đợt khảo sát..."
                        autocomplete="off">
                    
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle"></i> 
                        Nhập chính xác tên (phân biệt chữ hoa/thường)
                    </small>

                    <div id="errorMessage" class="alert alert-danger mt-3 d-none">
                        <i class="bi bi-x-circle"></i> Tên đợt khảo sát không khớp. Vui lòng kiểm tra lại.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Hủy
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="bi bi-trash me-1"></i> Xóa vĩnh viễn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSurveyName = '';
        let deleteFormAction = '';

        function copySurveyLink(button) {
            const link = button.getAttribute('data-link') || '';
            if (!link) {
                showToast('Không có đường dẫn để sao chép.', 'error');
                return;
            }
            
            navigator.clipboard.writeText(link).then(() => {
                showToast('✓ Link khảo sát đã được sao chép!', 'success');
            }).catch(() => {
                showToast('Sao chép thất bại, vui lòng thử lại.', 'error');
            });
        }

        function showDeleteModal(surveyId, surveyName) {
            currentSurveyName = surveyName;
            deleteFormAction = '{{ route('admin.survey.destroy', ':id') }}'.replace(':id', surveyId);
            
            document.getElementById('surveyNameDisplay').textContent = surveyName;
            document.getElementById('confirmSurveyName').value = '';
            document.getElementById('errorMessage').classList.add('d-none');
            document.getElementById('deleteForm').action = deleteFormAction;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();

            // Focus vào input khi modal mở
            setTimeout(() => {
                document.getElementById('confirmSurveyName').focus();
            }, 500);
        }

        function confirmDelete() {
            const inputName = document.getElementById('confirmSurveyName').value;
            const errorMessage = document.getElementById('errorMessage');
            
            if (inputName === currentSurveyName) {
                // Tên khớp - cho phép xóa
                document.getElementById('deleteForm').submit();
            } else {
                // Tên không khớp - hiện thông báo lỗi
                errorMessage.classList.remove('d-none');
                document.getElementById('confirmSurveyName').classList.add('is-invalid');
                
                // Shake animation
                const input = document.getElementById('confirmSurveyName');
                input.style.animation = 'shake 0.5s';
                setTimeout(() => {
                    input.style.animation = '';
                }, 500);
            }
        }

        // Xóa error khi người dùng gõ lại
        document.addEventListener('DOMContentLoaded', function() {
            const confirmInput = document.getElementById('confirmSurveyName');
            if (confirmInput) {
                confirmInput.addEventListener('input', function() {
                    document.getElementById('errorMessage').classList.add('d-none');
                    this.classList.remove('is-invalid');
                });

                // Enter để submit
                confirmInput.addEventListener('keypress', function(e) {
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
    </script>
@endsection