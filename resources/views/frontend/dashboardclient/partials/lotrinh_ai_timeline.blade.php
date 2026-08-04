@if(count($detailedPath) > 0)
    <div class="timeline mt-4" style="position: relative; padding-left: 30px;">
        <!-- Dòng kẻ dọc của timeline -->
        <div style="position: absolute; top: 0; bottom: 0; left: 15px; width: 2px; background: var(--border);"></div>

        @foreach($detailedPath as $index => $item)
            @php $course = $item['course']; @endphp
            <div class="timeline-item mb-4" style="position: relative;">
                <!-- Dấu chấm timeline -->
                <div style="position: absolute; left: -21px; top: 5px; width: 14px; height: 14px; border-radius: 50%; background: var(--primary); border: 2px solid #fff; box-shadow: 0 0 0 2px var(--primary);"></div>
                
                <div class="brand-card p-4 shadow-sm" style="border-radius: 12px; transition: transform 0.2s;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="font-head fw-bold fs-5 mb-1 text-dark">{{ $course->ten_khoa_hoc }}</h4>
                        <span class="badge bg-soft-primary text-primary" style="font-size: 0.8rem; padding: 5px 10px; border-radius: 99px;">Bước {{ $index + 1 }}</span>
                    </div>
                    
                    <p class="text-muted small mb-3">{{ Str::limit(strip_tags($course->mo_ta), 120) }}</p>
                    
                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="d-flex gap-2 align-items-start">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning mt-1 flex-shrink-0">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <div>
                                <span class="fw-semibold d-block" style="color: var(--text)">Lý do gợi ý:</span>
                                <span class="small" style="color: var(--text-muted)">{{ $item['reason'] }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('khoahoc.show', ['slug' => $course->slug ?? $course->id]) }}" class="btn btn-primary btn-sm rounded-pill px-4">Xem chi tiết khóa học</a>
                </div>
            </div>
        @endforeach
        
        <!-- Hoàn thành -->
        <div class="timeline-item mt-5" style="position: relative;">
            <div style="position: absolute; left: -24px; top: 0; width: 20px; height: 20px; border-radius: 50%; background: var(--success); display: flex; align-items: center; justify-content: center;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="fw-bold fs-6" style="color: var(--success)">Hoàn thành lộ trình!</div>
        </div>
    </div>
@else
    <div class="text-center py-5">
        <p class="text-muted">Rất tiếc, AI chưa thể gợi ý lộ trình phù hợp vào lúc này. Vui lòng thử lại.</p>
    </div>
@endif
