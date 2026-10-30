<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Bakery</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fffaf4 0%, #f4e0d0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-shell {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1fr 1.1fr;
            background: white;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(98, 60, 36, 0.15);
        }
        .promo {
            background: linear-gradient(135deg, #4d2d1d, #8a4b2b 60%, #d29663);
            color: white;
            padding: 48px 32px;
        }
        .promo h1 {
            font-size: 40px;
            margin: 0 0 18px;
        }
        .promo p {
            font-size: 18px;
            line-height: 1.7;
            opacity: 0.9;
        }
        .promo .chip {
            display: inline-block;
            margin-top: 18px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 600;
        }
        .form-panel {
            padding: 44px 34px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            width: 100%;
            max-width: 420px;
        }
        .brand {
            color: #8a4b2b;
            font-weight: 700;
            font-size: 26px;
            margin-bottom: 12px;
        }
        h2 {
            margin: 0 0 8px;
            font-size: 34px;
        }
        .subtext {
            color: #6e4e3d;
            margin-bottom: 24px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #4a3128;
            font-weight: 600;
        }
        input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #e6d5c6;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 16px;
        }
        input:focus {
            outline: none;
            border-color: #8a4b2b;
            box-shadow: 0 0 0 3px rgba(138, 75, 43, 0.12);
        }
        button {
            width: 100%;
            border: none;
            background: #8a4b2b;
            color: white;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }
        .error {
            color: #c0392b;
            margin: 8px 0 16px;
            font-weight: 600;
        }
        .link {
            margin-top: 18px;
            text-align: center;
            color: #5d4034;
        }
        .link a {
            color: #8a4b2b;
            text-decoration: none;
            font-weight: 700;
        }
        @media (max-width: 860px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }
            .promo {
                padding: 30px 24px;
            }
            .form-panel {
                padding: 28px 20px 42px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="promo">
            <div class="brand">Bakery</div>
            <h1>Đăng ký</h1>
            <p>Đăng ký tài khoản để theo dõi đơn hàng, nhận ưu đãi và đặt mua bánh dễ dàng hơn.</p>
            <div class="chip">Tạo tài khoản miễn phí</div>
        </div>

        <div class="form-panel">
            <div class="card">
                <h2>Tạo tài khoản</h2>
                <div class="subtext">Điền thông tin bên dưới để bắt đầu</div>

                @if ($errors->any())
                    <div class="error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div>
                        <label for="name">Họ tên</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label for="password">Mật khẩu</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <div>
                        <label for="password_confirmation">Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>

                    <button type="submit">Đăng ký</button>
                </form>

                <div class="link">
                    Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
