@php
    $features = \App\Models\Feature::where('is_active', true)->orderBy('order', 'asc')->get();
@endphp

<style>
    .features-section {
        padding: 5rem 0;
        background-color: var(--hb-bg); /* Hoặc màu nền nhạt của bạn, ví dụ: #f8fafc */
        text-align: center;
    }
    
    .features-subtitle {
        color: var(--hb-primary);
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    
    .features-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--hb-text);
        margin-bottom: 3rem;
        font-family: 'Poppins', sans-serif;
    }

    .feature-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 2rem;
        text-align: left;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.05);
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    }

    .feature-icon-wrapper {
        width: 48px;
        height: 48px;
        background-color: rgba(220, 38, 38, 0.1); /* Nhạt của --hb-primary */
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .feature-icon-wrapper svg {
        width: 24px;
        height: 24px;
        color: var(--hb-primary);
    }

    .feature-item-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--hb-text);
        margin-bottom: 0.75rem;
    }

    .feature-item-desc {
        font-size: 0.9rem;
        color: var(--hb-text-muted);
        line-height: 1.5;
        margin-bottom: 0;
    }
    
    .features-action-btn {
        margin-top: 3rem;
        background-color: transparent;
        color: var(--hb-text);
        border: 1px solid var(--hb-border);
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .features-action-btn:hover {
        background-color: var(--hb-border);
        color: var(--hb-text);
    }

    [data-theme="dark"] .feature-card {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    [data-theme="dark"] .feature-icon-wrapper {
        background-color: rgba(220, 38, 38, 0.15);
    }
    [data-theme="dark"] .features-action-btn {
        color: #f8fafc;
        border-color: #334155;
    }
    [data-theme="dark"] .features-action-btn:hover {
        background-color: #334155;
    }
</style>

<section class="features-section">
    <div class="container">
        <div class="features-subtitle">{{ \App\Models\CauHinh::getByKey('home_features_subtitle', 'VÌ SAO CHỌN HÁNYǓ BÀN') }}</div>
        <h2 class="features-title">{{ \App\Models\CauHinh::getByKey('home_features_title', 'Mọi kỹ năng, một nền tảng duy nhất') }}</h2>
        
        <div class="row g-4 justify-content-center">
            @foreach($features as $feature)
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        {!! $feature->icon !!}
                    </div>
                    <h3 class="feature-item-title">{{ $feature->title }}</h3>
                    <p class="feature-item-desc">{{ $feature->description }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <button class="btn features-action-btn">Khám phá chi tiết các tính năng</button>
    </div>
</section>
