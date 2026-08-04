<!doctype html>
<html lang="vi" data-theme="light">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký khóa học — Lộ trình HSK 3 từ con số 0 — Hányǔ Bàn</title>
    <meta
      name="description"
      content="Hoàn tất đăng ký và thanh toán khóa học tiếng Trung."
    />

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&family=Noto+Sans+SC:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link href="/asset/css/style.css" rel="stylesheet" />

    <style>
      /* ============ Scoped styles for the checkout / enrollment screen ============ */
      body {
        padding-top: 78px;
      }
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

      .coupon-box {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
      }
      .coupon-box input {
        flex: 1;
      }
      .coupon-applied {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: color-mix(in srgb, var(--success) 10%, var(--card));
        border: 1px solid color-mix(in srgb, var(--success) 35%, transparent);
        border-radius: 12px;
        padding: 0.6rem 0.9rem;
        font-size: 0.85rem;
        margin-bottom: 1.25rem;
      }
      .coupon-applied .remove-coupon {
        border: none;
        background: none;
        color: var(--danger);
        font-weight: 700;
        font-size: 0.78rem;
        cursor: pointer;
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
      .pm-option {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 1rem 1.1rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.15s ease;
      }
      .pm-option:hover {
        border-color: var(--primary);
      }
      .pm-option.selected {
        border-color: var(--primary);
        background: color-mix(in srgb, var(--primary) 6%, var(--card));
      }
      .pm-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
      }
      .pm-radio {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 1.5px solid var(--border);
        flex-shrink: 0;
        position: relative;
        transition: all 0.15s ease;
      }
      .pm-option.selected .pm-radio {
        border-color: var(--primary);
      }
      .pm-option.selected .pm-radio::after {
        content: "";
        position: absolute;
        inset: 3px;
        border-radius: 50%;
        background: var(--primary);
      }
      .pm-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--bg);
        border: 1px solid var(--border);
        font-weight: 800;
        font-size: 0.75rem;
      }
      .pm-name {
        font-weight: 700;
        font-size: 0.92rem;
      }
      .pm-desc {
        font-size: 0.76rem;
        color: var(--text-muted);
      }

      /* QR panel (shown when QR method selected) */
      .qr-panel {
        display: none;
        text-align: center;
        background: var(--bg);
        border: 1px dashed var(--border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 0.5rem;
      }
      .qr-panel.show {
        display: block;
      }
      .qr-code-box {
        width: 170px;
        height: 170px;
        margin: 0 auto 1rem;
        border-radius: 14px;
        background: #fff;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
      }
      .qr-timer {
        font-family: var(--font-head);
        font-weight: 800;
        color: var(--primary);
        font-size: 1.1rem;
        margin-bottom: 0.3rem;
      }

      /* card form (shown for Stripe) */
      .card-form {
        display: none;
        margin-top: 0.5rem;
      }
      .card-form.show {
        display: block;
      }

      /* voucher / terms */
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

      /* ============ SUCCESS SCREEN ============ */
      .success-wrap {
        display: none;
        text-align: center;
        padding: 3rem 1rem;
      }
      .success-wrap.show {
        display: block;
      }
      .checkout-wrap.hide {
        display: none;
      }
      .success-seal {
        width: 96px;
        height: 96px;
        border-radius: 22px;
        background: var(--success);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-family: var(--font-zh);
        font-weight: 700;
        font-size: 2.4rem;
        box-shadow: 0 14px 32px -14px rgba(34, 197, 94, 0.55);
        transform: rotate(-4deg);
      }
      .success-order-code {
        display: inline-block;
        background: var(--bg);
        border: 1px dashed var(--border);
        border-radius: 999px;
        padding: 0.4rem 1.1rem;
        font-family: var(--font-head);
        font-weight: 700;
        font-size: 0.9rem;
        margin: 1rem 0 1.5rem;
      }
    </style>
  </head>
  <body>
    <a href="#checkoutMain" class="skip-link">Bỏ qua đến nội dung chính</a>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top py-3">
      <div class="container">
        <a
          class="navbar-brand d-flex align-items-center gap-2"
          href="index.html"
        >
          <span class="brand-mark zh">汉</span>
          <span class="font-head fw-bold fs-5">Hányǔ Bàn</span>
        </a>
        <div class="d-flex align-items-center gap-2">
          <button
            class="theme-toggle"
            id="themeToggle"
            aria-label="Chuyển chế độ sáng/tối"
            type="button"
          >
            <svg
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
            </svg>
          </button>
          <span class="small" style="color: var(--text-muted)"
            >🔒 Thanh toán an toàn</span
          >
        </div>
      </div>
    </nav>

    <main id="checkoutMain" class="container checkout-page-pad">
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
              <h2>Chọn phương thức thanh toán</h2>

              <label class="pm-option selected" data-method="vnpay">
                <input type="radio" name="pm" checked />
                <span class="pm-radio"></span>
                <span class="pm-icon" style="color: #0047ab">VNPay</span>
                <span class="flex-fill">
                  <span class="pm-name d-block">VNPay</span>
                  <span class="pm-desc"
                    >Quét mã QR hoặc thẻ ATM nội địa / Visa / Master</span
                  >
                </span>
              </label>

              <label class="pm-option" data-method="momo">
                <input type="radio" name="pm" />
                <span class="pm-radio"></span>
                <span class="pm-icon" style="color: #a50064">MoMo</span>
                <span class="flex-fill">
                  <span class="pm-name d-block">Ví MoMo</span>
                  <span class="pm-desc"
                    >Thanh toán qua ứng dụng ví điện tử MoMo</span
                  >
                </span>
              </label>

              <label class="pm-option" data-method="stripe">
                <input type="radio" name="pm" />
                <span class="pm-radio"></span>
                <span class="pm-icon" style="color: #635bff">Stripe</span>
                <span class="flex-fill">
                  <span class="pm-name d-block">Thẻ quốc tế (Stripe)</span>
                  <span class="pm-desc"
                    >Visa, Mastercard, JCB phát hành quốc tế</span
                  >
                </span>
              </label>

              <!-- QR panel (VNPay / MoMo) -->
              <div class="qr-panel show" id="qrPanel">
                <div class="qr-code-box">
                  <svg width="150" height="150" viewBox="0 0 150 150">
                    <rect width="150" height="150" fill="#fff" />
                    <g fill="#111827">
                      <rect x="10" y="10" width="30" height="30" />
                      <rect x="18" y="18" width="14" height="14" fill="#fff" />
                      <rect x="22" y="22" width="6" height="6" />
                      <rect x="110" y="10" width="30" height="30" />
                      <rect x="118" y="18" width="14" height="14" fill="#fff" />
                      <rect x="122" y="22" width="6" height="6" />
                      <rect x="10" y="110" width="30" height="30" />
                      <rect x="18" y="118" width="14" height="14" fill="#fff" />
                      <rect x="22" y="122" width="6" height="6" />
                      <rect x="50" y="15" width="6" height="6" />
                      <rect x="65" y="15" width="6" height="6" />
                      <rect x="80" y="20" width="6" height="6" />
                      <rect x="95" y="15" width="6" height="6" />
                      <rect x="55" y="35" width="6" height="6" />
                      <rect x="70" y="40" width="6" height="6" />
                      <rect x="90" y="35" width="6" height="6" />
                      <rect x="50" y="55" width="6" height="6" />
                      <rect x="65" y="60" width="10" height="10" />
                      <rect x="85" y="55" width="6" height="6" />
                      <rect x="100" y="60" width="6" height="6" />
                      <rect x="50" y="75" width="6" height="6" />
                      <rect x="60" y="80" width="6" height="6" />
                      <rect x="80" y="75" width="10" height="10" />
                      <rect x="100" y="80" width="6" height="6" />
                      <rect x="50" y="95" width="6" height="6" />
                      <rect x="70" y="95" width="6" height="6" />
                      <rect x="90" y="100" width="6" height="6" />
                      <rect x="50" y="115" width="6" height="6" />
                      <rect x="65" y="120" width="6" height="6" />
                      <rect x="80" y="115" width="6" height="6" />
                      <rect x="95" y="120" width="20" height="6" />
                      <rect x="120" y="110" width="6" height="6" />
                      <rect x="120" y="125" width="6" height="6" />
                    </g>
                  </svg>
                </div>
                <div class="qr-timer" id="qrTimer">04:59</div>
                <p class="small mb-0" style="color: var(--text-muted)">
                  Mở app VNPay / ngân hàng, quét mã để hoàn tất thanh toán.
                  Trang sẽ tự cập nhật khi thành công.
                </p>
              </div>

              <!-- Card form (Stripe) -->
              <div class="card-form" id="cardForm">
                <div class="mb-3">
                  <label class="form-label small fw-semibold">Số thẻ</label>
                  <input
                    type="text"
                    class="form-control form-control-brand"
                    placeholder="1234 5678 9012 3456"
                  />
                </div>
                <div class="row g-2">
                  <div class="col-6">
                    <label class="form-label small fw-semibold"
                      >Ngày hết hạn</label
                    >
                    <input
                      type="text"
                      class="form-control form-control-brand"
                      placeholder="MM/YY"
                    />
                  </div>
                  <div class="col-6">
                    <label class="form-label small fw-semibold">CVC</label>
                    <input
                      type="text"
                      class="form-control form-control-brand"
                      placeholder="123"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="terms-box">
              <input
                type="checkbox"
                id="agreeCheckout"
                class="form-check-input mt-1"
                checked
              />
              <label for="agreeCheckout"
                >Tôi đồng ý với
                <a href="#" class="link-brand">Điều khoản dịch vụ</a> và xác
                nhận thông tin đăng ký khóa học là chính xác.</label
              >
            </div>
          </div>

          <!-- Right: order summary -->
          <div class="col-lg-5">
            <div class="order-summary">
              <div class="order-course">
                <div class="order-thumb zh">HSK</div>
                <div>
                  <div class="order-title">Lộ trình HSK 3 từ con số 0</div>
                  <div class="order-meta">45 bài giảng · Thầy Vương Kiệt</div>
                </div>
              </div>

              <div class="order-row">
                <span>Giá gốc</span><strong>1.590.000đ</strong>
              </div>
              <div class="order-row">
                <span>Giảm giá ưu đãi</span
                ><strong style="color: var(--success)">−340.000đ</strong>
              </div>

              <div id="couponAppliedBox" class="coupon-applied d-none">
                <span
                  >🎟️ Mã <strong id="appliedCouponCode">HOCTHU10</strong> đã áp
                  dụng</span
                >
                <button class="remove-coupon" id="removeCouponBtn">Xóa</button>
              </div>
              <div class="coupon-box" id="couponBox">
                <input
                  type="text"
                  class="form-control form-control-brand"
                  id="couponInput"
                  placeholder="Nhập mã giảm giá"
                />
                <button
                  class="btn-outline-brand"
                  id="applyCouponBtn"
                  style="white-space: nowrap"
                >
                  Áp dụng
                </button>
              </div>

              <div class="order-total-row">
                <span class="label">Tổng thanh toán</span>
                <span class="amount"
                  ><span class="order-old-price d-none" id="oldPriceTag"
                    >1.590.000đ</span
                  ><span id="finalPrice">1.250.000đ</span></span
                >
              </div>

              <button class="btn-confirm-pay mt-4" id="confirmPayBtn">
                <span id="confirmPayText">Xác nhận thanh toán</span>
                <span
                  id="confirmPaySpinner"
                  class="spinner-border spinner-border-sm ms-2 d-none"
                ></span>
              </button>

              <div class="guarantee-box">
                <svg
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <span
                  >Hoàn tiền 100% trong 7 ngày đầu nếu bạn không hài lòng với
                  khóa học.</span
                >
              </div>
            </div>
          </div>
        </div>

        <div class="trust-badges">
          <span class="trust-badge"
            ><svg
              width="15"
              height="15"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <rect x="3" y="11" width="18" height="11" rx="2" />
              <path d="M7 11V7a5 5 0 0110 0v4" />
            </svg>
            Thanh toán bảo mật SSL</span
          >
          <span class="trust-badge"
            ><svg
              width="15"
              height="15"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path d="M9 12l2 2 4-4" />
              <circle cx="12" cy="12" r="10" />
            </svg>
            Truy cập trọn đời</span
          >
          <span class="trust-badge"
            ><svg
              width="15"
              height="15"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"
              />
            </svg>
            Được 12.000+ học viên tin dùng</span
          >
        </div>
      </div>

      <!-- ============ SUCCESS SCREEN ============ -->
      <div class="success-wrap" id="successWrap">
        <div class="success-seal zh">好</div>
        <h1 class="font-head fw-bold fs-3 mb-2">
          Đăng ký khóa học thành công!
        </h1>
        <p style="color: var(--text-muted); max-width: 460px; margin: 0 auto">
          Bạn đã đăng ký khóa <strong>Lộ trình HSK 3 từ con số 0</strong>. Email
          xác nhận và hóa đơn đã được gửi tới hộp thư của bạn.
        </p>
        <div class="success-order-code">
          Mã đơn hàng: <span id="orderCode">HB-284917</span>
        </div>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="dashboard.html" class="btn-brand">Vào học ngay</a>
          <a href="my-courses.html" class="btn-outline-brand"
            >Xem khóa học của tôi</a
          >
        </div>
      </div>
    </main>

    <div
      class="toast-container position-fixed bottom-0 end-0 p-3"
      style="z-index: 2000"
    >
      <div
        id="ajaxToast"
        class="toast align-items-center border-0 text-bg-danger"
        role="status"
        aria-live="polite"
        aria-atomic="true"
      >
        <div class="d-flex">
          <div class="toast-body" id="ajaxToastBody">Có lỗi xảy ra.</div>
          <button
            type="button"
            class="btn-close btn-close-white me-2 m-auto"
            data-bs-dismiss="toast"
            aria-label="Đóng"
          ></button>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        /* ---------- Theme ---------- */
        const root = document.documentElement;
        function lsGet(k) {
          try {
            return localStorage.getItem(k);
          } catch (e) {
            return null;
          }
        }
        function lsSet(k, v) {
          try {
            localStorage.setItem(k, v);
          } catch (e) {}
        }
        root.setAttribute("data-theme", lsGet("hb-theme") || "light");
        document
          .getElementById("themeToggle")
          .addEventListener("click", function () {
            const next =
              root.getAttribute("data-theme") === "light" ? "dark" : "light";
            root.setAttribute("data-theme", next);
            lsSet("hb-theme", next);
          });

        /* ---------- Payment method switch ---------- */
        const basePrice = 1250000;
        let currentDiscount = 0;
        const qrPanel = document.getElementById("qrPanel");
        const cardForm = document.getElementById("cardForm");

        document.querySelectorAll(".pm-option").forEach(function (opt) {
          opt.addEventListener("click", function () {
            document
              .querySelectorAll(".pm-option")
              .forEach((o) => o.classList.remove("selected"));
            opt.classList.add("selected");
            opt.querySelector("input").checked = true;
            const method = opt.dataset.method;
            qrPanel.classList.toggle(
              "show",
              method === "vnpay" || method === "momo",
            );
            cardForm.classList.toggle("show", method === "stripe");
          });
        });

        /* ---------- QR countdown (demo) ---------- */
        let qrSeconds = 5 * 60 - 1;
        const qrTimerEl = document.getElementById("qrTimer");
        setInterval(function () {
          if (qrSeconds <= 0) return;
          qrSeconds--;
          const m = Math.floor(qrSeconds / 60)
            .toString()
            .padStart(2, "0");
          const s = (qrSeconds % 60).toString().padStart(2, "0");
          qrTimerEl.textContent = m + ":" + s;
        }, 1000);

        /* ---------- Coupon apply (Ajax demo) ---------- */
        const couponInput = document.getElementById("couponInput");
        const applyCouponBtn = document.getElementById("applyCouponBtn");
        const couponBox = document.getElementById("couponBox");
        const couponAppliedBox = document.getElementById("couponAppliedBox");
        const finalPriceEl = document.getElementById("finalPrice");
        const oldPriceTag = document.getElementById("oldPriceTag");

        function formatVND(n) {
          return n.toLocaleString("vi-VN") + "đ";
        }

        applyCouponBtn.addEventListener("click", function () {
          const code = couponInput.value.trim().toUpperCase();
          if (!code) return;
          applyCouponBtn.disabled = true;
          applyCouponBtn.textContent = "Đang kiểm tra...";

          fetch("https://jsonplaceholder.typicode.com/posts/1")
            .then(function (res) {
              if (!res.ok) throw new Error("failed");
              return res.json();
            })
            .then(function () {
              // Demo: mọi mã hợp lệ đều giảm thêm 10%
              currentDiscount = Math.round(basePrice * 0.1);
              const newPrice = basePrice - currentDiscount;
              oldPriceTag.textContent = formatVND(basePrice);
              oldPriceTag.classList.remove("d-none");
              finalPriceEl.textContent = formatVND(newPrice);
              document.getElementById("appliedCouponCode").textContent = code;
              couponAppliedBox.classList.remove("d-none");
              couponBox.classList.add("d-none");
            })
            .finally(function () {
              applyCouponBtn.disabled = false;
              applyCouponBtn.textContent = "Áp dụng";
            });
        });

        document
          .getElementById("removeCouponBtn")
          .addEventListener("click", function () {
            currentDiscount = 0;
            finalPriceEl.textContent = formatVND(basePrice);
            oldPriceTag.classList.add("d-none");
            couponAppliedBox.classList.add("d-none");
            couponBox.classList.remove("d-none");
            couponInput.value = "";
          });

        /* ---------- Confirm payment (Ajax) ---------- */
        const confirmBtn = document.getElementById("confirmPayBtn");
        const confirmText = document.getElementById("confirmPayText");
        const confirmSpinner = document.getElementById("confirmPaySpinner");
        const toastEl = document.getElementById("ajaxToast");
        const toastBody = document.getElementById("ajaxToastBody");

        confirmBtn.addEventListener("click", function () {
          if (!document.getElementById("agreeCheckout").checked) {
            toastBody.textContent =
              "Vui lòng đồng ý với Điều khoản dịch vụ trước khi thanh toán.";
            new bootstrap.Toast(toastEl, { delay: 3000 }).show();
            return;
          }
          const selectedMethod = document.querySelector(".pm-option.selected")
            .dataset.method;

          confirmBtn.disabled = true;
          confirmText.textContent = "Đang xử lý thanh toán...";
          confirmSpinner.classList.remove("d-none");

          // AJAX: gửi yêu cầu tạo đơn hàng / khởi tạo thanh toán (demo endpoint)
          fetch("https://jsonplaceholder.typicode.com/posts", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              course: "hsk3-tu-con-so-0",
              method: selectedMethod,
              amount: basePrice - currentDiscount,
            }),
          })
            .then(function (res) {
              if (!res.ok) throw new Error("failed");
              return res.json();
            })
            .then(function () {
              document.getElementById("checkoutWrap").classList.add("hide");
              document.getElementById("successWrap").classList.add("show");
              document
                .querySelectorAll(".checkout-step")[1]
                .classList.remove("active");
              document
                .querySelectorAll(".checkout-step")[1]
                .classList.add("done");
              document
                .querySelectorAll(".checkout-step")[1]
                .querySelector(".num").textContent = "✓";
              document
                .querySelectorAll(".checkout-step")[2]
                .classList.add("active");
              document
                .querySelectorAll(".checkout-step")[2]
                .querySelector(".num").textContent = "✓";
              window.scrollTo({ top: 0, behavior: "smooth" });
            })
            .catch(function () {
              toastBody.textContent = "Thanh toán thất bại, vui lòng thử lại.";
              new bootstrap.Toast(toastEl, { delay: 3000 }).show();
            })
            .finally(function () {
              confirmBtn.disabled = false;
              confirmText.textContent = "Xác nhận thanh toán";
              confirmSpinner.classList.add("d-none");
            });
        });
      });
    </script>
  </body>
</html>
