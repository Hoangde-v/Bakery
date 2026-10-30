<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Khách hàng</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f6f1; margin: 0; }
        .container { max-width: 960px; margin: 60px auto; background: white; border-radius: 12px; padding: 32px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        h1 { color: #8a4b2b; }
        .meta { margin: 16px 0; color: #555; }
        form { margin-top: 20px; }
        button {
            background: #8a4b2b; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Chào mừng, {{ Auth::user()->name }}</h1>
        <div class="meta">Vai trò: Khách hàng</div>
        <p>Bạn đang ở khu vực khách hàng của hệ thống Bakery. Tại đây bạn có thể xem thông tin cá nhân và đơn hàng của mình.</p>

        @if (session('status'))
            <div style="margin-top:20px; background:#eafaf1; color:#1b7f46; padding:12px 14px; border-radius:8px;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="margin-top:20px; background:#fdecea; color:#a13b2d; padding:12px 14px; border-radius:8px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Đăng xuất</button>
            </form>

            <form method="POST" action="{{ route('account.delete') }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không? Hành động này không thể hoàn tác.');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:#c0392b;">Xóa tài khoản</button>
            </form>
        </div>

        <div style="margin-top:30px; border-top:1px solid #eee; padding-top:20px;">
            <h2 style="color:#8a4b2b; margin-bottom:16px;">Đổi mật khẩu</h2>
            <form method="POST" action="{{ route('account.changePassword') }}" style="max-width:420px;">
                @csrf

                <div style="margin-bottom:14px;">
                    <label for="current_password" style="display:block; margin-bottom:6px; font-weight:bold;">Mật khẩu hiện tại</label>
                    <input id="current_password" type="password" name="current_password" required style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px;">
                </div>

                <div style="margin-bottom:14px;">
                    <label for="password" style="display:block; margin-bottom:6px; font-weight:bold;">Mật khẩu mới</label>
                    <input id="password" type="password" name="password" required style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px;">
                </div>

                <div style="margin-bottom:18px;">
                    <label for="password_confirmation" style="display:block; margin-bottom:6px; font-weight:bold;">Nhập lại mật khẩu mới</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px;">
                </div>

                <button type="submit">Cập nhật mật khẩu</button>
            </form>
        </div>
    </div>
</body>
</html>
