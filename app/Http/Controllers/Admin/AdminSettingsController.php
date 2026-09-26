<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        // Handle file uploads for branding
        $fileKeys = ['site_logo', 'site_favicon', 'hero_banner', 'site_watermark'];
        foreach ($fileKeys as $fileKey) {
            if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
                $file = $request->file($fileKey);
                $ext = strtolower($file->getClientOriginalExtension());
                
                // Allow safe web images
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                    $fileName = $fileKey . '_' . time() . '_' . Str::random(4) . '.' . $ext;
                    $destPath = public_path('uploads/site');
                    if (!file_exists($destPath)) {
                        mkdir($destPath, 0755, true);
                    }
                    $file->move($destPath, $fileName);
                    
                    SiteSetting::updateOrCreate(
                        ['key' => $fileKey],
                        [
                            'value' => 'uploads/site/' . $fileName,
                            'group' => 'branding',
                        ]
                    );
                }
                unset($inputs[$fileKey]);
            }
        }

        foreach ($inputs as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'group' => $this->determineGroup($key),
                ]
            );
        }

        AdminActivityLog::log(
            auth()->id(),
            'update',
            'settings',
            null,
            "Updated website site settings and media"
        );

        return redirect()->route('admin.settings.index')->with('success', "Website settings and branding updated successfully.");
    }

    private function determineGroup($key)
    {
        if (str_starts_with($key, 'contact_') || str_starts_with($key, 'phone') || str_starts_with($key, 'email') || str_starts_with($key, 'address')) {
            return 'contact';
        }
        if (str_starts_with($key, 'social_')) {
            return 'social';
        }
        if (str_starts_with($key, 'booking_')) {
            return 'booking';
        }
        return 'general';
    }
}
