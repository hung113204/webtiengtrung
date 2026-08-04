@extends('frontend.layouts.dashboard')

@section('title', 'Chào mừng — Hányǔ Bàn')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-lg-8">
        <div class="brand-card p-5 text-center shadow-lg rounded-4 border-0">
            <div class="mb-4">
                <div class="icon-wrap bg-soft-primary text-primary mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <h1 class="font-head fw-bold fs-3">Chào mừng bạn đến với Hányǔ Bàn! 🎉</h1>
                <p class="text-muted mt-2">Để mang lại trải nghiệm học tập tốt nhất, hệ thống Trí Tuệ Nhân Tạo (AI) của chúng tôi cần biết một chút thông tin về trình độ và mục tiêu của bạn.</p>
            </div>

            <form action="{{ route('frontend.dashboard.onboarding.save') }}" method="POST" class="text-start mt-4">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Trình độ tiếng Trung hiện tại của bạn?</label>
                        <select name="level" class="form-select form-select-lg rounded-3" required>
                            <option value="">-- Chọn trình độ --</option>
                            <option value="Chưa biết gì (Người mới bắt đầu)">Chưa biết gì (Người mới bắt đầu)</option>
                            <option value="Tương đương HSK 1">Tương đương HSK 1</option>
                            <option value="Tương đương HSK 2">Tương đương HSK 2</option>
                            <option value="Tương đương HSK 3">Tương đương HSK 3</option>
                            <option value="Tương đương HSK 4">Tương đương HSK 4</option>
                            <option value="Tương đương HSK 5">Tương đương HSK 5</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mục tiêu học tập chính của bạn là gì?</label>
                        <select name="goal" class="form-select form-select-lg rounded-3" required>
                            <option value="">-- Chọn mục tiêu --</option>
                            <option value="Giao tiếp cơ bản hàng ngày">Giao tiếp cơ bản hàng ngày</option>
                            <option value="Thi lấy chứng chỉ HSK">Thi lấy chứng chỉ HSK</option>
                            <option value="Phục vụ công việc, thương mại">Phục vụ công việc, thương mại</option>
                            <option value="Đi du học Trung Quốc/Đài Loan">Đi du học Trung Quốc/Đài Loan</option>
                            <option value="Xem phim, nghe nhạc không cần Vietsub">Xem phim, nghe nhạc giải trí</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 d-inline-flex align-items-center gap-2 fw-bold shadow-sm">
                        <span>Xác nhận & Nhận lộ trình AI</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                    <p class="small text-muted mt-3">Hệ thống sẽ tự động thiết kế lộ trình học cá nhân hóa cho bạn ngay sau bước này.</p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
