@extends('frontend.layouts.learning')

@section('title', 'Thực hành phát âm: ' . $baiHoc->ten_bai_hoc)

@push('styles')
<style>
    :root {
        --bg-app: #f4f6f8;
        --card-bg: #ffffff;
        --brand-primary: #4F46E5;
        --brand-success: #10B981;
        --brand-danger: #EF4444;
        --text-main: #1F2937;
        --text-muted: #6B7280;
    }
    
    body {
        background-color: var(--bg-app);
        font-family: 'Inter', sans-serif;
    }

    .pronunciation-container {
        max-width: 700px;
        margin: 30px auto;
        padding: 0 15px;
    }

    .main-card {
        background: var(--card-bg);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        overflow: hidden;
        position: relative;
    }

    .card-header-bar {
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        padding: 24px 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .vocab-display {
        padding: 40px 30px;
        text-align: center;
        min-height: 250px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .chinese-text {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 15px;
        letter-spacing: 2px;
        transition: color 0.3s ease;
    }

    .pinyin-text {
        font-size: 1.5rem;
        color: var(--brand-primary);
        font-weight: 500;
        margin-bottom: 10px;
    }

    .meaning-text {
        font-size: 1.1rem;
        color: var(--text-muted);
    }

    .controls-area {
        padding: 30px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    .btn-listen {
        background: white;
        border: 2px solid #e2e8f0;
        color: var(--text-main);
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-listen:hover {
        border-color: var(--brand-primary);
        color: var(--brand-primary);
        background: #eff6ff;
    }

    /* Microphone Button */
    .mic-button-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .mic-button {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--brand-primary);
        color: white;
        border: none;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        transition: transform 0.2s, background 0.2s;
    }

    .mic-button:hover {
        transform: scale(1.05);
    }

    .mic-button.recording {
        background: var(--brand-danger);
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
        animation: pulse 1.5s infinite;
    }

    .mic-ripple {
        position: absolute;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--brand-danger);
        opacity: 0;
        z-index: 1;
    }

    .recording .mic-ripple {
        animation: ripple 1.5s infinite ease-out;
    }

    @keyframes ripple {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(2); opacity: 0; }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(0.95); }
        100% { transform: scale(1); }
    }

    .feedback-box {
        margin-top: 20px;
        padding: 20px;
        border-radius: 16px;
        text-align: center;
        width: 100%;
        display: none;
        animation: slideUp 0.4s ease-out;
    }

    .feedback-box.show {
        display: block;
    }

    .feedback-score {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .feedback-msg {
        font-size: 1.1rem;
        font-weight: 500;
    }

    .status-text {
        font-size: 0.95rem;
        color: var(--text-muted);
        min-height: 24px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .char-correct { color: var(--brand-success); }
    .char-wrong { color: var(--brand-danger); }

    /* Completion Screen */
    .completion-screen {
        padding: 60px 30px;
        text-align: center;
        display: none;
    }
</style>
@endpush

@section('content')
<div class="pronunciation-container">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}" class="text-decoration-none fw-medium text-secondary d-flex align-items-center">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Trở về
        </a>
        <div class="fw-bold" style="color: var(--brand-primary)">
            Câu <span id="currentIdx">1</span>/{{ count($baiHoc->cauHois) }}
        </div>
    </div>

    <div class="main-card">
        <!-- Header -->
        <div class="card-header-bar" id="appHeader">
            <div>
                <h5 class="mb-0 fw-bold">{{ $baiHoc->ten_bai_hoc }}</h5>
            </div>
            <div class="progress" style="width: 100px; height: 6px; background: rgba(255,255,255,0.2);">
                <div id="progressBar" class="progress-bar bg-white" role="progressbar" style="width: 0%"></div>
            </div>
        </div>

        <!-- Practice UI -->
        <div id="practiceUI">
            <div class="vocab-display">
                <div class="pinyin-text" id="vpPinyin"></div>
                <div class="chinese-text" id="vpChinese"></div>
                <div class="meaning-text" id="vpMeaning"></div>
            </div>

            <div class="controls-area">
                <button class="btn-listen" id="btnListen">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                    Nghe mẫu
                </button>

                <div class="status-text" id="statusText">Nhấn vào mic để bắt đầu đọc</div>

                <div class="mic-button-wrapper">
                    <div class="mic-ripple"></div>
                    <button class="mic-button" id="btnRecord">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                    </button>
                </div>

                <div class="feedback-box" id="feedbackBox">
                    <div class="feedback-score" id="feedbackScore"></div>
                    <div class="feedback-msg" id="feedbackMsg"></div>
                    <button class="btn btn-primary mt-3 px-4 rounded-pill d-none" id="btnNext">Tiếp tục <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ms-1"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
                </div>
            </div>
        </div>

        <!-- Completion UI -->
        <div id="completionUI" class="completion-screen">
            <div class="mb-4">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--brand-success)" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h2 class="fw-bold mb-3">Bạn đã hoàn thành xuất sắc!</h2>
            <p class="text-muted fs-5 mb-4">Bài tập phát âm đã kết thúc.</p>
            
            <div id="apiCompletionMsg"></div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button class="btn btn-outline-primary btn-lg rounded-pill px-4" onclick="location.reload()">Luyện lại</button>
                @if($nextLesson)
                <a href="{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $nextLesson->slug]) }}" class="btn btn-primary btn-lg rounded-pill px-4">Bài tiếp theo</a>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questions = {!! json_encode($baiHoc->cauHois->map(function($q) {
        return [
            'noi_dung' => $q->noi_dung,
            'pinyin' => $q->pinyin,
            'dich_nghia' => $q->dich_nghia,
        ];
    })) !!};

    let qIndex = 0;
    const totalQuestions = questions.length;
    
    // Elements
    const vpChinese = document.getElementById('vpChinese');
    const vpPinyin = document.getElementById('vpPinyin');
    const vpMeaning = document.getElementById('vpMeaning');
    const btnListen = document.getElementById('btnListen');
    const btnRecord = document.getElementById('btnRecord');
    const statusText = document.getElementById('statusText');
    const feedbackBox = document.getElementById('feedbackBox');
    const feedbackScore = document.getElementById('feedbackScore');
    const feedbackMsg = document.getElementById('feedbackMsg');
    const btnNext = document.getElementById('btnNext');
    const progressBar = document.getElementById('progressBar');
    
    let recognition = null;
    let isRecording = false;

    // Speech Recognition Setup
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SpeechRecognition();
        recognition.lang = 'zh-CN';
        recognition.continuous = false;
        recognition.interimResults = false;

        recognition.onstart = function() {
            isRecording = true;
            btnRecord.classList.add('recording');
            statusText.textContent = 'Đang nghe...';
            feedbackBox.classList.remove('show');
            vpChinese.innerHTML = questions[qIndex].noi_dung; // reset color
        };

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            evaluatePronunciation(transcript, questions[qIndex].noi_dung);
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error', event.error);
            statusText.textContent = 'Lỗi thu âm. Vui lòng thử lại.';
            btnRecord.classList.remove('recording');
            isRecording = false;
        };

        recognition.onend = function() {
            btnRecord.classList.remove('recording');
            isRecording = false;
            if(statusText.textContent === 'Đang nghe...') {
                statusText.textContent = 'Đã xử lý xong.';
            }
        };
    } else {
        statusText.textContent = 'Trình duyệt không hỗ trợ nhận diện giọng nói.';
        btnRecord.disabled = true;
    }

    function loadQuestion() {
        if (qIndex >= totalQuestions) {
            showCompletion();
            return;
        }

        const q = questions[qIndex];
        vpChinese.innerHTML = q.noi_dung;
        vpPinyin.textContent = q.pinyin || '';
        vpMeaning.textContent = q.dich_nghia || '';
        
        document.getElementById('currentIdx').textContent = qIndex + 1;
        progressBar.style.width = ((qIndex / totalQuestions) * 100) + '%';
        
        feedbackBox.classList.remove('show');
        btnNext.classList.add('d-none');
        statusText.textContent = 'Nhấn vào mic để bắt đầu đọc';
    }

    // TTS Setup
    btnListen.addEventListener('click', () => {
        const utterance = new SpeechSynthesisUtterance(questions[qIndex].noi_dung);
        utterance.lang = 'zh-CN';
        utterance.rate = 0.8; // Read a bit slower
        window.speechSynthesis.speak(utterance);
    });

    btnRecord.addEventListener('click', () => {
        if (!recognition) return;
        if (isRecording) {
            recognition.stop();
        } else {
            recognition.start();
        }
    });

    function cleanPunctuation(str) {
        return str.replace(/[.,!?，。！？]/g, '');
    }

    function evaluatePronunciation(spokenText, correctText) {
        // A very simple mock evaluation for demo purposes.
        // In a real app, you'd send audio to an AI backend API.
        
        const cleanSpoken = cleanPunctuation(spokenText);
        const cleanCorrect = cleanPunctuation(correctText);
        
        let score = 0;
        let coloredHtml = '';
        
        if (cleanSpoken === cleanCorrect) {
            score = 100;
            coloredHtml = `<span class="char-correct">${correctText}</span>`;
        } else {
            // Count matching chars
            let matches = 0;
            let resultHtml = '';
            
            for(let i=0; i < cleanCorrect.length; i++) {
                const char = cleanCorrect[i];
                if (cleanSpoken.includes(char)) {
                    matches++;
                    resultHtml += `<span class="char-correct">${char}</span>`;
                } else {
                    resultHtml += `<span class="char-wrong">${char}</span>`;
                }
            }
            score = Math.round((matches / cleanCorrect.length) * 100);
            coloredHtml = resultHtml;
            // append punctuation back manually if needed, simplified here
        }

        // Display results
        vpChinese.innerHTML = coloredHtml;
        
        let colorClass, msg;
        if (score >= 80) {
            colorClass = 'text-success';
            msg = 'Tuyệt vời! Phát âm rất chuẩn.';
            btnNext.classList.remove('d-none');
        } else if (score >= 50) {
            colorClass = 'text-warning';
            msg = 'Khá tốt! Bạn hãy nghe lại mẫu và thử lại nhé.';
            btnNext.classList.add('d-none'); // Force retry
        } else {
            colorClass = 'text-danger';
            msg = 'Chưa chính xác. Đừng nản, thử lại nào!';
            btnNext.classList.add('d-none');
        }

        feedbackBox.className = 'feedback-box show bg-light';
        feedbackScore.className = `feedback-score ${colorClass}`;
        feedbackScore.textContent = score + '%';
        feedbackMsg.textContent = msg;
        
        if(score >= 80) {
            // Auto-advance after a successful pronunciation
            setTimeout(() => {
                qIndex++;
                loadQuestion();
            }, 2500);
        }
    }

    btnNext.addEventListener('click', () => {
        qIndex++;
        loadQuestion();
    });

    function showCompletion() {
        document.getElementById('practiceUI').classList.add('d-none');
        document.getElementById('appHeader').classList.add('d-none');
        document.getElementById('completionUI').style.display = 'block';

        // Auto submit progress
        const updateProgressUrl = "{{ route('frontend.dashboard.khoahoc.progress', $baiHoc->id) }}";
        const csrfToken = "{{ csrf_token() }}";
        
        fetch(updateProgressUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ da_hoan_thanh: 1 })
        }).then(res => res.json()).then(data => {
            window.location.href = "{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}";
        });
    }

    // Init
    loadQuestion();
});
</script>
@endpush
