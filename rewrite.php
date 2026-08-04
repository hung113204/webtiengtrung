<?php
\ = 'c:\\xampp\\htdocs\\webtiengtrung\\resources\\views\\frontend\\khoahocclient\\index.blade.php';
\ = file_get_contents(\);

\ = '<div
          class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3"';
\ = strpos(\, \);

\ = '</main>';
\ = strpos(\, \);
\ = substr(\, \, \ - \);

\ = 'document.addEventListener("DOMContentLoaded", function () {';
\ = strpos(\, \);
\ = '</script>';
\ = strpos(\, \, \);
\ = substr(\, \, \ - \);

\ = preg_replace('/\\/\\* ---------- Theme ---------- \\*\\/.*?\\/\\* ---------- Save \\/ bookmark toggle ---------- \\*\\//s', '/* ---------- Save / bookmark toggle ---------- */', \);

\ = <<<EOT
@extends('frontend.layouts.main')
@section('title', 'Khóa h?c c?a tôi — Hányu Bàn')

@push('styles')
    <link href="{{ asset('frontend/asset/css/dashboard.css') }}" rel="stylesheet" />
    <link href="{{ asset('frontend/asset/css/chinesecourses.css') }}" rel="stylesheet" />
    <style>
      /* PREMIUM REDESIGN STYLES */
      :root {
        --primary-rgb: 239, 68, 68;
      }
      body {
        position: relative;
        background: #f8fafc !important;
      }
      body::before {
        content: '';
        position: fixed;
        top: -15%; left: -10%;
        width: 600px; height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(239, 68, 68, 0.12) 0%, transparent 70%);
        z-index: -1;
        pointer-events: none;
      }
      body::after {
        content: '';
        position: fixed;
        bottom: -15%; right: -10%;
        width: 700px; height: 700px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%);
        z-index: -1;
        pointer-events: none;
      }
      .course-card {
        background: rgba(255, 255, 255, 0.65) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.8) !important;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
      }
      .course-card:hover {
        transform: translateY(-8px) scale(1.02) !important;
        box-shadow: 0 20px 40px -12px rgba(239, 68, 68, 0.2) !important;
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
      }
      .course-cover {
        overflow: hidden;
      }
      .course-cover::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: inherit;
        transition: transform 0.6s ease;
        z-index: 0;
      }
      .course-card:hover .course-cover::before {
        transform: scale(1.15) rotate(2deg);
      }
      .course-cover > * {
        z-index: 1;
        position: relative;
      }
      .course-title {
        background: linear-gradient(90deg, #1e293b, #334155);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800 !important;
        transition: all 0.3s ease;
      }
      .course-card:hover .course-title {
        background: linear-gradient(90deg, #ef4444, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
      .status-tab {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(8px) !important;
        border: 1px solid rgba(255, 255, 255, 0.8) !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;
        transition: all 0.3s ease !important;
      }
      .status-tab:hover {
        transform: translateY(-2px);
      }
      .status-tab.active {
        background: linear-gradient(135deg, #ef4444, #f59e0b) !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.35) !important;
      }
      .btn-continue {
        background: linear-gradient(135deg, #ef4444, #f59e0b) !important;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3) !important;
        transition: all 0.3s ease !important;
        border: none !important;
        color: #fff !important;
      }
      .btn-continue:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4) !important;
      }
      .progress-thin {
        background: rgba(0,0,0,0.05) !important;
        height: 6px !important;
        border-radius: 10px !important;
        overflow: hidden;
      }
      .progress-thin .fill {
        background: linear-gradient(90deg, #ef4444, #f59e0b) !important;
        border-radius: 10px !important;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
      }
      /* Dark mode overrides */
      [data-theme="dark"] body {
        background: #0f172a !important;
      }
      [data-theme="dark"] .course-card {
        background: rgba(30, 41, 59, 0.65) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
      }
      [data-theme="dark"] .course-title {
        background: linear-gradient(90deg, #f8fafc, #cbd5e1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
      [data-theme="dark"] .course-card:hover .course-title {
        background: linear-gradient(90deg, #ef4444, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
      [data-theme="dark"] .status-tab {
        background: rgba(30, 41, 59, 0.7) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
      }
    </style>
@endpush

@section('content')
<div class="container page-pad my-5">
    \
</div>
@endsection

@push('scripts')
<script>
\
</script>
@endpush
EOT;

file_put_contents(\, \);
echo "SUCCESS";
