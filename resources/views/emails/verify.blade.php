<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực tài khoản Hányǔ Bàn</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #4b5563;
        }
        .content h2 {
            font-size: 20px;
            color: #111827;
            margin-top: 0;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            background-color: #ef4444;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.25);
            transition: all 0.2s ease;
        }
        .btn:hover {
            background-color: #dc2626;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hányǔ Bàn 汉语伴</h1>
        </div>
        
        <div class="content">
            <h2>Xin chào, {{ $userName }}!</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Hányǔ Bàn</strong> - Nền tảng học tiếng Trung trực tuyến hiện đại.</p>
            <p>Để hoàn tất quá trình đăng ký và bảo vệ tài khoản của mình, vui lòng nhấn vào nút bên dưới để xác thực địa chỉ email:</p>
            
            <div class="button-container">
                <a href="{{ $verifyUrl }}" class="btn">Xác Thực Tài Khoản</a>
            </div>
            <p style="margin-top: 30px;">Nếu bạn không tạo tài khoản này, xin vui lòng bỏ qua email này.</p>
            <p>Trân trọng,<br><strong>Đội ngũ Hányǔ Bàn</strong></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Hányǔ Bàn. All rights reserved.</p>
            <p>Email liên hệ: support@hanyuban.com</p>
        </div>
    </div>
</body>
</html>
