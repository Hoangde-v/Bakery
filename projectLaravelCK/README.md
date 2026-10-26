## ⚠️ LƯU Ý TRƯỚC KHI CHẠY DỰ ÁN

Để tránh gặp lỗi khi chạy dự án Laravel, các thành viên cần kiểm tra những điều sau trước khi bắt đầu:

1. **Bật XAMPP:** Khởi động Apache và MySQL trước khi chạy dự án.
2. **Kiểm tra Database:** Đảm bảo đã tạo database đúng tên theo cấu hình trong file `.env`.
3. **Kiểm tra cấu hình `.env`:** Kiểm tra các thông tin `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` và `DB_PORT` có chính xác hay không.
4. **Cài đặt dependencies:** Nếu mới tải project về, chạy lệnh `composer install`.
5. **Chạy migration (nếu cần):** Nếu database chưa có các bảng cần thiết, kiểm tra migration và chạy `php artisan migrate` khi phù hợp.
6. **Khởi động dự án:** Chạy lệnh `php artisan serve` để mở ứng dụng.

### ❌ NẾU GẶP LỖI, HÃY KIỂM TRA

- Đã bật XAMPP và MySQL chưa?
- Đã chọn đúng database chưa?
- Tên database trong file `.env` có khớp với database trong phpMyAdmin không?
- Cổng MySQL (`DB_PORT`) có đúng không? Ví dụ: `3306` hoặc `3307`.
- Database đã có đầy đủ bảng và dữ liệu cần thiết chưa?
- Đã chạy `composer install` chưa?

**📌 LƯU Ý QUAN TRỌNG:** Trước khi báo lỗi trong nhóm, hãy kiểm tra các mục trên và đọc kỹ thông báo lỗi. Không tự ý sửa cấu hình hoặc database chung khi chưa trao đổi với các thành viên khác.

Cảm ơn mọi người đã phối hợp! ❤️