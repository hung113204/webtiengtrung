<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CauHinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CauHinhController extends Controller
{
    /**
     * Hiển thị trang cấu hình hệ thống
     */
    public function index()
    {
        $settings = CauHinh::pluck('value', 'key')->all();
        return view('admin.caihinh.index', compact('settings'));
    }

    /**
     * Cập nhật các thông số cấu hình hệ thống
     */
    public function update(Request $request)
    {
        $request->validate([
            'website_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'meta_description' => 'nullable|string',
            'vnpay_tmncode' => 'nullable|string|max:100',
            'vnpay_hashsecret' => 'nullable|string|max:255',
            'vnpay_environment' => 'nullable|string|in:sandbox,production',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|string|max:10',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'khoahoc_page_title' => 'nullable|string|max:255',
            'khoahoc_page_description' => 'nullable|string|max:1000',
            'home_features_title' => 'nullable|string|max:255',
            'home_features_subtitle' => 'nullable|string|max:255',
            'website_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'website_favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,webp|max:5120',
        ]);

        $fields = [
            'website_name',
            'contact_email',
            'meta_description',
            'enable_payment',
            'vnpay_tmncode',
            'vnpay_hashsecret',
            'vnpay_environment',
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_password',
            'recaptcha_site_key',
            'recaptcha_secret_key',
            'require_email_verification',
            'khoahoc_page_title',
            'khoahoc_page_description',
            'home_features_title',
            'home_features_subtitle',
        ];

        foreach ($fields as $field) {
            if ($field === 'enable_payment' || $field === 'require_email_verification') {
                // Switches return 'on' if checked, else not present in request
                $value = $request->has($field) ? '1' : '0';
                CauHinh::setByKey($field, $value);
            } else {
                CauHinh::setByKey($field, $request->input($field));
            }
        }

        // Xử lý upload Logo
        if ($request->hasFile('website_logo')) {
            $logoPath = $request->file('website_logo')->store('settings', 'public');
            CauHinh::setByKey('website_logo', $logoPath);
        } elseif ($request->input('remove_website_logo') == '1') {
            CauHinh::setByKey('website_logo', null);
        }

        // Xử lý upload Favicon
        if ($request->hasFile('website_favicon')) {
            $faviconPath = $request->file('website_favicon')->store('settings', 'public');
            CauHinh::setByKey('website_favicon', $faviconPath);
        } elseif ($request->input('remove_website_favicon') == '1') {
            CauHinh::setByKey('website_favicon', null);
        }

        return redirect()->route('admin.caihinh.index')->with('success', 'Cập nhật cấu hình hệ thống thành công!');
    }
}
