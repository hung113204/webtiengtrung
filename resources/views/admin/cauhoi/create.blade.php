@extends('admin.layouts.main')

@section('title', 'Thêm câu hỏi mới — Hányǔ Admin')

@section('content')
<style>
  .option-row {
    background: #f8fafc;
    border: 1px solid var(--admin-border);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.2s;
  }
  .option-row:hover {
    border-color: #cbd5e1;
  }
  .option-row.is-correct {
    background: #f0fdf4;
    border-color: #86efac;
  }
</style>

<nav aria-label="breadcrumb" class="mb-3 animate-fade-in delay-1">
<ol class="breadcrumb small mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.khoahoc.index') ?? '#' }}" class="text-decoration-none text-muted">Đào tạo</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.cauhoi.index') ?? '#' }}" class="text-decoration-none text-muted">Ngân hàng đề thi</a></li>
    <li class="breadcrumb-item active" aria-current="page">Thêm câu hỏi mới</li>
</ol>
</nav>

<form action="{{ route('admin.cauhoi.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="page-header animate-fade-in delay-1">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Thêm câu hỏi mới</h1>
        <p class="text-muted mb-0 small">Thiết kế nội dung câu hỏi tiếng Trung với các đáp án.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.cauhoi.index') ?? '#' }}" class="btn btn-light border px-4">Hủy</a>
        <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">Lưu câu hỏi</button>
    </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @include('admin.cauhoi._form')
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const answersContainer = document.getElementById('answers-container');
        const addAnswerBtn = document.getElementById('add-answer-btn');

        // Hàm cập nhật lại các chữ cái (A, B, C, D...) cho các đáp án
        function updateAnswerLabels() {
            const rows = answersContainer.querySelectorAll('.option-row');
            rows.forEach((row, index) => {
                const letter = String.fromCharCode(65 + index); // 65 = 'A'
                
                // Cập nhật radio
                const radio = row.querySelector('input[type="radio"]');
                if (radio) {
                    radio.value = letter;
                    radio.id = 'ans' + letter;
                }
                
                // Cập nhật text label
                const label = row.querySelector('.fw-bold.d-flex.align-items-center');
                if (label) {
                    label.textContent = 'Đáp án ' + letter;
                }
                
                // Cập nhật input name cho nội dung
                const inputDapAn = row.querySelector('input[name^="dap_an["]');
                if (inputDapAn) {
                    inputDapAn.name = `dap_an[${letter}]`;
                }

                // Cập nhật input name cho pinyin
                const inputPinyin = row.querySelector('input[name^="dap_an_pinyin["]');
                if (inputPinyin) {
                    inputPinyin.name = `dap_an_pinyin[${letter}]`;
                }
            });
        }

        // Xử lý thêm đáp án
        addAnswerBtn.addEventListener('click', function() {
            const rowsCount = answersContainer.querySelectorAll('.option-row').length;
            if (rowsCount >= 10) {
                alert('Tối đa 10 đáp án!');
                return;
            }
            const nextLetter = String.fromCharCode(65 + rowsCount); // 65 = 'A'

            const newRow = document.createElement('div');
            newRow.className = 'option-row mb-2'; // Thêm mb-2 để giãn cách
            newRow.innerHTML = `
                <div class="d-flex gap-3 align-items-start">
                <div class="pt-2">
                    <input class="form-check-input" type="radio" name="dap_an_dung" value="${nextLetter}" id="ans${nextLetter}" style="transform: scale(1.2);">
                </div>
                <div class="flex-grow-1 row g-2">
                    <div class="col-sm-2 fw-bold d-flex align-items-center">Đáp án ${nextLetter}</div>
                    <div class="col-sm-5">
                    <input type="text" class="form-control form-control-sm" name="dap_an[${nextLetter}]" placeholder="Nội dung (VD: 学校)">
                    </div>
                    <div class="col-sm-5">
                    <input type="text" class="form-control form-control-sm text-muted" name="dap_an_pinyin[${nextLetter}]" placeholder="Pinyin (VD: xuéxiào)">
                    </div>
                </div>
                <button type="button" class="btn text-danger p-1 delete-answer-btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg></button>
                </div>
            `;
            answersContainer.appendChild(newRow);
        });

        // Sử dụng event delegation cho các sự kiện động (Xóa và Chọn Radio)
        answersContainer.addEventListener('click', function(e) {
            // Xử lý nút xóa
            if (e.target.closest('.delete-answer-btn') || e.target.closest('.btn.text-danger.p-1')) {
                const rows = answersContainer.querySelectorAll('.option-row');
                if (rows.length <= 2) {
                    alert('Phải có ít nhất 2 đáp án!');
                    return;
                }
                const row = e.target.closest('.option-row');
                
                // Nếu đáp án bị xóa đang được check đúng, check mặc định vào đáp án đầu tiên
                const radio = row.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    const firstRadio = answersContainer.querySelector('.option-row input[type="radio"]');
                    if (firstRadio) {
                        firstRadio.checked = true;
                        firstRadio.closest('.option-row').classList.add('is-correct');
                    }
                }
                
                row.remove();
                updateAnswerLabels(); // Đánh lại thứ tự A, B, C...
            }
        });

        // Xử lý đổi màu radio
        answersContainer.addEventListener('change', function(e) {
            if (e.target.name === 'dap_an_dung') {
                document.querySelectorAll('.option-row').forEach(row => {
                    row.classList.remove('is-correct');
                });
                if (e.target.checked) {
                    e.target.closest('.option-row').classList.add('is-correct');
                }
            }
        });

        // ==========================================
        // LOGIC CHUYỂN ĐỔI LOẠI CÂU HỎI
        // ==========================================
        const loaiSelect = document.getElementById('loai-cau-hoi-select');
        const multiContainer = document.getElementById('multiple-choice-container');
        const singleContainer = document.getElementById('single-answer-container');
        const singleInput = document.getElementById('single-answer-input');
        
        function toggleAnswerUI() {
            const selectedOption = loaiSelect.options[loaiSelect.selectedIndex];
            const slug = selectedOption.getAttribute('data-slug') || '';
            const text = selectedOption.textContent.toLowerCase();
            
            const isSingle = slug.includes('dien') || slug.includes('sap-xep') || text.includes('điền') || text.includes('sắp xếp');
            const isDungSai = slug.includes('dung-sai') || text.includes('đúng / sai');
            const isSapXep = slug.includes('sap-xep') || text.includes('sắp xếp');
            const isDienKhuyet = slug.includes('dien') || text.includes('điền');
            const isNghe = slug.includes('nghe') || text.includes('nghe');
            
            const noiDungLabel = document.getElementById('noi_dung_label');
            const noiDungInput = document.getElementById('noi_dung_input');
            const noiDungHint = document.getElementById('noi_dung_hint');
            
            // Xử lý đổi Label & Placeholder cho ô Câu hỏi tùy theo loại
            if (isSapXep) {
                noiDungLabel.innerHTML = 'Các từ bị xáo trộn (Tiếng Trung) <span class="text-danger">*</span>';
                noiDungInput.placeholder = 'Ví dụ: 学生 / 是 / 我';
                noiDungHint.textContent = 'Nhập các từ bị xáo trộn, có thể ngăn cách nhau bằng dấu gạch chéo (/) hoặc dấu phẩy.';
            } else if (isDienKhuyet) {
                noiDungLabel.innerHTML = 'Câu hỏi điền khuyết (Tiếng Trung) <span class="text-danger">*</span>';
                noiDungInput.placeholder = 'Ví dụ: 你今天去___吗？';
                noiDungHint.textContent = 'Sử dụng dấu gạch dưới (___) để tạo chỗ trống cho học viên điền.';
            } else if (isNghe) {
                noiDungLabel.innerHTML = 'Nội dung câu hỏi (Kèm Audio) <span class="text-danger">*</span>';
                noiDungInput.placeholder = 'Ví dụ: Hãy nghe đoạn âm thanh sau và chọn đáp án đúng nhất.';
                noiDungHint.textContent = 'Nhập dòng lệnh / hướng dẫn làm bài. Bắt buộc phải tải file Âm thanh ở cột bên phải.';
            } else {
                noiDungLabel.innerHTML = 'Câu hỏi (Tiếng Trung) <span class="text-danger">*</span>';
                noiDungInput.placeholder = 'Nhập nội dung câu hỏi...';
                noiDungHint.textContent = 'Nhập nội dung câu hỏi chính.';
            }

            // Xử lý bắt buộc file Âm thanh
            const amThanhLabel = document.getElementById('am_thanh_label');
            const amThanhInput = document.getElementById('am_thanh_input');
            if (isNghe) {
                amThanhLabel.innerHTML = 'Âm thanh (am_thanh) <span class="text-danger">* (Bắt buộc)</span>';
                amThanhInput.required = true;
                amThanhLabel.classList.remove('text-muted');
                amThanhLabel.classList.add('text-danger', 'fw-bold');
            } else {
                amThanhLabel.innerHTML = 'Âm thanh (am_thanh)';
                amThanhInput.required = false;
                amThanhLabel.classList.add('text-muted');
                amThanhLabel.classList.remove('text-danger', 'fw-bold');
            }
            
            if (isSingle) {
                // Màn hình Single Answer (Điền khuyết, Sắp xếp)
                multiContainer.style.display = 'none';
                singleContainer.style.display = 'block';
                
                // Thay đổi Placeholder của ô Đáp án
                if (isSapXep) {
                    singleInput.placeholder = 'Ví dụ: 我是学生。';
                    singleInput.previousElementSibling.innerHTML = 'Câu hoàn chỉnh (Đáp án) <span class="text-danger">*</span>';
                } else {
                    singleInput.placeholder = 'Ví dụ: 学校';
                    singleInput.previousElementSibling.innerHTML = 'Nội dung đáp án <span class="text-danger">*</span>';
                }
                
                // Kích hoạt name cho single, hủy name của multi
                singleInput.name = 'dap_an[A]';
                document.getElementById('single-answer-dung').name = 'dap_an_dung';
                
                // Xóa name của các input A,B,C,D để không bị gửi lên server
                answersContainer.querySelectorAll('input[type="text"]').forEach(input => {
                    input.name = 'dap_an_disabled[]';
                });
                answersContainer.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.name = 'dap_an_dung_disabled';
                });
            } else {
                // Màn hình Multiple Choice (Trắc nghiệm, Nghe chọn, Đúng Sai...)
                multiContainer.style.display = 'block';
                singleContainer.style.display = 'none';
                
                // Kích hoạt lại name cho multi, hủy name của single
                singleInput.name = 'dap_an_single_disabled';
                document.getElementById('single-answer-dung').name = 'dap_an_dung_single_disabled';
                
                // Khôi phục name cho các input A,B,C,D
                answersContainer.querySelectorAll('.option-row').forEach((row, idx) => {
                    const letter = String.fromCharCode(65+idx);
                    const inputs = row.querySelectorAll('input[type="text"]');
                    if (inputs.length >= 1) inputs[0].name = `dap_an[${letter}]`;
                    if (inputs.length >= 2) inputs[1].name = `dap_an_pinyin[${letter}]`;
                });
                answersContainer.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.name = 'dap_an_dung';
                });
                
                // Xử lý riêng giao diện cho câu hỏi Đúng / Sai
                if (isDungSai) {
                    addAnswerBtn.style.display = 'none'; // Ẩn nút thêm đáp án
                    answersContainer.querySelectorAll('.delete-answer-btn').forEach(btn => btn.style.display = 'none'); // Ẩn nút xóa
                    
                    // Xóa các đáp án thừa (C, D...)
                    const rows = answersContainer.querySelectorAll('.option-row');
                    for (let i = 2; i < rows.length; i++) {
                        rows[i].remove();
                    }
                    
                    // Tự động điền chữ Đúng / Sai
                    const inputs = answersContainer.querySelectorAll('input[type="text"]');
                    if (inputs.length >= 1) inputs[0].value = 'Đúng';
                    if (inputs.length >= 2) inputs[1].value = 'Sai';
                    
                } else {
                    addAnswerBtn.style.display = 'flex'; // Hiện lại nút thêm
                    answersContainer.querySelectorAll('.delete-answer-btn').forEach(btn => btn.style.display = 'block'); // Hiện lại nút xóa
                }
            }
        }
        
        loaiSelect.addEventListener('change', toggleAnswerUI);
        // Chạy lần đầu khi load trang
        toggleAnswerUI();
    });
</script>
@endsection
