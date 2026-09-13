<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingKeys = [
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
    ];

    protected $defaults = [
        'company_name' => 'Aetherian Cargo',
        'company_email' => 'Aetheriancargo@gmail.com',
        'company_phone' => '+1 (423) 277-8587',
        'company_address' => 'Aetherian Cargo HQ',
    ];

    public function index()
    {
        $settings = collect($this->settingKeys)->mapWithKeys(function ($key) {
            return [$key => Setting::get($key, $this->defaults[$key] ?? '')];
        });

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($this->settingKeys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings saved.');
    }
}
