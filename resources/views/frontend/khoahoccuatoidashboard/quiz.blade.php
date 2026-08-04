@extends('frontend.layouts.learning')

@section('title', 'Bài kiểm tra: ' . $baiHoc->ten_bai_hoc)

@push('styles')
<style>
    :root {
        --bg-quiz: #f8f9fa;
        --card-bg: #ffffff;
        --quiz-primary: #0d6efd;
    }
    
    body {
        background-color: var(--bg-quiz);
    }

    .quiz-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 15px;
    }

    .quiz-card {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .quiz-header {
        background: var(--quiz-primary);
        color: white;
        padding: 20px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .quiz-body {
        padding: 40px;
    }

    /* Quiz Styles */
    .tq-progress { font-weight: 600; color: var(--quiz-primary); margin-bottom: 1rem; }
    .tq-question { font-size: 1.3rem; font-weight: 600; margin-bottom: 2rem; color: var(--bs-heading-color, #212529); line-height: 1.5;}
    .tq-option { padding: 1.2rem; border: 2px solid var(--bs-border-color); border-radius: 12px; margin-bottom: 1rem; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; font-size: 1.1rem;}
    .tq-option:hover { border-color: var(--quiz-primary); background: rgba(13, 110, 253, 0.05); transform: translateY(-2px); }
    .tq-option.correct { border-color: var(--bs-success); background: rgba(25, 135, 84, 0.1); }
    .tq-option.wrong { border-color: var(--bs-danger); background: rgba(220, 53, 69, 0.1); }
    .tq-letter { width: 40px; height: 40px; border-radius: 50%; background: rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; margin-right: 1.2rem; font-weight: 600; font-size: 1rem; flex-shrink: 0;}
    .tq-feedback { margin-top: 2rem; padding: 1.5rem; border-radius: 12px; font-weight: 500; display: none; font-size: 1.1rem; border: 1px solid transparent;}
    .tq-feedback.show { display: block; animation: fadeIn 0.3s; }
    
    .tq-feedback.success-feedback { background: rgba(25, 135, 84, 0.1); border-color: rgba(25, 135, 84, 0.2); color: var(--bs-success); }
    .tq-feedback.error-feedback { background: rgba(220, 53, 69, 0.1); border-color: rgba(220, 53, 69, 0.2); color: var(--bs-danger); }

    .tq-result { text-align: center; padding: 3rem 0; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .word-chip {
        font-size: 1.1rem;
        padding: 0.5rem 1.2rem;
        margin: 0.25rem;
        border-radius: 50px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="quiz-container">
    <div class="mb-3 d-flex align-items-center">
        <a href="{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}" class="text-decoration-none text-secondary d-flex align-items-center">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Quay lại bài học
        </a>
    </div>

    <div class="quiz-card">
        <div class="quiz-header">
            <div>
                <h4 class="mb-1 fw-bold text-white">{{ $baiHoc->ten_bai_hoc }}</h4>
                <div class="small opacity-75">{{ $khoaHoc->ten_khoa_hoc }}</div>
            </div>
            <div>
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
        </div>
        <div class="quiz-body">
            <div id="quizActive">
                <div class="tq-progress">Câu <span id="tqCurrent">1</span> / {{ count($baiHoc->cauHois) }}</div>
                <div class="progress mb-4" style="height: 6px;">
                    <div id="tqProgressBar" class="progress-bar bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="tq-question" id="tqQuestion"></div>
                <div id="tqOptions"></div>
                <div class="tq-feedback" id="tqFeedback"></div>
            </div>
            <div class="tq-result d-none" id="quizResult">
                <div class="mb-4 text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <h2 class="fw-bold mb-3" style="color: var(--bs-heading-color);">Hoàn thành bài kiểm tra!</h2>
                <div class="fs-4 mb-4 text-muted">Điểm số: <span id="tqScore" class="fw-bold text-primary">0</span> / {{ count($baiHoc->cauHois) }}</div>
                
                @if($baiHoc->loai_dieu_kien === 'kiem_tra')
                <div id="quizCompletionMsg" class="alert alert-success d-inline-block px-4 py-3 mb-4 fs-5">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div> Hệ thống đang ghi nhận kết quả...
                </div>
                @endif
                
                <div class="mt-4 pt-3 border-top">
                    <button class="btn btn-outline-primary btn-lg px-4" onclick="resetQuiz()">Làm lại</button>
                    @if($nextLesson)
                    <a href="{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $nextLesson->slug]) }}" class="btn-brand btn-lg ms-3 px-4 d-inline-flex align-items-center text-decoration-none">
                        Bài tiếp theo <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ms-2"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                    </a>
                    @else
                    <a href="{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}" class="btn-brand btn-lg ms-3 px-4 d-inline-flex align-items-center text-decoration-none">
                        Về bài học <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ms-2"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const examQuestions = {!! json_encode($baiHoc->cauHois->map(function($q) {
        return [
            'id' => $q->id,
            'noi_dung' => $q->noi_dung,
            'pinyin' => $q->pinyin,
            'dich_nghia' => $q->dich_nghia,
            'giai_thich' => $q->giai_thich,
            'am_thanh' => $q->am_thanh,
            'dapAns' => $q->dapAns->map(function($a) {
                return [
                    'id' => $a->id,
                    'noi_dung' => $a->noi_dung,
                    'pinyin' => $a->pinyin,
                    'dung' => $a->dung,
                ];
            })->values()->toArray()
        ];
    })) !!};

    let qIndex = 0, score = 0;
    const totalQuestions = examQuestions.length;
    const updateProgressUrl = "{{ route('frontend.dashboard.khoahoc.progress', $baiHoc->id) }}";
    const csrfToken = "{{ csrf_token() }}";
    const isKiemTra = "{{ $baiHoc->loai_dieu_kien }}" === "kiem_tra";
    let daGuiHoanThanh = {{ $tienDo->da_hoan_thanh ? 'true' : 'false' }};

    function updateProgressBar() {
        const percentage = (qIndex / totalQuestions) * 100;
        document.getElementById('tqProgressBar').style.width = percentage + '%';
    }

    function renderQuestion() {
        updateProgressBar();
        
        if (qIndex >= totalQuestions) {
            document.getElementById('quizActive').classList.add('d-none');
            document.getElementById('quizResult').classList.remove('d-none');
            document.getElementById('tqScore').textContent = score;
            
            // Tự động hoàn thành bài học nếu điều kiện là làm bài kiểm tra
            if (isKiemTra && !daGuiHoanThanh) {
                daGuiHoanThanh = true;
                fetch(updateProgressUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ da_hoan_thanh: 1 })
                }).then(response => response.json())
                  .then(data => {
                      window.location.href = "{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}";
                  }).catch(error => {
                      console.error('Error updating progress:', error);
                      const msgEl = document.getElementById('quizCompletionMsg');
                      if (msgEl) {
                          msgEl.classList.replace('alert-success', 'alert-danger');
                          msgEl.innerHTML = 'Có lỗi xảy ra khi lưu kết quả. Vui lòng thử lại.';
                      }
                  });
            } else {
                window.location.href = "{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}";
            }
            return;
        }
        
        const item = examQuestions[qIndex];
        document.getElementById('tqCurrent').textContent = qIndex + 1;
        
        let qText = item.noi_dung;
        if(item.pinyin) qText += `<br><small style="color:var(--text-muted); font-weight:normal; font-size: 1rem;">${item.pinyin}</small>`;
        if(item.dich_nghia) qText += `<br><small style="color:var(--text-muted); font-weight:normal; font-size: 1rem;">${item.dich_nghia}</small>`;
        
        if (item.am_thanh) {
            const audioUrl = `{{ asset('storage') }}/${item.am_thanh}`;
            qText += `
            <div class="mt-4 mb-2">
                <audio controls style="max-width: 100%; height: 45px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <source src="${audioUrl}" type="audio/mpeg">
                    Trình duyệt không hỗ trợ audio.
                </audio>
            </div>`;
        }
        
        document.getElementById('tqQuestion').innerHTML = qText;
        
        const optWrap = document.getElementById('tqOptions');
        optWrap.innerHTML = '';
        const feedbackEl = document.getElementById('tqFeedback');
        feedbackEl.className = 'tq-feedback'; // Reset classes
        feedbackEl.innerHTML = ''; 

        const options = item.dapAns;
        
        if (options.length === 1) {
            if (item.noi_dung.includes('/')) {
                const words = item.noi_dung.split('/').map(w => w.trim()).filter(w => w !== '');
                
                const sortWrap = document.createElement('div');
                sortWrap.className = 'mt-4';
                sortWrap.innerHTML = `
                    <div id="tqAnswerBox" class="p-3 mb-4 border rounded d-flex flex-wrap gap-2" style="background:#f1f3f5; min-height: 70px; border-color: #dee2e6 !important;"></div>
                    <div id="tqWordBank" class="d-flex flex-wrap gap-2 mb-4 justify-content-center"></div>
                    <div class="text-end">
                        <button class="btn-brand px-5 py-2 fs-5" id="tqSubmitAnswer" style="border:none; cursor:pointer; border-radius: 8px;">Kiểm tra đáp án</button>
                    </div>
                `;
                optWrap.appendChild(sortWrap);
                
                const answerBox = document.getElementById('tqAnswerBox');
                const wordBank = document.getElementById('tqWordBank');
                
                words.forEach((word, index) => {
                    const chip = document.createElement('button');
                    chip.className = 'btn btn-outline-secondary word-chip';
                    chip.textContent = word;
                    chip.dataset.word = word;
                    
                    chip.addEventListener('click', function() {
                        if (this.parentElement === wordBank) {
                            answerBox.appendChild(this);
                        } else {
                            wordBank.appendChild(this);
                        }
                    });
                    wordBank.appendChild(chip);
                });
                
                document.getElementById('tqSubmitAnswer').addEventListener('click', function() {
                    const userVal = Array.from(answerBox.children).map(c => c.dataset.word).join('');
                    if (!userVal) return;
                    Array.from(answerBox.children).forEach(c => c.disabled = true);
                    Array.from(wordBank.children).forEach(c => c.disabled = true);
                    answerQuestion(userVal, options, item.giai_thich, true);
                });
                
            } else {
                const inputWrap = document.createElement('div');
                inputWrap.className = 'mt-4 d-flex flex-column gap-3 position-relative';
                inputWrap.innerHTML = `
                    <div class="position-relative">
                        <input type="text" id="tqInputAnswer" class="form-control form-control-lg" placeholder="Nhập câu trả lời (tiếng Trung hoặc Pinyin không dấu)..." autocomplete="off" style="border: 2px solid var(--bs-border-color); border-radius: 12px; padding: 15px 20px;">
                        
                        <!-- Pinyin Suggestion Box (Mini IME) -->
                        <div id="tqPinyinSuggestion" class="d-none shadow-sm rounded bg-white border p-2 position-absolute" style="top: -55px; left: 10px; z-index: 10; animation: fadeIn 0.2s;">
                            <span class="badge bg-light text-muted border me-2">Phím 1-5 / Space</span>
                            <div id="imeCandidates" class="d-inline-flex gap-2"></div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn-brand px-5 py-2 fs-5" id="tqSubmitAnswer" style="border:none; cursor:pointer; border-radius: 8px;">Kiểm tra đáp án</button>
                    </div>
                `;
                optWrap.appendChild(inputWrap);
                
                const inputEl = document.getElementById('tqInputAnswer');
                const suggestionBox = document.getElementById('tqPinyinSuggestion');
                const candidatesContainer = document.getElementById('imeCandidates');
                
                let currentSuggestions = [];
                let composingMatch = null;
                let composingPinyin = '';
                let debounceTimer = null;
                
                function hideSuggestions() {
                    suggestionBox.classList.add('d-none');
                    currentSuggestions = [];
                    composingMatch = null;
                    composingPinyin = '';
                }

                function insertSuggestion(text) {
                    if (!composingMatch) return;
                    const val = inputEl.value;
                    const prefix = val.substring(0, composingMatch.index);
                    const suffix = val.substring(composingMatch.index + composingPinyin.length);
                    
                    inputEl.value = prefix + text + suffix;
                    hideSuggestions();
                    inputEl.focus();
                }

                function renderSuggestions() {
                    if (currentSuggestions.length === 0) {
                        hideSuggestions();
                        return;
                    }
                    let html = '';
                    currentSuggestions.forEach((text, i) => {
                        html += `<span class="ime-candidate" data-index="${i}" style="cursor:pointer; padding: 2px 5px; border-radius: 4px; transition: background 0.1s;">
                                    <small class="text-muted">${i+1}.</small> <span class="fw-bold fs-5 text-primary">${text}</span>
                                 </span>`;
                    });
                    candidatesContainer.innerHTML = html;
                    suggestionBox.classList.remove('d-none');
                    
                    candidatesContainer.querySelectorAll('.ime-candidate').forEach(el => {
                        el.addEventListener('click', function() {
                            const index = this.getAttribute('data-index');
                            insertSuggestion(currentSuggestions[index]);
                        });
                        el.addEventListener('mouseenter', function() {
                            this.style.backgroundColor = 'var(--bs-light)';
                        });
                        el.addEventListener('mouseleave', function() {
                            this.style.backgroundColor = 'transparent';
                        });
                    });
                }
                
                inputEl.addEventListener('input', function(e) {
                    const val = this.value;
                    const match = val.match(/[a-zA-Z']+$/);
                    
                    if (match) {
                        composingMatch = match;
                        composingPinyin = match[0];
                        
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            fetch(`https://inputtools.google.com/request?text=${composingPinyin}&itc=zh-t-i0-pinyin&num=5&cp=0&cs=1&ie=utf-8&oe=utf-8`)
                                .then(res => res.json())
                                .then(data => {
                                    if (data[0] === 'SUCCESS' && data[1] && data[1][0] && data[1][0][1]) {
                                        currentSuggestions = data[1][0][1];
                                        renderSuggestions();
                                    }
                                }).catch(err => console.error(err));
                        }, 200);
                    } else {
                        hideSuggestions();
                    }
                });
                
                inputEl.addEventListener('keydown', function(e) {
                    if (!suggestionBox.classList.contains('d-none') && currentSuggestions.length > 0) {
                        if (['1', '2', '3', '4', '5'].includes(e.key)) {
                            e.preventDefault();
                            const index = parseInt(e.key) - 1;
                            if (currentSuggestions[index]) {
                                insertSuggestion(currentSuggestions[index]);
                            }
                            return;
                        } 
                        else if (e.key === ' ') {
                            e.preventDefault();
                            insertSuggestion(currentSuggestions[0]);
                            return;
                        }
                        else if (e.key === 'Enter') {
                            e.preventDefault();
                            hideSuggestions();
                            return; // Enter just keeps the raw pinyin in the box
                        }
                    }
                    
                    if (e.key === 'Enter' && suggestionBox.classList.contains('d-none')) {
                        document.getElementById('tqSubmitAnswer').click();
                    }
                });

                document.getElementById('tqSubmitAnswer').addEventListener('click', function() {
                    const userVal = document.getElementById('tqInputAnswer').value.trim();
                    if (!userVal) return;
                    answerQuestion(userVal, options, item.giai_thich, true, item.pinyin);
                });
                
                setTimeout(() => document.getElementById('tqInputAnswer').focus(), 100);
            }
        } else {
            options.forEach(function(opt, idx) {
                const div = document.createElement('div');
                div.className = 'tq-option';
                
                let optText = opt.noi_dung;
                if(opt.pinyin) optText += ` <span style="color:var(--text-muted); font-size: 0.9em; margin-left: 8px;">(${opt.pinyin})</span>`;
                
                let letterHtml = `<span class="tq-letter" style="color:var(--bs-heading-color)">${String.fromCharCode(65 + idx)}</span>`;
                
                div.innerHTML = `${letterHtml}<span style="color:var(--bs-heading-color)">${optText}</span>`;
                div.dataset.idx = idx;
                div.addEventListener('click', function() {
                    answerQuestion(idx, options, item.giai_thich, false);
                });
                optWrap.appendChild(div);
            });
        }
    }

    function answerQuestion(userAnswer, options, explanation, isTextEntry, questionPinyin) {
        let isCorrect = false;
        let correctText = '';
        
        if (isTextEntry) {
            const removeTones = (str) => {
                if (!str) return '';
                return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/v/g, "u").toLowerCase().replace(/\s+/g, "");
            };

            const input = userAnswer.replace(/\s+/g, '').toLowerCase();
            correctText = options[0].noi_dung;
            const correct = correctText.replace(/\s+/g, '').toLowerCase();
            
            isCorrect = (input === correct);
            
            if (!isCorrect && options[0].pinyin) {
                const correctPinyin = removeTones(options[0].pinyin);
                const inputPinyin = removeTones(userAnswer);
                if (inputPinyin === correctPinyin && inputPinyin !== '') isCorrect = true;
            }
            
            const inputEl = document.getElementById('tqInputAnswer');
            const btnEl = document.getElementById('tqSubmitAnswer');
            if (btnEl) btnEl.disabled = true;
            
            if (inputEl) {
                inputEl.disabled = true;
                if (isCorrect) {
                    inputEl.classList.add('is-valid');
                    inputEl.style.borderColor = 'var(--bs-success)';
                } else {
                    inputEl.classList.add('is-invalid');
                    inputEl.style.borderColor = 'var(--bs-danger)';
                }
            }
        } else {
            const optionsEl = document.querySelectorAll('.tq-option');
            optionsEl.forEach(o => o.style.pointerEvents = 'none');
            
            const correctIdx = options.findIndex(o => o.dung == 1);
            if(correctIdx !== -1) {
                optionsEl[correctIdx].classList.add('correct');
                correctText = options[correctIdx].noi_dung;
            }
            
            isCorrect = (userAnswer === correctIdx);
            
            if (!isCorrect && optionsEl[userAnswer]) {
                optionsEl[userAnswer].classList.add('wrong');
            }
        }
        
        const feedback = document.getElementById('tqFeedback');
        let feedbackHtml = '';
        
        if (isCorrect) {
            score++;
            feedback.classList.add('success-feedback');
            feedbackHtml = '<div class="d-flex align-items-center"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> <strong>Tuyệt vời! Đáp án hoàn toàn chính xác.</strong></div>';
        } else {
            feedback.classList.add('error-feedback');
            let msg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> <strong>Rất tiếc, câu trả lời chưa đúng.</strong>';
            if (isTextEntry) {
                msg += `<div class="mt-2 ms-4">Đáp án đúng là: <span class="badge bg-danger fs-6">${correctText}</span></div>`;
            } else {
                msg += '<div class="mt-2 ms-4">Đáp án đúng đã được tô màu xanh ở trên.</div>';
            }
            feedbackHtml = `<div>${msg}</div>`;
        }
        
        if(explanation) {
            feedbackHtml += `<div class="mt-3 pt-3 border-top" style="border-color: currentColor !important; opacity: 0.9;"><strong>💡 Giải thích chi tiết:</strong><br>${explanation}</div>`;
        }
        
        feedback.innerHTML = feedbackHtml;
        feedback.classList.add('show');

        // Delay longer if there's an explanation or if it's wrong, so the user can read
        let delay = isCorrect ? 1500 : 2500;
        if (explanation) delay = 4000;

        setTimeout(function() {
            qIndex++;
            renderQuestion();
        }, delay);
    }

    window.resetQuiz = function() {
        qIndex = 0;
        score = 0;
        document.getElementById('quizResult').classList.add('d-none');
        document.getElementById('quizActive').classList.remove('d-none');
        renderQuestion();
    }

    if (totalQuestions > 0) {
        renderQuestion();
    }
});
</script>
@endpush
