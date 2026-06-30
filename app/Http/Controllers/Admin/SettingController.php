<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $settings = $this->service->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name'        => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'currency'         => 'nullable|string|max:10',
            'timezone'         => 'nullable|string|max:100',
            'tax_rate'         => 'nullable|numeric|min:0|max:100',
            'shipping_fee'     => 'nullable|numeric|min:0',
            'order_prefix'     => 'nullable|string|max:20',
            'mail_from_name'   => 'nullable|string|max:255',
            'mail_from_address'=> 'nullable|email|max:255',
        ]);

        $this->service->bulkUpdate($validated);

        return back()->with('success', 'Settings saved successfully.');
    }
}
