@extends('frontend.layouts.main')

@section('title', 'Học thử - ' . $baiHoc->ten_bai_hoc)
@push('styles')
<link href="{{ asset('frontend/asset/css/triallesson.css') }}" rel="stylesheet">
<link href="https://vjs.zencdn.net/7.21.5/video-js.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/videojs-seek-buttons/dist/videojs-seek-buttons.css" rel="stylesheet">
<style>
    /* Bổ sung cho flashcard từ vựng: ảnh minh họa, bookmark, nút đã học */
    .mini-thumb{
        width:100%; max-height:64px; object-fit:cover; border-radius:10px; margin-bottom:.35rem;
    }
    .mini-bookmark{
        position:absolute; top:8px; left:8px; border:1px solid var(--border); background:var(--bg);
        border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center;
        color:var(--text-muted); cursor:pointer;
    }
    .mini-bookmark.active{ color:var(--secondary); border-color:var(--secondary); }
    .mini-audio.playing{ background:var(--primary); color:#fff; }
    .mini-learned-btn{
        margin-top:.5rem; border:1.5px solid var(--border); background:var(--card); color:var(--text-muted);
        font-size:.72rem; font-weight:700; border-radius:999px; padding:.3rem .8rem; cursor:pointer; transition:all .15s ease;
    }
    .mini-learned-btn.active{
        border-color:var(--success); background:color-mix(in srgb, var(--success) 14%, var(--card)); color:var(--success);
    }
</style>
@endpush
@section('content')
    {{-- Banner học thử --}}
    <div class="trial-banner">
        <div class="container">
            <span>🎓 Bạn đang <strong>học thử miễn phí</strong> {{ $baiHoc->ten_bai_hoc }}</span>
            <div class="track"><div class="fill" style="width: 8%;"></div></div>
            <span class="d-none d-sm-inline">Còn 6 phút xem thử</span>
            <a href="{{ route('khoahoc.show', $khoaHoc->slug) }}" class="cta">
                Đăng ký học đầy đủ
            </a>
        </div>
    </div>

    <main class="container trial-page-pad">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('khoahoc.show', $khoaHoc->slug) }}">{{ $khoaHoc->ten_khoa_hoc }}</a></li>
                <li class="breadcrumb-item active">Học thử — {{ $baiHoc->ten_bai_hoc }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Tabs --}}
                <div class="content-tabs" id="contentTabs">
                    <button class="content-tab active" data-panel="video">🎬 Video bài giảng</button>
                    <button class="content-tab" data-panel="vocab">🀄 Từ vựng bài học</button>
                    <button class="content-tab" data-panel="quiz">📝 Đề kiểm tra</button>
                </div>

                {{-- Panel Video --}}
                @php
                    $videoRaw = $baiHoc->video;
                    $isExternalUrl = $videoRaw && preg_match('#^https?://#i', $videoRaw);
                    $embedUrl = null;
                    if ($isExternalUrl) {
                        if (preg_match('#youtu\.be/([a-zA-Z0-9_-]+)#', $videoRaw, $m) ||
                            preg_match('#youtube\.com/watch\?v=([a-zA-Z0-9_-]+)#', $videoRaw, $m) ||
                            preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]+)#', $videoRaw, $m)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                        } elseif (preg_match('#vimeo\.com/(\d+)#', $videoRaw, $m)) {
                            $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                        }
                    }
                @endphp
                <div class="content-panel" id="panel-video">
                    <div class="video-frame">
                        @if($isExternalUrl && $embedUrl)
                            <iframe src="{{ $embedUrl }}" style="width:100%;height:100%;border:0;" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                        @elseif($isExternalUrl)
                            <video id="ext-video" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" style="width:100%;height:100%;object-fit:cover;" data-setup='{"fluid": true, "playbackRates": [0.5, 0.75, 1, 1.25, 1.5, 2]}'>
                                <source src="{{ $videoRaw }}">
                            </video>
                        @elseif($baiHoc->hls_path)
                            <video id="hls-video" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" style="width:100%;height:100%;object-fit:cover;" data-setup='{"fluid": true, "playbackRates": [0.5, 0.75, 1, 1.25, 1.5, 2]}'>
                                <source src="{{ asset('storage/' . $baiHoc->hls_path) }}" type="application/x-mpegURL">
                            </video>
                        @elseif($videoRaw)
                            <video id="raw-video" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" style="width:100%;height:100%;object-fit:cover;" data-setup='{"fluid": true, "playbackRates": [0.5, 0.75, 1, 1.25, 1.5, 2]}'>
                                <source src="{{ asset('storage/' . $videoRaw) }}">
                            </video>
                        @else
                            <img src="{{ $baiHoc->anh_bia ? asset('storage/' . $baiHoc->anh_bia) : 'https://via.placeholder.com/800x450' }}" alt="Bài giảng">
                        @endif
                    </div>

                    {{-- Bảng ghi hội thoại nếu có --}}
                    @if($baiHoc->hoiThoais->isNotEmpty())
                        <div class="transcript-box">
                            <h3 class="font-head fs-6 fw-bold mb-2">Bản ghi hội thoại</h3>
                            @foreach($baiHoc->hoiThoais as $hoiThoai)
                                @foreach($hoiThoai->chiTietHoiThoais as $ct)
                                    <div class="transcript-line">
                                        <span class="transcript-time">{{ $ct->thoi_gian ?? '00:00' }}</span>
                                        <div class="transcript-text">
                                            <div class="zh">{{ $ct->noi_dung_tieng_trung }}</div>
                                            <div class="vi">{{ $ct->noi_dung_tieng_viet }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Panel Từ vựng --}}
                <div class="content-panel d-none" id="panel-vocab">
                    <p class="mb-3" style="color: var(--text-muted)">
                        Chạm vào thẻ để lật và xem nghĩa — nghe thử phát âm chuẩn.
                    </p>
                    <div class="trial-vocab-grid" id="vocabGrid">
                        @foreach($baiHoc->tuVungs as $tu)
                            <div class="mini-card-scene">
                                <div class="mini-flashcard" onclick="this.classList.toggle('flipped')">
                                    <div class="mini-face front">
                                        <button
                                            class="mini-audio"
                                            data-audio-url="{{ $tu->am_thanh ? asset('storage/' . $tu->am_thanh) : '' }}"
                                            data-fallback-text="{{ $tu->tu_han }}"
                                            onclick="event.stopPropagation(); playVocabAudio(this)"
                                            aria-label="Nghe phát âm"
                                        >
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M15.5 8.5a5 5 0 010 7"/>
                                            </svg>
                                        </button>

                                        <button
                                            class="mini-bookmark"
                                            data-id="{{ $tu->id }}"
                                            onclick="event.stopPropagation(); toggleBookmark(this)"
                                            aria-label="Đánh dấu từ này"
                                        >
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                                        </button>

                                        @if($tu->hinh_anh)
                                            <img src="{{ asset('storage/' . $tu->hinh_anh) }}" alt="{{ $tu->tu_han }}" class="mini-thumb">
                                        @endif

                                        <div class="mini-hanzi zh">{{ $tu->tu_han }}</div>
                                        <div class="mini-meaning">Chạm để lật</div>
                                    </div>
                                    <div class="mini-face back">
                                        <div class="mini-pinyin">{{ $tu->phien_am }}</div>
                                        <div class="mini-meaning fw-semibold">{{ $tu->nghia_tieng_viet }}</div>
                                        <button
                                            class="mini-learned-btn"
                                            data-id="{{ $tu->id }}"
                                            onclick="event.stopPropagation(); toggleLearned(this)"
                                        >
                                            <span class="learned-label">Đánh dấu đã học</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($baiHoc->tuVungs->isEmpty())
                        <p class="text-muted">Bài học này chưa có từ vựng nào được thêm.</p>
                    @endif
                </div>

                {{-- Panel Quiz --}}
                <div class="content-panel d-none" id="panel-quiz">
                    <div class="trial-quiz-card">
                        <div id="quizActive">
                            <div class="tq-progress">Câu <span id="tqCurrent">1</span>/{{ count($baiHoc->cauHois) }}</div>
                            <div class="tq-question" id="tqQuestion"></div>
                            <div id="tqOptions"></div>
                            <div class="tq-feedback" id="tqFeedback"></div>
                        </div>
                        <div class="tq-result d-none" id="quizResult">
                            <div class="fw-bold fs-5 mb-1">Kết quả: <span id="tqScore">0</span>/{{ count($baiHoc->cauHois) }}</div>
                            <p style="color: var(--text-muted)" class="mb-3">Làm tốt lắm! Đăng ký khóa học để luyện thêm hàng trăm câu quiz như thế này.</p>
                            <a href="{{ route('khoahoc.show', $khoaHoc->slug) }}" class="btn-brand">Đăng ký học đầy đủ</a>
                        </div>
                    </div>
                </div>

                {{-- Paywall / CTA --}}
                <div class="paywall-card">
                    <div class="paywall-lock">🔒</div>
                    <h3>Bạn vừa hoàn thành bài học thử!</h3>
                    <p>Còn {{ $khoaHoc->chuongHocs->sum(fn($c) => $c->baiHocs->count()) - 1 }} bài giảng, hơn 600 từ vựng và bộ đề luyện thi HSK 3 đầy đủ đang chờ bạn ở phía trước.</p>
                    <div class="paywall-price">
                        {{ number_format($khoaHoc->gia, 0, ',', '.') }}₫
                        <span style="font-size: 0.85rem; opacity: 0.8; font-weight: 500">trọn khóa</span>
                    </div>
                    <div class="paywall-actions">
                        <a href="{{ route('khoahoc.show', $khoaHoc->slug) }}" class="btn-white">Đăng ký khóa học</a>
                        <button class="btn-outline-white" data-bs-toggle="modal" data-bs-target="#registerModal">Nhận thêm bài học miễn phí</button>
                    </div>
                </div>

                {{-- Lead-gen email --}}
                <div class="leadgen-box">
                    <h3 class="font-head fs-6 fw-bold mb-0">Nhận thêm 3 bài học thử miễn phí qua email</h3>
                    <form id="leadForm" class="row g-2" novalidate>
                        <div class="col-12 col-sm-8">
                            <input type="email" class="form-control" id="leadEmail" placeholder="ban@email.com" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <button type="submit" class="btn-brand w-100" id="leadSubmitBtn">
                                <span id="leadBtnText">Gửi cho tôi</span>
                                <span id="leadSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar: danh sách bài học --}}
            <div class="col-lg-4">
                <div class="curriculum-panel">
                    <h3>Nội dung khóa học</h3>
                    @foreach($khoaHoc->chuongHocs as $chuong)
                        @foreach($chuong->baiHocs as $bai)
                            @php
                                $isCurrent = $bai->id == $baiHoc->id;
                                $isFree = $bai->mien_phi == 1;
                            @endphp
                            
                            @if($isFree && !$isCurrent)
                                <a href="{{ route('baihoc.trial', $bai->slug) }}" class="text-decoration-none text-dark">
                            @endif
                            
                            <div class="lesson-list-item {{ $isCurrent ? 'unlocked' : ($isFree ? '' : 'locked') }} {{ $isFree && !$isCurrent ? 'hover-shadow-sm transition-all' : '' }}">
                                <div class="lesson-icon {{ $isCurrent ? 'unlocked' : ($isFree ? '' : 'locked') }}" style="{{ $isFree && !$isCurrent ? 'background: var(--border); color: var(--primary); border: 1px solid var(--border);' : '' }}">
                                    @if($isCurrent || $isFree)
                                        ▶
                                    @else
                                        🔒
                                    @endif
                                </div>
                                <span class="lesson-name" style="{{ $isFree && !$isCurrent ? 'color: var(--text); font-weight: 600;' : '' }}">{{ $bai->ten_bai_hoc }}</span>
                                <span class="lesson-dur">{{ $bai->thoi_luong_giay ? floor($bai->thoi_luong_giay/60).'phút' : '' }}</span>
                            </div>
                            
                            @if($isFree && !$isCurrent)
                                </a>
                            @endif
                        @endforeach
                    @endforeach
                    <hr>
                    <a href="{{ route('khoahoc.show', $khoaHoc->slug) }}" class="btn-brand w-100 text-center d-block">Xem toàn bộ nội dung</a>
                </div>
            </div>
        </div>
    </main>

