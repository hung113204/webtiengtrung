<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Học thử Hányǔ Bàn</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #dc3545; }
        .content { margin-bottom: 20px; }
        .footer { text-align: center; font-size: 12px; color: #777; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Chào mừng đến với Hányǔ Bàn!</h1>
        </div>
        <div class="content">
            <p>Xin chào,</p>
            <p>Cảm ơn bạn đã đăng ký nhận tài khoản học thử 7 ngày tại Hányǔ Bàn. Chúng tôi đã nhận được yêu cầu từ email <strong>{{ $email }}</strong>.</p>
            <p>Dưới đây là thông tin tài khoản học thử của bạn:</p>
            <ul>
                <li><strong>Tên đăng nhập:</strong> {{ $email }}</li>
                <li><strong>Mật khẩu:</strong> hocthu123456</li>
            </ul>
            <p>Tài khoản này có thời hạn sử dụng trong 7 ngày và bao gồm 1 buổi luyện phát âm cùng AI gia sư.</p>
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/') }}" class="btn">Đăng nhập và học ngay</a>
            </p>
            <p>Nếu bạn có bất kỳ thắc mắc nào, đừng ngần ngại liên hệ lại với chúng tôi.</p>
            <p>Trân trọng,<br>Đội ngũ Hányǔ Bàn</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Hányǔ Bàn. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
