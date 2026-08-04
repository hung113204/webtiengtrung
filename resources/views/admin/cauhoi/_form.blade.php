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

@php
    // Prepare DapAn Data for dynamic rendering (supports both old() validation errors and edit mode)
    $dapAnData = old('dap_an');
    $dapAnPinyinData = old('dap_an_pinyin');
    $dapAnDungData = old('dap_an_dung');
    
    // If no old data but we have $cauHoi (Edit mode)
    if (empty($dapAnData) && isset($cauHoi)) {
        $dapAnData = [];
        $dapAnPinyinData = [];
        $char = 'A';
        foreach($cauHoi->dapAns as $da) {
            $dapAnData[$char] = $da->noi_dung;
            $dapAnPinyinData[$char] = $da->pinyin;
            if ($da->dung) $dapAnDungData = $char;
            $char++;
        }
    }
    
    // Default to A, B, C if still empty (Create mode)
    if (empty($dapAnData)) {
        $dapAnData = ['A' => '', 'B' => '', 'C' => ''];
        $dapAnPinyinData = ['A' => '', 'B' => '', 'C' => ''];
        $dapAnDungData = 'A';
    }

    // Single Answer (for Fill in the blank / Ordering)
    $singleAnswer = old('dap_an_single');
    if (empty($singleAnswer) && isset($cauHoi) && in_array($cauHoi->loaiCauHoi->slug ?? '', ['dien-khuyet', 'sap-xep'])) {
        $singleAnswer = $cauHoi->dapAns->first()->noi_dung ?? '';
    }
@endphp

