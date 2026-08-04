@extends('frontend.layouts.main')

@section('title', 'Thanh toán khóa học — Hányǔ Bàn')

@push('styles')
<style>
/* ============ Scoped styles for the checkout / enrollment screen ============ */
.checkout-page-pad {
  padding-top: 2.5rem;
  padding-bottom: 4rem;
}

.checkout-steps {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}
.checkout-step {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-muted);
}
.checkout-step .num {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  border: 1.5px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.78rem;
  font-weight: 700;
  flex-shrink: 0;
}
.checkout-step.active {
  color: var(--text);
}
.checkout-step.active .num {
  border-color: var(--primary);
  background: var(--primary);
  color: #fff;
}
.checkout-step.done .num {
  border-color: var(--success);
  background: var(--success);
  color: #fff;
}
.checkout-step-sep {
  width: 32px;
  height: 1.5px;
  background: var(--border);
  flex-shrink: 0;
}

/* course summary card */
.order-summary {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
  position: sticky;
  top: 96px;
}
.order-course {
  display: flex;
  gap: 1rem;
  padding-bottom: 1.25rem;
  border-bottom: 1px solid var(--border);
  margin-bottom: 1.25rem;
}
.order-thumb {
  width: 76px;
  height: 76px;
  border-radius: 14px;
  flex-shrink: 0;
  background: linear-gradient(135deg, var(--primary), #f97316);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-family: var(--font-zh);
  font-weight: 700;
  font-size: 1.6rem;
  object-fit: cover;
}
.order-title {
  font-family: var(--font-head);
  font-weight: 700;
  font-size: 0.95rem;
  line-height: 1.35;
  margin-bottom: 0.3rem;
}
.order-meta {
  font-size: 0.78rem;
  color: var(--text-muted);
}
.order-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.88rem;
  margin-bottom: 0.6rem;
  color: var(--text-muted);
}
.order-row strong {
  color: var(--text);
  font-weight: 700;
}
.order-total-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  margin-top: 0.5rem;
  border-top: 1px solid var(--border);
}
.order-total-row .label {
  font-weight: 700;
}
.order-total-row .amount {
  font-family: var(--font-head);
  font-weight: 800;
  font-size: 1.4rem;
  color: var(--primary);
}
.order-old-price {
  text-decoration: line-through;
  color: var(--text-muted);
  font-size: 0.8rem;
  margin-right: 0.4rem;
}

.guarantee-box {
  display: flex;
  gap: 0.7rem;
  align-items: flex-start;
  font-size: 0.78rem;
  color: var(--text-muted);
  margin-top: 1.25rem;
}
.guarantee-box svg {
  flex-shrink: 0;
  color: var(--success);
  margin-top: 0.1rem;
}

/* payment methods */
.payment-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
  margin-bottom: 1.25rem;
}
.payment-card h2 {
  font-family: var(--font-head);
  font-weight: 700;
  font-size: 1.05rem;
  margin-bottom: 1rem;
}

.qr-panel {
  text-align: center;
  background: var(--bg);
  border: 1px dashed var(--border);
  border-radius: 16px;
  padding: 1.5rem;
  margin-top: 0.5rem;
}

.qr-code-box {
  width: auto;
  margin: 0 auto 1rem;
  border-radius: 14px;
  background: #fff;
  border: 1px solid var(--border);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px;
}

.qr-code-box img {
    max-width: 250px;
    height: auto;
}

.terms-box {
  display: flex;
  align-items: flex-start;
  gap: 0.6rem;
  font-size: 0.85rem;
  color: var(--text-muted);
  margin: 1rem 0 1.5rem;
}

.btn-confirm-pay {
  width: 100%;
  background: var(--primary);
  border: none;
  color: #fff;
  font-weight: 700;
  border-radius: 999px;
  padding: 0.9rem 1.4rem;
  font-size: 1rem;
  box-shadow: 0 10px 24px -10px rgba(220, 38, 38, 0.55);
}
.btn-confirm-pay:hover {
  background: var(--primary-dark);
  color: #fff;
}
.btn-confirm-pay:disabled {
  opacity: 0.6;
}

.trust-badges {
  display: flex;
  gap: 1.25rem;
  flex-wrap: wrap;
  margin-top: 1.5rem;
  justify-content: center;
}
.trust-badge {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.76rem;
  color: var(--text-muted);
}
.trust-badge svg {
  color: var(--success);
}
</style>
@endpush

