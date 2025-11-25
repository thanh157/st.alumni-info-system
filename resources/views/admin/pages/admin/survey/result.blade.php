@extends('admin.layouts.master')

@section('title', 'Kết quả khảo sát')

@section('content')
    <div class="container py-4">
        <div class="mb-4 border-bottom pb-3">
            <h4 class="fw-bold text-primary mb-2">Khảo sát: {{ $survey->title }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('admin.survey.index') }}">Khảo sát việc làm</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kết quả khảo sát</li>
                </ol>
            </nav>

            {{-- Main Info Section --}}
            <div class="row g-4">
                {{-- Left Column: Survey Information --}}
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-secondary mb-3">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i>Thông tin khảo sát
                            </h6>

                            <div class="mb-3">
                                <p class="mb-2">
                                    <i class="bi bi-calendar-event text-primary me-2"></i>
                                    <strong>Năm khảo sát:</strong>
                                    <span class="badge bg-primary-subtle text-primary">{{ $schoolYear }}</span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <p class="mb-2">
                                    <i class="bi bi-mortarboard text-primary me-2"></i>
                                    <strong>Đợt khảo sát:</strong>
                                </p>
                                <ul class="list-unstyled ms-4 mb-0">
                                    @foreach ($allDotTotNghiep as $item)
                                        <li class="mb-1">
                                            <i class="bi bi-chevron-right text-muted small me-1"></i>
                                            <a href="{{ route('admin.graduation-student.show', [
                                                'id' => $item['id'],
                                                'name' => base64_encode($item['name']),
                                            ]) }}"
                                                target="_blank" class="text-decoration-none">
                                                {{ $item['name'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @php
                                $totalPhanHoi = App\Models\EmploymentSurveyResponse::where(
                                    'survey_period_id',
                                    $survey->id,
                                )->count();
                                $phanTramPhanHoi =
                                    $survey->total_graduations > 0
                                        ? round(($totalPhanHoi / $survey->total_graduations) * 100, 1)
                                        : 0;
                            @endphp

                            <div class="p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-chat-left-text-fill text-success me-2"></i>
                                        <strong class="text-success">Số lượt phản hồi:</strong>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="mb-0 fw-bold text-success">
                                            {{ $totalPhanHoi }} <small class="text-muted">/
                                                {{ $survey->total_graduations }}</small>
                                        </h5>
                                        <small class="text-muted">
                                            <i class="bi bi-graph-up-arrow"></i> {{ $phanTramPhanHoi }}% đã phản hồi
                                        </small>
                                    </div>
                                </div>
                                {{-- Progress bar --}}
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $phanTramPhanHoi }}%;" aria-valuenow="{{ $phanTramPhanHoi }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                {{-- Thanh thời gian còn lại --}}
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $endTime = \Carbon\Carbon::parse($survey->end_time);
                                    $startTime = \Carbon\Carbon::parse($survey->start_time);
                                    
                                    // Tính tổng thời gian và thời gian đã trôi qua
                                    $totalDuration = $startTime->diffInSeconds($endTime);
                                    $elapsed = $startTime->diffInSeconds($now);
                                    $remaining = $now->diffInSeconds($endTime, false); // false để có giá trị âm nếu quá hạn
                                    
                                    // Tính phần trăm thời gian đã trôi qua
                                    $timeProgress = $totalDuration > 0 ? min(100, round(($elapsed / $totalDuration) * 100, 1)) : 0;
                                    
                                    // Kiểm tra trạng thái
                                    $isExpired = $remaining < 0;
                                    $isNearDeadline = $remaining > 0 && $remaining < 86400 * 3; // 3 ngày
                                    
                                    // Màu thanh progress
                                    $progressColor = $isExpired ? 'danger' : ($isNearDeadline ? 'warning' : 'info');
                                @endphp

                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <i class="bi bi-clock-history text-{{ $progressColor }} me-2"></i>
                                            <strong class="text-{{ $progressColor }}">Thời gian khảo sát:</strong>
                                        </div>
                                        <div class="text-end">
                                            @if($isExpired)
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle-fill me-1"></i>Đã kết thúc
                                                </span>
                                            @elseif($isNearDeadline)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Sắp hết hạn
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    <i class="bi bi-hourglass-split me-1"></i>Đang diễn ra
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            {{ $startTime->format('d/m/Y H:i') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-x me-1"></i>
                                            {{ $endTime->format('d/m/Y H:i') }}
                                        </small>
                                    </div>

                                    {{-- Progress bar thời gian --}}
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $progressColor }}" 
                                            role="progressbar" 
                                            style="width: {{ $timeProgress }}%;" 
                                            aria-valuenow="{{ $timeProgress }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    <div class="text-center mt-2">
                                        @if($isExpired)
                                            <small class="text-danger fw-bold">
                                                <i class="bi bi-exclamation-circle-fill me-1"></i>
                                                Đã quá hạn {{ $now->diffForHumans($endTime, true) }}
                                            </small>
                                        @else
                                            <small class="text-muted">
                                                <i class="bi bi-hourglass-bottom me-1"></i>
                                                Còn lại: <strong class="text-{{ $progressColor }}">{{ $now->diffForHumans($endTime, true) }}</strong>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Statistics Cards --}}
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-secondary mb-0">
                                    <i class="bi bi-bar-chart-fill text-primary me-2"></i>Thống kê tổng quan
                                </h6>
                                {{-- Nút Popover cho Summary Box --}}
                                <i 
                                    class="bi bi-question-circle-fill me-1 text-secondary cursor-pointer" 
                                    id="summary-popover-trigger"
                                    tabindex="0"
                                    role="button">
                                </i>
                            </div>
                            <div class="row g-3">
                                {{-- Card 1: Tỷ lệ SV có việc làm / tổng SV phản hồi --}}
                                @php
                                    $percentCoViec = $totalPhanHoi > 0 ? round(($coViec / $totalPhanHoi) * 100, 2) : 0;
                                @endphp
                                <div class="col-4">
                                    <div class="stat-card p-3 rounded border border-success bg-success bg-opacity-10 h-100">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div class="stat-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-briefcase-fill"></i>
                                            </div>
                                            <span class="badge bg-success">{{ $percentCoViec }}%</span>
                                        </div>
                                        <h4 class="fw-bold text-success mb-1">{{ $coViec }} / {{ $totalPhanHoi }}
                                        </h4>
                                        <p class="text-muted small mb-0">Có việc làm / Phản hồi</p>
                                        <div class="progress mt-2" style="height: 4px;">
                                            <div class="progress-bar bg-success" style="width: {{ $percentCoViec }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 2: Tỷ lệ SV chưa có việc làm / tổng SV phản hồi --}}
                                <div class="col-4">
                                    <div class="stat-card p-3 rounded border border-danger bg-danger bg-opacity-10 h-100">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div class="stat-icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </div>
                                            <span class="badge bg-danger">{{ 100 - $percentCoViec }}%</span>
                                        </div>
                                        <h4 class="fw-bold text-danger mb-1">{{ $totalPhanHoi - $coViec }} /
                                            {{ $totalPhanHoi }}</h4>
                                        <p class="text-muted small mb-0">Chưa có việc làm / Phản hồi</p>
                                        <div class="progress mt-2" style="height: 4px;">
                                            <div class="progress-bar bg-danger"
                                                style="width: {{ 100 - $percentCoViec }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 3: Tỷ lệ SV có việc làm / tổng SV tốt nghiệp --}}
                                @php
                                    $percentEmployment =
                                        $survey->total_graduations > 0
                                            ? round(($coViec / $survey->total_graduations) * 100, 2)
                                            : 0;
                                @endphp
                                <div class="col-4">
                                    <div class="stat-card p-3 rounded border border-primary bg-primary bg-opacity-10 h-100">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div class="stat-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-mortarboard-fill fs-5"></i>
                                            </div>
                                            <span class="badge bg-primary">{{ $percentEmployment }}%</span>
                                        </div>
                                        <h4 class="fw-bold text-primary mb-1">{{ $coViec }} /
                                            {{ $survey->total_graduations }}</h4>
                                        <p class="text-muted small mb-0">Có việc làm / Tốt nghiệp</p>
                                        <div class="progress mt-2" style="height: 4px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $percentEmployment }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 4: Tỷ lệ SV có việc làm phù hợp / tổng SV phản hồi --}}
                                @php
                                    $coViecPhuHop = $dungNganh + $lienQuan + $diHoc;
                                    $tyLeViecPhuHop =
                                        $totalPhanHoi > 0 ? round(($coViecPhuHop / $totalPhanHoi) * 100, 2) : 0;
                                    $coViecPhuHopTrenTotNghiep = $coViecPhuHop + intval(($survey->total_graduations - $totalPhanHoi) / 2);
                                    $tyLeViecPhuHopTrenTotNghiep =
                                        $survey->total_graduations > 0
                                            ? round(($coViecPhuHopTrenTotNghiep / $survey->total_graduations) * 100, 2)
                                            : 0;
                                @endphp
                                <div class="col-4">
                                    <div class="stat-card p-3 rounded border border-info bg-info bg-opacity-10 h-100">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div class="stat-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-check-circle-fill fs-5"></i>
                                            </div>
                                            <span class="badge bg-info">{{ $tyLeViecPhuHop }}%</span>
                                        </div>
                                        <h4 class="fw-bold text-info mb-1">{{ $coViecPhuHop }} / {{ $totalPhanHoi }}</h4>
                                        <p class="text-muted small mb-0">Việc làm phù hợp / Phản hồi</p>
                                        <div class="progress mt-2" style="height: 4px;">
                                            <div class="progress-bar bg-info" style="width: {{ $tyLeViecPhuHop }}%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 5: Tỷ lệ SV có việc làm phù hợp / tổng SV tốt nghiệp --}}
                                <div class="col-4">
                                    <div
                                        class="stat-card p-3 rounded border border-warning bg-warning bg-opacity-10 h-100">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div class="stat-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-award-fill fs-5"></i>
                                            </div>
                                            <span class="badge bg-warning">{{ $tyLeViecPhuHopTrenTotNghiep }}%</span>
                                        </div>
                                        <h4 class="fw-bold text-warning mb-1">{{ $coViecPhuHopTrenTotNghiep }} /
                                            {{ $survey->total_graduations }}</h4>
                                        <p class="text-muted small mb-0">Việc làm phù hợp / Tốt nghiệp</p>
                                        <div class="progress mt-2" style="height: 4px;">
                                            <div class="progress-bar bg-warning"
                                                style="width: {{ $tyLeViecPhuHopTrenTotNghiep }}%;"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card 6: Tỷ lệ SV có việc làm ĐÚNG NGÀNH / tổng SV phản hồi --}}
                                @php
                                    $tyLeDungNganh = $totalPhanHoi > 0 ? round(($dungNganh / $totalPhanHoi) * 100, 2) : 0;
                                @endphp
                                <div class="col-4"> 
                                    <div
                                        class="stat-card p-3 rounded border border-secondary bg-secondary bg-opacity-10 h-100">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div class="stat-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-patch-check-fill fs-5"></i>
                                            </div>
                                            <span class="badge bg-secondary">{{ $tyLeDungNganh }}%</span>
                                        </div>
                                        <h4 class="fw-bold text-secondary mb-1">{{ $dungNganh }} /
                                            {{ $totalPhanHoi }}</h4>
                                        <p class="text-muted small mb-0">Đúng ngành / Phản hồi</p>
                                        <div class="progress mt-2" style="height: 4px;">
                                            <div class="progress-bar bg-secondary"
                                                style="width: {{ $tyLeDungNganh }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hidden Content for Popover --}}
            <div id="popover-content-stats" class="d-none">
                <div class="small text-muted">
                    <div class="mb-2 d-flex align-items-start">
                        <i class="bi bi-info-circle text-primary me-2 mt-1"></i>
                        <div>
                            <strong>SV có việc làm:</strong><br>
                            = Đúng ngành + Tiếp tục học
                        </div>
                    </div>
                    <div class="mb-2 d-flex align-items-start">
                        <i class="bi bi-check2-circle text-success me-2 mt-1"></i>
                        <div>
                            <strong>Việc làm phù hợp (Trên phản hồi):</strong><br>
                            = Đúng ngành + Liên quan
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-calculator text-warning me-2 mt-1"></i>
                        <div>
                            <strong>Việc làm phù hợp (Trên tổng SV):</strong><br>
                            = Đúng ngành + Liên quan + Tiếp tục học + (Tổng SV khảo sát - Tổng phản hồi)/2
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Actions --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
            <form action="" method="GET" class="d-flex flex-grow-1 flex-md-grow-0 w-100"
                style="max-width: 500px;">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Tìm theo mã sinh viên hoặc họ tên">
                    <button type="submit" class="btn btn-outline-primary px-4">
                        <i class="bi bi-search me-1"></i>Tìm kiếm
                    </button>
                </div>
            </form>

            {{-- THÊM MỚI: Bộ lọc trạng thái việc làm --}}
            <div class="d-flex gap-2 align-items-center">
                <form action="" method="GET" class="d-flex gap-2">
                    {{-- Giữ lại search query nếu có --}}
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <select name="employment_status" class="form-select form-select-sm"
                        style="width: auto; min-width: 200px;">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1,2" {{ request('employment_status') == '1,2' ? 'selected' : '' }}>
                            Đã có việc làm
                        </option>
                        {{-- <option value="2" {{ request('employment_status') == '2' ? 'selected' : '' }}>
                            Đang tiếp tục học
                        </option> --}}
                        <option value="3,4" {{ request('employment_status') == '3,4' ? 'selected' : '' }}>
                            Chưa có việc làm
                        </option>
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-funnel me-1"></i>Lọc
                    </button>

                    @if (request('employment_status') || request('search'))
                        <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Xóa lọc
                        </a>
                    @endif
                </form>
            </div>

            <div class="d-flex gap-2">
                @if (request()->has('search') || request()->has('employment_status'))
                    <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
                    </a>
                @else
                    <a href="{{ route('admin.survey.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại ds khảo sát
                    </a>
                @endif
                <a href="{{ route('admin.surveys.downloadAllPdfs', $survey->id) }}"
                    class="btn btn-success btn-sm download-link" download>
                    <i class="bi bi-file-earmark-zip-fill me-1 "></i>Tải tất cả PDF (Zip)
                </a>
            </div>
        </div>
        @if (request('employment_status'))
            <div class="mb-3">
                <span class="badge bg-info">
                    <i class="bi bi-funnel-fill me-1"></i>
                    Đang lọc:
                    @switch(request('employment_status'))
                        @case('1')
                            Đã có việc làm
                        @break

                        @case('2')
                            Đang tiếp tục học
                        @break

                        @case('3')
                            Chưa có việc làm
                        @break

                        @case('4')
                            Chưa đi tìm việc
                        @break
                    @endswitch
                    ({{ $data->total() }} kết quả)
                </span>
            </div>
        @endif

        @include('admin.layouts.noti')

        {{-- Data Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-table text-primary me-2"></i>Danh sách phản hồi
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">STT</th>
                            <th style="width: 120px;">Mã SV</th>
                            <th>Email</th>
                            <th>Họ tên</th>
                            <th class="text-center" style="width: 150px;">Ngày phản hồi</th>
                            <th class="text-center" style="width: 200px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">
                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td><strong class="text-primary">{{ $item->code_student }}</strong></td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->full_name }}</td>
                                <td class="text-center">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.contact-survey.show_student_submit', ['id' => $item->id]) }}"
                                            class="btn btn-sm btn-outline-info" title="Xem thông tin SV">
                                            <i class="bi bi-award-fill"></i>
                                        </a>
                                        <a href="{{ route('admin.survey.result_detail', ['id' => $item->id]) }}"
                                            class="btn btn-sm btn-outline-primary" title="Xem chi tiết kết quả">
                                            <i class="bi bi-info-circle-fill"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger  " title="Xuất PDF"
                                            onclick="downloadPdf({{ $item->id }})">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <em>Không có dữ liệu</em>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($data->hasPages())
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-center">
                            {{ $data->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            transition: transform 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .progress {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table> :not(caption)>*>* {
            padding: 0.75rem 0.5rem;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }
    </style>

    <script>
        function downloadPdf(resultId) {
            const link = document.createElement('a');
            link.href = "{{ route('export_pdf_v2', ['resultId' => '__ID__']) }}".replace('__ID__', resultId);
            link.setAttribute('download', '');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Lấy nội dung từ div ẩn
            var popoverContent = document.getElementById('popover-content-stats').innerHTML;
            
            var triggerElement = document.getElementById('summary-popover-trigger');
            
            if(triggerElement){
                new bootstrap.Popover(triggerElement, {
                    html: true,
                    content: popoverContent,
                    placement: 'bottom',
                    trigger: 'hover focus', // Dùng hover focus cho desktop
                    title: '<span class="fw-bold text-success">Cách tính chỉ số</span>' // Tiêu đề popover
                });
            }
        });
    </script>
@endsection