<div class="row g-4 mb-5">
    <!-- Cột trái: Nội dung câu hỏi -->
    <div class="col-lg-8 animate-fade-in delay-2">
        
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px;">
            <h2 class="fs-5 fw-bold mb-4 pb-3 border-bottom">1. Nội dung câu hỏi</h2>
            
            <div class="mb-4">
                <label class="form-label fw-medium" id="noi_dung_label">Câu hỏi (Tiếng Trung) <span class="text-danger">*</span></label>
                <textarea class="form-control" name="noi_dung" id="noi_dung_input" rows="3" placeholder="Ví dụ: 你今天去___吗？" required>{{ old('noi_dung', $cauHoi->noi_dung ?? '') }}</textarea>
                <div class="form-text" id="noi_dung_hint">Có thể chứa khoảng trống (___) để làm câu hỏi điền khuyết.</div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Pinyin (Tùy chọn)</label>
                    <input type="text" class="form-control" name="pinyin" placeholder="Nǐ jīn tiān qù ___ ma?" value="{{ old('pinyin', $cauHoi->pinyin ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Dịch nghĩa Tiếng Việt</label>
                    <input type="text" class="form-control" name="dich_nghia" placeholder="Hôm nay bạn có đi ___ không?" value="{{ old('dich_nghia', $cauHoi->dich_nghia ?? '') }}">
                </div>
            </div>
        </div>

        <!-- MÀN HÌNH 1: TRẮC NGHIỆM -->
        <div id="multiple-choice-container">
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                    <h2 class="fs-5 fw-bold mb-0 border-0 pb-0">2. Các đáp án</h2>
                    <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" id="add-answer-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Thêm đáp án
                    </button>
                </div>
                
                <p class="text-muted small mb-3">Tích chọn vào nút tròn bên trái để đánh dấu đáp án đúng nhất.</p>

                <div id="answers-container">
                    @foreach($dapAnData as $letter => $content)
                    <div class="option-row {{ $dapAnDungData === $letter ? 'is-correct' : '' }} mb-2">
                        <div class="d-flex gap-3 align-items-start">
                            <div class="pt-2">
                                <input class="form-check-input" type="radio" name="dap_an_dung" value="{{ $letter }}" id="ans{{ $letter }}" {{ $dapAnDungData === $letter ? 'checked' : '' }} style="transform: scale(1.2);">
                            </div>
                            <div class="flex-grow-1 row g-2">
                                <div class="col-sm-2 fw-bold d-flex align-items-center">Đáp án {{ $letter }}</div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control form-control-sm" name="dap_an[{{ $letter }}]" placeholder="Nội dung (VD: 学校)" value="{{ $content }}">
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control form-control-sm text-muted" name="dap_an_pinyin[{{ $letter }}]" placeholder="Pinyin (VD: xuéxiào)" value="{{ $dapAnPinyinData[$letter] ?? '' }}">
                                </div>
                            </div>
                            <button type="button" class="btn text-danger p-1 delete-answer-btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg></button>
                        </div>
                    </div>
                    @endforeach
                </div> <!-- end #answers-container -->

            </div>
        </div> <!-- end #multiple-choice-container -->

        <!-- MÀN HÌNH 2: ĐIỀN KHUYẾT / SẮP XẾP CÂU -->
        <div id="single-answer-container" style="display: none;">
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                    <h2 class="fs-5 fw-bold mb-0 border-0 pb-0">2. Đáp án chính xác</h2>
                </div>
                <p class="text-muted small mb-3">Nhập nội dung đáp án đúng duy nhất. Học viên cần gõ chính xác đoạn này.</p>
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Nội dung đáp án <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="dap_an_single" id="single-answer-input" placeholder="VD: 学校" value="{{ $singleAnswer }}">
                    <!-- Các input ẩn để lừa backend lưu đúng chuẩn -->
                    <input type="hidden" name="dap_an_dung_single" value="A" id="single-answer-dung">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4" style="border-radius: 12px;">
            <h2 class="fs-5 fw-bold mb-3">3. Giải thích đáp án</h2>
            <div class="form-text mb-2">Lời giải này sẽ hiển thị khi học viên trả lời sai hoặc xem lại bài thi.</div>
            <textarea class="form-control mb-3" name="giai_thich" rows="3" placeholder="Nhập lời giải chi tiết tại đây... (Ví dụ: Chỗ trống cần một danh từ chỉ địa điểm)">{{ old('giai_thich', $cauHoi->giai_thich ?? '') }}</textarea>
            
            <label class="form-label small text-muted mb-1">File nghe giải thích (am_thanh_giai_thich)</label>
            @if(isset($cauHoi) && $cauHoi->am_thanh_giai_thich)
                <div class="mb-2">
                    <audio controls style="height: 30px; max-width: 250px;">
                        <source src="{{ asset('storage/' . $cauHoi->am_thanh_giai_thich) }}" type="audio/mpeg">
                    </audio>
                    <div class="small text-muted mt-1">Đã tải lên file hiện tại. Tải file mới để ghi đè.</div>
                </div>
            @endif
            <input type="file" name="am_thanh_giai_thich" class="form-control form-control-sm" accept="audio/mpeg, audio/wav">
            <div class="form-text small">Tùy chọn tải lên đoạn âm thanh đọc/giải thích đáp án.</div>
        </div>

    </div>

    <!-- Cột phải: Cài đặt câu hỏi -->
    <div class="col-lg-4 animate-fade-in delay-3">
        
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px;">
            <h2 class="fs-5 fw-bold mb-4 border-bottom pb-3">Thiết lập chung</h2>
            
            <div class="mb-3">
                <label class="form-label fw-medium">Loại câu hỏi</label>
                <select class="form-select" name="id_loai_cau_hoi" id="loai-cau-hoi-select" required>
                    <option value="" data-slug="">-- Chọn loại câu hỏi --</option>
                    @foreach($loaiCauHois as $loai)
                        <option value="{{ $loai->id }}" data-slug="{{ $loai->slug }}" {{ old('id_loai_cau_hoi', $cauHoi->id_loai_cau_hoi ?? '') == $loai->id ? 'selected' : '' }}>{{ $loai->ten_loai }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Mức độ</label>
                <select class="form-select" name="id_muc_do" required>
                    <option value="">-- Chọn mức độ --</option>
                    @foreach($mucDos as $mucDo)
                        <option value="{{ $mucDo->id }}" {{ old('id_muc_do', $cauHoi->id_muc_do ?? '') == $mucDo->id ? 'selected' : '' }}>{{ $mucDo->ten_muc_do }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-dark fw-medium">Thuộc Khóa học</label>
                <select class="form-select border-primary-subtle" name="id_khoa_hoc">
                    <option value="">-- Chọn khóa học --</option>
                    @if(isset($khoaHocs))
                        @foreach($khoaHocs as $kh)
                            <option value="{{ $kh->id }}" {{ old('id_khoa_hoc', $cauHoi->id_khoa_hoc ?? '') == $kh->id ? 'selected' : '' }}>{{ $kh->ten_khoa_hoc }}</option>
                        @endforeach
                    @else
                        <option value="1">Khóa học mẫu</option>
                    @endif
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-dark fw-medium">Thuộc Bài học / Đề thi</label>
                <select class="form-select border-primary-subtle" name="id_bai_hoc" required>
                    <option value="">-- Chọn bài học --</option>
                    @if(isset($baiHocs))
                        @foreach($baiHocs as $bh)
                            <option value="{{ $bh->id }}" {{ old('id_bai_hoc', $cauHoi->id_bai_hoc ?? '') == $bh->id ? 'selected' : '' }}>{{ $bh->ten_bai_hoc }}</option>
                        @endforeach
                    @else
                        <option value="1">Bài học mẫu</option>
                    @endif
                </select>
            </div>
            
            <hr class="my-4 text-muted">
            
            <h6 class="fw-semibold mb-3">Tệp đính kèm (Media)</h6>
            
            <div class="mb-3">
                <label class="form-label small text-muted mb-1">Hình ảnh (hinh_anh)</label>
                @if(isset($cauHoi) && $cauHoi->hinh_anh)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $cauHoi->hinh_anh) }}" alt="Hình ảnh" style="max-width: 100%; max-height: 150px; border-radius: 8px;">
                    </div>
                @endif
                <input type="file" name="hinh_anh" class="form-control form-control-sm" accept="image/png, image/jpeg">
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted mb-1" id="am_thanh_label">Âm thanh (am_thanh)</label>
                @if(isset($cauHoi) && $cauHoi->am_thanh)
                    <div class="mb-2">
                        <audio controls style="height: 30px; max-width: 100%;">
                            <source src="{{ asset('storage/' . $cauHoi->am_thanh) }}" type="audio/mpeg">
                        </audio>
                    </div>
                @endif
                <input type="file" name="am_thanh" id="am_thanh_input" class="form-control form-control-sm" accept="audio/mpeg, audio/wav">
            </div>

            <div class="mb-2">
                <label class="form-label small text-muted mb-1">Video (video)</label>
                @if(isset($cauHoi) && $cauHoi->video)
                    <div class="mb-2">
                        <video controls style="max-width: 100%; max-height: 150px; border-radius: 8px;">
                            <source src="{{ asset('storage/' . $cauHoi->video) }}" type="video/mp4">
                        </video>
                    </div>
                @endif
                <input type="file" name="video" class="form-control form-control-sm" accept="video/mp4">
            </div>
        </div>

    </div>
