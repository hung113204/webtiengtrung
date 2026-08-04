# Xây dựng Module Quản lý Tính năng nổi bật (Features)

Module này sẽ chuyển đổi nội dung giới thiệu tính năng từ tĩnh (hardcode HTML) thành động, cho phép Admin dễ dàng thêm, sửa, xóa, và thay đổi thứ tự các khối Tính năng thông qua giao diện quản trị.

## User Review Required

Vui lòng xem qua cấu trúc bảng CSDL dưới đây, đặc biệt là các trường dữ liệu được thiết kế để khớp 100% với giao diện hiện tại của bạn. Nếu bạn muốn bỏ hoặc thêm trường nào, hãy phản hồi lại cho tôi biết.

## Cấu trúc Database Đề xuất (Bảng `tinh_nangs`)

Để hiển thị giống hệt các khối Feature bạn đã cắt HTML, bảng này cần các trường sau:

- `id`: Khóa chính
- `tieu_de` (string): Tiêu đề chính (VD: "Thuật toán Spaced Repetition")
- `badge_text` (string): Chữ hiển thị trong huy hiệu nhỏ (VD: "Học từ vựng siêu tốc")
- `mo_ta` (text): Đoạn văn bản mô tả tính năng
- `danh_sach_bullet` (json): Danh sách các gạch đầu dòng (Mảng các chuỗi, giới hạn khoảng 3 mục)
- `image_url` (string, nullable): Đường dẫn hình ảnh minh họa
- `vi_tri_anh` (enum): `left` hoặc `right` (Để thiết kế so le: chữ bên trái, ảnh bên phải và ngược lại)
- `stat_number` (string, nullable): Con số thống kê nổi bật (VD: "+200%")
- `stat_label` (string, nullable): Dòng chữ nhỏ dưới số thống kê (VD: "Tốc độ ghi nhớ từ")
- `stat_icon` (text, nullable): Mã SVG của icon thống kê
- `button_text` (string, nullable): Chữ trên nút bấm (VD: "Thử Flashcard ngay")
- `button_link` (string, nullable): Đường dẫn khi click nút
- `thu_tu` (integer): Dùng để sắp xếp (1, 2, 3...)
- `trang_thai` (boolean): 1 = Hiển thị, 0 = Ẩn

## Proposed Changes

### Database & Model
#### [NEW] `database/migrations/xxxx_xx_xx_xxxxxx_create_tinh_nangs_table.php`
- Tạo file migration với các cột như trên.

#### [NEW] `app/Models/TinhNang.php`
- Khai báo model với thuộc tính `$fillable` và ép kiểu `$casts` mảng `danh_sach_bullet` thành kiểu array.

### Admin Controller & Views
#### [NEW] `app/Http/Controllers/Admin/TinhNangController.php`
- Các hàm CRUD cơ bản: `index`, `create`, `store`, `edit`, `update`, `destroy`.

#### [NEW] `resources/views/admin/tinhnang/index.blade.php`
- Danh sách quản lý các tính năng dạng bảng. Có nút Bật/Tắt trạng thái.

#### [NEW] `resources/views/admin/tinhnang/create.blade.php` & `edit.blade.php`
- Form nhập liệu cho tất cả các thông tin phong phú của một tính năng. (Bao gồm ô input để gõ các gạch đầu dòng, mã SVG...).

### Cập nhật Frontend
#### [MODIFY] `app/Http/Controllers/Frontend/TinhNangClientController.php`
- Truy vấn `TinhNang::where('trang_thai', 1)->orderBy('thu_tu')->get()` và truyền biến `$tinhNangs` sang View.

#### [MODIFY] `resources/views/frontend/tinhnangclient/index.blade.php`
- Thay thế 4 khối tính năng tĩnh bằng vòng lặp `@foreach($tinhNangs as $index => $item)`.
- Xử lý điều kiện `$item->vi_tri_anh` để quyết định gắn class `order-1`/`order-2` cho việc hiển thị so le trái - phải.

#### [MODIFY] `routes/web.php`
- Thêm Admin route resource: `Route::resource('tinh-nang', TinhNangController::class);`

#### [MODIFY] `resources/views/admin/layouts/sidebar.blade.php`
- Thêm đường dẫn vào menu bên trái của khu vực Admin cho trang Quản lý Tính năng.

## Verification Plan
1. Chạy Artisan Migrate để tạo bảng.
2. Seed (Hoặc nhập tay) lại 4 tính năng cũ vào DB từ trang Admin.
3. Kiểm tra trang Admin có cho phép upload ảnh và sửa nội dung JSON trơn tru hay không.
4. Tải lại trang Tính năng của Client để xác minh hiển thị vòng lặp đúng chuẩn HTML.
