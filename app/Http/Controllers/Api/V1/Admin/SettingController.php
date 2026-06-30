<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json(['data' => $this->service->all()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name'         => 'nullable|string|max:255',
            'site_description'  => 'nullable|string|max:500',
            'currency'          => 'nullable|string|max:10',
            'timezone'          => 'nullable|string|max:100',
            'tax_rate'          => 'nullable|numeric|min:0|max:100',
            'shipping_fee'      => 'nullable|numeric|min:0',
            'order_prefix'      => 'nullable|string|max:20',
            'mail_from_name'    => 'nullable|string|max:255',
            'mail_from_address' => 'nullable|email|max:255',
            // Homepage hero section
            'hero_title'        => 'nullable|string|max:255',
            'hero_subtitle'     => 'nullable|string|max:500',
            'hero_button_text'  => 'nullable|string|max:50',
            'hero_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($request->hasFile('hero_image')) {
            $oldImage = $this->service->get('hero_image');
            if ($oldImage) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldImage));
            }
            $path = $request->file('hero_image')->store('settings', 'public');
            $validated['hero_image'] = '/storage/' . $path;
        } else {
            unset($validated['hero_image']);
        }

        $this->service->bulkUpdate($validated);

        return response()->json([
            'message' => 'Settings saved successfully.',
            'data'    => $this->service->all(),
        ]);
    }
}
