<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Detail Setting
     */
    public function show()
    {
        $setting = Setting::first();

        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }

    /**
     * Update Pengaturan Umum
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email'
        ]);

        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create($validated);
        } else {
            $setting->update($validated);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan umum berhasil diperbarui',
            'data' => $setting
        ]);
    }

    /**
     * Update Carousel
     */
    public function updateCarousel(Request $request)
    {
        $request->validate([
            'carousel_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'carousel_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'carousel_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create([
                'store_name' => 'Toko Beras'
            ]);
        }

        foreach (['carousel_1', 'carousel_2', 'carousel_3'] as $field) {

            if ($request->hasFile($field)) {

                if ($setting->$field) {
                    Storage::disk('public')->delete($setting->$field);
                }

                $path = $request->file($field)
                    ->store('settings/carousel', 'public');

                $setting->$field = $path;
            }
        }

        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Carousel berhasil diperbarui',
            'data' => $setting
        ]);
    }
}