@push('styles')
<link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://vjs.zencdn.net/7.21.5/video.min.js"></script>
<!-- HLS Quality Level Plugins -->
<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.1.0/dist/videojs-contrib-quality-levels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@1.1.4/dist/videojs-hls-quality-selector.min.js"></script>
<!-- Seek Buttons & Hotkeys Plugins -->
<script src="https://cdn.jsdelivr.net/npm/videojs-seek-buttons/dist/videojs-seek-buttons.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-hotkeys/videojs.hotkeys.min.js"></script>

<script>
    // Khởi tạo video js nếu có
    var videoIds = ['hls-video', 'raw-video', 'ext-video'];
    videoIds.forEach(function(vid) {
        if (document.getElementById(vid)) {
            var player = videojs(vid);
            
            // Kích hoạt tính năng chọn chất lượng (chỉ áp dụng HLS)
            if (vid === 'hls-video' && typeof player.hlsQualitySelector === 'function') {
                player.hlsQualitySelector({
                    displayCurrentQuality: true,
                });
            }

            // Kích hoạt nút tua 10s
            if (typeof player.seekButtons === 'function') {
                player.seekButtons({
                    forward: 10,
                    back: 10
                });
            }

            // Kích hoạt phím tắt (tua bằng mũi tên trái phải, dừng bằng space)
            if (typeof player.hotkeys === 'function') {
                player.hotkeys({
                    volumeStep: 0.1,
                    seekStep: 5,
                    enableModifiersForNumbers: false
                });
            }
        }
    });
