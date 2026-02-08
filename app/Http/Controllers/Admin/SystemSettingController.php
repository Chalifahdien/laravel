<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingController extends Controller
{
    public function edit()
    {
        $settings = SystemSetting::query()->firstOrCreate([], [
            'system_name' => 'Photobooth',
        ]);

        return view('admin.settings.system', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = SystemSetting::query()->firstOrCreate([], [
            'system_name' => 'Photobooth',
        ]);

        $validated = $request->validate([
            'system_name' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico,jpg,jpeg,webp', 'max:1024'],
        ]);

        $settings->system_name = $validated['system_name'];

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = $request->file('logo')->store('system', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $settings->favicon_path = $request->file('favicon')->store('system', 'public');
        }

        $settings->save();

        return back()->with('success', 'System settings berhasil disimpan.');
    }
}