@section('content')
<main id="checkoutMain" class="container checkout-page-pad" style="margin-top: 40px;">
    <!-- Step indicator -->
    <div class="checkout-steps">
        <div class="checkout-step done">
            <span class="num">✓</span> Chọn khóa học
        </div>
        <div class="checkout-step-sep"></div>
        <div class="checkout-step active">
            <span class="num">2</span> Thanh toán
        </div>
        <div class="checkout-step-sep"></div>
        <div class="checkout-step"><span class="num">3</span> Hoàn tất</div>
    </div>

    <!-- ============ CHECKOUT FORM ============ -->
    <div class="checkout-wrap" id="checkoutWrap">
        <div class="row g-4">
            <!-- Left: payment methods -->
            <div class="col-lg-7">
                <div class="payment-card">
                    <h2>Thông tin chuyển khoản</h2>
                    
                    <p class="text-muted small mb-4">Vui lòng chuyển khoản đúng số tiền và nội dung chuyển khoản như hướng dẫn dưới đây để hệ thống tự động duyệt đăng ký khóa học cho bạn.</p>

                    <!-- QR panel (VNPay / MoMo) -->
                    <div class="qr-panel show" id="qrPanel">
                        @php
                            // Ngân hàng VietinBank (Mã BIN 970415)
                            $bankBin = '970415'; // VietinBank
                            $bankAccount = '108877335903'; // Thay bằng STK VietinBank của bạn
                            $accountName = 'HANYU BAN'; // Tên chủ thẻ của bạn
                            $amount = $hoaDon->so_tien;
                            $description = $hoaDon->ma_hoa_don;
                            
                            $qrUrl = "https://img.vietqr.io/image/{$bankBin}-{$bankAccount}-compact2.png?amount={$amount}&addInfo={$description}&accountName=" . urlencode($accountName);
                        @endphp
                        
                        <div class="qr-code-box">
                            <img src="{{ $qrUrl }}" alt="Mã QR Chuyển Khoản">
                        </div>
                        
                        <div class="alert alert-info text-start mt-3 mb-0">
                            <div class="mb-2"><strong>Ngân hàng:</strong> VietinBank</div>
                            <div class="mb-2"><strong>Chủ tài khoản:</strong> {{ $accountName }}</div>
                            <div class="mb-2"><strong>Số tài khoản:</strong> <span class="fw-bold text-primary">{{ $bankAccount }}</span></div>
                            <div class="mb-2"><strong>Số tiền:</strong> <span class="fw-bold text-danger">{{ number_format($hoaDon->so_tien, 0, ',', '.') }}đ</span></div>
                            <div class="mb-0"><strong>Nội dung CK:</strong> <span class="fw-bold fs-5 text-primary">{{ $hoaDon->ma_hoa_don }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="terms-box">
                    <input type="checkbox" id="agreeCheckout" class="form-check-input mt-1" checked />
                    <label for="agreeCheckout">
                        Tôi đã đọc và đồng ý với <a href="#" class="link-brand">Điều khoản dịch vụ</a>.
                    </label>
                </div>
                
                <form action="{{ route('khoahoc.processPayment') }}" method="POST" id="vnpayForm">
                    @csrf
                    <input type="hidden" name="ma_hoa_don" value="{{ $hoaDon->ma_hoa_don }}">
                    <input type="hidden" name="payment_method" value="vnpay">
                    <button type="submit" class="btn-confirm-pay mt-4 mb-2" id="btn-vnpay">
                        Thanh toán tự động qua VNPay
                    </button>
                </form>

                <form action="{{ route('khoahoc.processPayment') }}" method="POST" id="manualForm">
                    @csrf
                    <input type="hidden" name="ma_hoa_don" value="{{ $hoaDon->ma_hoa_don }}">
                    <input type="hidden" name="payment_method" value="manual">
                    <button type="submit" class="btn w-100" style="padding: 0.9rem 1.4rem; border-radius: 999px; font-weight: 700; background: var(--bg); border: 2px solid var(--border); color: var(--text);" id="btn-manual">
                        Tự quét mã QR (Chờ Admin duyệt)
                    </button>
                </form>
            </div>

            <!-- Right: order summary -->
            <div class="col-lg-5">
                <div class="order-summary">
                    <div class="order-course">
                        @if($khoaHoc->anh_bia)
                            <img src="{{ asset('storage/' . $khoaHoc->anh_bia) }}" alt="Thumb" class="order-thumb">
                        @else
                            <div class="order-thumb zh">HSK</div>
                        @endif
                        <div>
                            <div class="order-title">{{ $khoaHoc->ten_khoa_hoc }}</div>
                            <div class="order-meta">Khóa học Online</div>
                        </div>
                    </div>

                    <div class="order-row">
                        <span>Giá gốc</span>
                        <strong>
                            {{ number_format($khoaHoc->gia ?? $hoaDon->so_tien, 0, ',', '.') }}đ
                        </strong>
                    </div>

                    @if($khoaHoc->gia_giam && $khoaHoc->gia_giam < $khoaHoc->gia)
                        <div class="order-row">
                            <span>Giảm giá</span>
                            <strong style="color: var(--success)">
                                −{{ number_format($khoaHoc->gia - $khoaHoc->gia_giam, 0, ',', '.') }}đ
                            </strong>
                        </div>
                    @endif

                    <div class="order-total-row">
                        <span class="label">Tổng thanh toán</span>
                        <span class="amount">
                            <span id="finalPrice">{{ number_format($hoaDon->so_tien, 0, ',', '.') }}đ</span>
                        </span>
                    </div>

                    <div class="guarantee-box">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <span>Hoàn tiền 100% trong 7 ngày đầu nếu bạn không hài lòng với khóa học.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="trust-badges">
            <span class="trust-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0110 0v4" /></svg>
                Thanh toán an toàn
            </span>
            <span class="trust-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4" /><circle cx="12" cy="12" r="10" /></svg>
                Truy cập trọn đời
            </span>
            <span class="trust-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                Được 12.000+ học viên tin dùng
            </span>
        </div>
    </div>
</main>
@endsection