</script>
@endpush
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Tab switching ---
        document.getElementById('contentTabs').addEventListener('click', function(e) {
            const tab = e.target.closest('.content-tab');
            if (!tab) return;
            document.querySelectorAll('.content-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            document.querySelectorAll('.content-panel').forEach(p => p.classList.add('d-none'));
            document.getElementById('panel-' + tab.dataset.panel).classList.remove('d-none');
        });

        // --- Text-to-speech (dự phòng khi từ chưa có file âm thanh) ---
        function speak(text) {
            try {
                if (!('speechSynthesis' in window)) return;
                const u = new SpeechSynthesisUtterance(text);
                u.lang = 'zh-CN';
                u.rate = 0.85;
                window.speechSynthesis.cancel();
                window.speechSynthesis.speak(u);
            } catch (e) {}
        }
        window.speak = speak; // để gọi từ onclick

        // --- Phát âm thanh thật của từ vựng (file upload hoặc TTS đã lưu sẵn ở server) ---
        // Nếu từ có file am_thanh (do TuVungController tự sinh bằng Google TTS hoặc admin upload)
        // thì phát file đó; nếu không có, mới rơi về giọng đọc trình duyệt (speak()).
        let currentAudio = null;
        window.playVocabAudio = function (btn) {
            const url = btn.dataset.audioUrl;
            const fallbackText = btn.dataset.fallbackText;
            btn.classList.add('playing');
            setTimeout(() => btn.classList.remove('playing'), 500);

            if (url) {
                if (currentAudio) { currentAudio.pause(); currentAudio.currentTime = 0; }
                currentAudio = new Audio(url);
                currentAudio.play().catch(function () {
                    // Nếu file lỗi/không tồn tại thì fallback sang TTS trình duyệt
                    speak(fallbackText);
                });
            } else {
                speak(fallbackText);
            }
        };

        // --- Đánh dấu Bookmark / Đã học (lưu tạm ở localStorage theo trình duyệt) ---
        // Lưu ý: đây là lưu phía client. Nếu muốn đồng bộ theo tài khoản trên nhiều thiết bị,
        // cần thêm bảng pivot (id_nguoi_dung, id_tu_vung, da_hoc, da_luu) + route AJAX lưu server.
        function getStoredSet(key) {
            try { return new Set(JSON.parse(localStorage.getItem(key) || '[]')); }
            catch (e) { return new Set(); }
        }
        function saveStoredSet(key, set) {
            try { localStorage.setItem(key, JSON.stringify(Array.from(set))); }
            catch (e) {}
        }

        const bookmarkedSet = getStoredSet('tuvung_bookmarked');
        const learnedSet = getStoredSet('tuvung_learned');

        // Khôi phục trạng thái đã lưu khi tải trang
        document.querySelectorAll('.mini-bookmark').forEach(function (btn) {
            if (bookmarkedSet.has(btn.dataset.id)) btn.classList.add('active');
        });
        document.querySelectorAll('.mini-learned-btn').forEach(function (btn) {
            if (learnedSet.has(btn.dataset.id)) {
                btn.classList.add('active');
                btn.querySelector('.learned-label').textContent = 'Đã học ✓';
            }
        });

        window.toggleBookmark = function (btn) {
            const id = btn.dataset.id;
            if (bookmarkedSet.has(id)) { bookmarkedSet.delete(id); btn.classList.remove('active'); }
            else { bookmarkedSet.add(id); btn.classList.add('active'); }
            saveStoredSet('tuvung_bookmarked', bookmarkedSet);
        };

        window.toggleLearned = function (btn) {
            const id = btn.dataset.id;
            const label = btn.querySelector('.learned-label');
            if (learnedSet.has(id)) {
                learnedSet.delete(id);
                btn.classList.remove('active');
                label.textContent = 'Đánh dấu đã học';
            } else {
                learnedSet.add(id);
                btn.classList.add('active');
                label.textContent = 'Đã học ✓';
            }
            saveStoredSet('tuvung_learned', learnedSet);
        };

        // --- Real Quiz generation ---
        const examQuestions = {!! json_encode($baiHoc->cauHois->map(function($q) {
            return [
                'id' => $q->id,
                'noi_dung' => $q->noi_dung,
                'pinyin' => $q->pinyin,
                'dich_nghia' => $q->dich_nghia,
                'giai_thich' => $q->giai_thich,
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
        const total = examQuestions.length;

        function renderQuestion() {
            if (qIndex >= total) {
                document.getElementById('quizActive').classList.add('d-none');
                document.getElementById('quizResult').classList.remove('d-none');
                document.getElementById('tqScore').textContent = score;
                return;
            }
            const item = examQuestions[qIndex];
            document.getElementById('tqCurrent').textContent = qIndex + 1;
            
            // Build question text with pinyin/meaning if available
            let qText = item.noi_dung;
            if(item.pinyin) qText += `<br><small style="color:var(--text-muted); font-weight:normal;">${item.pinyin}</small>`;
            if(item.dich_nghia) qText += `<br><small style="color:var(--text-muted); font-weight:normal;">${item.dich_nghia}</small>`;
            
            document.getElementById('tqQuestion').innerHTML = qText;
            
            const optWrap = document.getElementById('tqOptions');
            optWrap.innerHTML = '';
            document.getElementById('tqFeedback').classList.remove('show');
            document.getElementById('tqFeedback').innerHTML = ''; // clear explanation

            const options = item.dapAns;
            
            if (options.length === 1) {
                // Kiểm tra xem có phải câu Sắp xếp (chứa dấu "/") không
                if (item.noi_dung.includes('/')) {
                    const words = item.noi_dung.split('/').map(w => w.trim()).filter(w => w !== '');
                    
                    const sortWrap = document.createElement('div');
                    sortWrap.className = 'mt-3';
                    sortWrap.innerHTML = `
                        <div id="tqAnswerBox" class="p-3 mb-3 border rounded d-flex flex-wrap gap-2 min-vh-25" style="background:var(--bg); min-height: 50px;"></div>
                        <div id="tqWordBank" class="d-flex flex-wrap gap-2 mb-3"></div>
                        <button class="btn-brand px-4" id="tqSubmitAnswer">Kiểm tra</button>
                    `;
                    optWrap.appendChild(sortWrap);
                    
                    const answerBox = document.getElementById('tqAnswerBox');
                    const wordBank = document.getElementById('tqWordBank');
                    
                    // Tạo các chip từ
                    words.forEach((word, index) => {
                        const chip = document.createElement('button');
                        chip.className = 'btn btn-outline-secondary btn-sm rounded-pill';
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
                        // Nối các từ trong answerBox lại
                        const userVal = Array.from(answerBox.children).map(c => c.dataset.word).join('');
                        if (!userVal) return;
                        // Vô hiệu hóa các chip
                        Array.from(answerBox.children).forEach(c => c.disabled = true);
                        Array.from(wordBank.children).forEach(c => c.disabled = true);
                        answerQuestion(userVal, options, item.giai_thich, true);
                    });
                    
                } else {
                    // Điền khuyết bình thường -> Hiển thị ô nhập liệu
                    const inputWrap = document.createElement('div');
                    inputWrap.className = 'mt-3 d-flex gap-2';
                    inputWrap.innerHTML = `
                        <input type="text" id="tqInputAnswer" class="form-control" placeholder="Nhập câu trả lời (tiếng Trung hoặc Pinyin)..." autocomplete="off">
                        <button class="btn-brand px-4" id="tqSubmitAnswer">Kiểm tra</button>
                    `;
                    optWrap.appendChild(inputWrap);
                    
                    document.getElementById('tqSubmitAnswer').addEventListener('click', function() {
                        const userVal = document.getElementById('tqInputAnswer').value.trim();
                        if (!userVal) return;
                        answerQuestion(userVal, options, item.giai_thich, true, item.pinyin);
                    });
                    
                    document.getElementById('tqInputAnswer').addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            document.getElementById('tqSubmitAnswer').click();
                        }
                    });
                }
            } else {
                // Trắc nghiệm nhiều đáp án
                options.forEach(function(opt, idx) {
                    const div = document.createElement('div');
                    div.className = 'tq-option';
                    
                    let optText = opt.noi_dung;
                    if(opt.pinyin) optText += ` <span style="color:var(--text-muted); font-size: 0.85em;">(${opt.pinyin})</span>`;
                    
                    let letterHtml = `<span class="tq-letter">${String.fromCharCode(65 + idx)}</span>`;
                    
                    div.innerHTML = `${letterHtml}<span>${optText}</span>`;
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
                // So sánh chuỗi nhập vào với đáp án (bỏ qua khoảng trắng thừa)
                const input = userAnswer.replace(/\s+/g, '').toLowerCase();
                correctText = options[0].noi_dung;
                const correct = correctText.replace(/\s+/g, '').toLowerCase();
                
                isCorrect = (input === correct);
                
                // Nếu người dùng gõ pinyin, so sánh với pinyin của đáp án (hoặc của câu hỏi)
                if (!isCorrect && options[0].pinyin) {
                    const correctPinyin = options[0].pinyin.replace(/\s+/g, '').toLowerCase();
                    if (input === correctPinyin) isCorrect = true;
                }
                
                const inputEl = document.getElementById('tqInputAnswer');
                const btnEl = document.getElementById('tqSubmitAnswer');
                if (btnEl) btnEl.disabled = true;
                
                if (inputEl) {
                    inputEl.disabled = true;
                    if (isCorrect) {
                        inputEl.classList.add('is-valid');
                        inputEl.style.borderColor = 'var(--success)';
                    } else {
                        inputEl.classList.add('is-invalid');
                        inputEl.style.borderColor = 'var(--danger)';
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
                feedbackHtml = '<div style="color:var(--success)">✓ Chính xác!</div>';
            } else {
                let msg = '✗ Chưa đúng.';
                if (isTextEntry) {
                    msg = '✗ Chưa đúng — đáp án đúng là: <strong>' + correctText + '</strong>';
                } else {
                    msg = '✗ Chưa đúng — đáp án đúng đã được tô xanh.';
                }
                feedbackHtml = '<div style="color:var(--danger)">' + msg + '</div>';
            }
            
            if(explanation) {
                feedbackHtml += `<div class="mt-2 small p-2 rounded" style="background:var(--bg); border: 1px solid var(--border); color:var(--text); text-align:left;"><strong>Giải thích:</strong> ${explanation}</div>`;
            }
            
            feedback.innerHTML = feedbackHtml;
            feedback.classList.add('show');

            setTimeout(function() {
                qIndex++;
                renderQuestion();
            }, explanation ? 3500 : 1300); // Nếu có giải thích thì đợi lâu hơn một chút
        }

        if (total > 0) {
            document.querySelector('.tq-progress').innerHTML = `Câu <span id="tqCurrent">1</span>/${total}`;
            document.getElementById('quizResult').querySelector('span').nextSibling.nodeValue = `/${total}`;
            renderQuestion();
        } else {
            document.getElementById('quizActive').innerHTML = '<p class="text-muted">Chưa có đề kiểm tra cho bài học này.</p>';
        }

        // --- Lead form AJAX ---
        const leadForm = document.getElementById('leadForm');
        const leadEmail = document.getElementById('leadEmail');
        const leadBtn = document.getElementById('leadSubmitBtn');
        const leadBtnText = document.getElementById('leadBtnText');
        const leadSpinner = document.getElementById('leadSpinner');

        leadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!leadEmail.checkValidity()) {
                leadEmail.classList.add('is-invalid');
                return;
            }
            alert('Cảm ơn! Tính năng đăng ký email sẽ sớm được hỗ trợ.');
            leadForm.reset();
        });
    });
</script>
@endpush