</div>

@push('scripts')
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

        // Xử lý nút Thêm đáp án
        addAnswerBtn.addEventListener('click', function() {
            const rowCount = answersContainer.querySelectorAll('.option-row').length;
            if (rowCount >= 6) {
                alert('Tối đa 6 đáp án (A-F)!');
                return;
            }

            const letter = String.fromCharCode(65 + rowCount);
            const newRow = document.createElement('div');
            newRow.className = 'option-row mb-2';
            newRow.innerHTML = `
                <div class="d-flex gap-3 align-items-start">
                    <div class="pt-2">
                        <input class="form-check-input" type="radio" name="dap_an_dung" value="${letter}" id="ans${letter}" style="transform: scale(1.2);">
                    </div>
                    <div class="flex-grow-1 row g-2">
                        <div class="col-sm-2 fw-bold d-flex align-items-center">Đáp án ${letter}</div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control form-control-sm" name="dap_an[${letter}]" placeholder="Nội dung">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control form-control-sm text-muted" name="dap_an_pinyin[${letter}]" placeholder="Pinyin (Tùy chọn)">
                        </div>
                    </div>
                    <button type="button" class="btn text-danger p-1 delete-answer-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                    </button>
                </div>
            `;
            answersContainer.appendChild(newRow);
            updateAnswerLabels();
        });

        // Xử lý Xóa đáp án
        answersContainer.addEventListener('click', function(e) {
            const btn = e.target.closest('.delete-answer-btn');
            if (btn) {
                const row = btn.closest('.option-row');
                // Không cho xóa nếu chỉ còn 2 đáp án
                if (answersContainer.querySelectorAll('.option-row').length <= 2) {
                    alert('Cần ít nhất 2 đáp án!');
                    return;
                }
                
                // Nếu xóa đúng cái đang được check, check cái đầu tiên
                const radio = row.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    const firstRadio = answersContainer.querySelector('input[type="radio"]');
                    if (firstRadio && firstRadio !== radio) {
                        firstRadio.checked = true;
                        firstRadio.closest('.option-row').classList.add('is-correct');
                    } else {
                        const secondRadio = answersContainer.querySelectorAll('input[type="radio"]')[1];
                        if (secondRadio) {
                            secondRadio.checked = true;
                            secondRadio.closest('.option-row').classList.add('is-correct');
                        }
                    }
                }

                row.remove();
                updateAnswerLabels();
            }
        });

        // Xử lý style khi chọn radio (is-correct)
        answersContainer.addEventListener('change', function(e) {
            if (e.target.type === 'radio' && e.target.name === 'dap_an_dung') {
                document.querySelectorAll('.option-row').forEach(row => row.classList.remove('is-correct'));
                e.target.closest('.option-row').classList.add('is-correct');
            }
        });

        // LOGIC CHUYỂN ĐỔI GIAO DIỆN THEO LOẠI CÂU HỎI
        const loaiSelect = document.getElementById('loai-cau-hoi-select');
        const multipleChoiceContainer = document.getElementById('multiple-choice-container');
        const singleAnswerContainer = document.getElementById('single-answer-container');
        
        // Các elements thay đổi text
        const noiDungLabel = document.getElementById('noi_dung_label');
        const noiDungInput = document.getElementById('noi_dung_input');
        const noiDungHint = document.getElementById('noi_dung_hint');
        const amThanhLabel = document.getElementById('am_thanh_label');
        const amThanhInput = document.getElementById('am_thanh_input');

        function toggleAnswerUI() {
            const selectedOption = loaiSelect.options[loaiSelect.selectedIndex];
            const slug = selectedOption.getAttribute('data-slug');

            // Reset labels to default
            noiDungLabel.innerHTML = 'Câu hỏi (Tiếng Trung) <span class="text-danger">*</span>';
            noiDungInput.placeholder = 'Ví dụ: 你今天去___吗？';
            noiDungHint.textContent = 'Có thể chứa khoảng trống (___) để làm câu hỏi điền khuyết.';
            amThanhLabel.innerHTML = 'Âm thanh (am_thanh)';
            amThanhInput.required = false;

            // Xử lý các loại
            if (slug === 'dien-vao-cho-trong') {
                // ĐIỀN KHUYẾT
                multipleChoiceContainer.style.display = 'none';
                singleAnswerContainer.style.display = 'block';
                // Đặt required cho input 1 đáp án
                document.getElementById('single-answer-input').required = true;
                // Vô hiệu hóa inputs nhiều đáp án để không gửi lên server
                const multiInputs = multipleChoiceContainer.querySelectorAll('input');
                multiInputs.forEach(i => i.disabled = true);
                // Bật lại input single
                const singleInputs = singleAnswerContainer.querySelectorAll('input');
                singleInputs.forEach(i => i.disabled = false);
            } 
            else if (slug === 'sap-xep-cau') {
                // SẮP XẾP CÂU
                multipleChoiceContainer.style.display = 'none';
                singleAnswerContainer.style.display = 'block';
                
                noiDungLabel.innerHTML = 'Từ vựng xáo trộn <span class="text-danger">*</span>';
                noiDungInput.placeholder = 'Nhập các từ cách nhau bởi dấu gạch chéo. VD: 的 / 他 / 很大 / 苹果';
                noiDungHint.textContent = 'Nhập các từ hoặc cụm từ bị xáo trộn để học viên sắp xếp lại.';

                document.getElementById('single-answer-input').required = true;
                const multiInputs = multipleChoiceContainer.querySelectorAll('input');
                multiInputs.forEach(i => i.disabled = true);
                const singleInputs = singleAnswerContainer.querySelectorAll('input');
                singleInputs.forEach(i => i.disabled = false);
            }
            else if (slug === 'nghe-va-chon-dap-an') {
                // NGHE HIỂU
                multipleChoiceContainer.style.display = 'block';
                singleAnswerContainer.style.display = 'none';

                noiDungLabel.innerHTML = 'Nội dung câu hỏi';
                noiDungInput.placeholder = '(Tùy chọn) Nhập yêu cầu, ví dụ: Nghe audio và chọn đáp án đúng.';
                noiDungInput.required = false;

                amThanhLabel.innerHTML = 'Âm thanh bài nghe <span class="text-danger">*</span>';
                @if(!isset($cauHoi) || empty($cauHoi->am_thanh))
                    amThanhInput.required = true;
                @endif

                document.getElementById('single-answer-input').required = false;
                const singleInputs = singleAnswerContainer.querySelectorAll('input');
                singleInputs.forEach(i => i.disabled = true);
                const multiInputs = multipleChoiceContainer.querySelectorAll('input');
                multiInputs.forEach(i => i.disabled = false);
            }
            else {
                // TRẮC NGHIỆM VÀ CÁC LOẠI KHÁC (Mặc định)
                multipleChoiceContainer.style.display = 'block';
                singleAnswerContainer.style.display = 'none';

                noiDungInput.required = true;

                document.getElementById('single-answer-input').required = false;
                const singleInputs = singleAnswerContainer.querySelectorAll('input');
                singleInputs.forEach(i => i.disabled = true);
                const multiInputs = multipleChoiceContainer.querySelectorAll('input');
                multiInputs.forEach(i => i.disabled = false);
            }
        }

        loaiSelect.addEventListener('change', toggleAnswerUI);
        
        // Khởi chạy khi load trang (quan trọng khi edit hoặc quay lại do lỗi validate)
        toggleAnswerUI();
    });
</script>
@endpush
