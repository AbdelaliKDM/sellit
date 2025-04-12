<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = [
            'app_name' => Setting::get('app_name', 'Sellit'),
            'language' => Setting::get('language', 'en'),
            'currency' => Setting::get('currency', 'dollar'),
            'enable_registration' => Setting::get('enable_registration', 'true'),
        ];

        return view('content.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'language' => 'required|in:en,ar,fr',
            'currency' => 'required|in:dollar,euro,dzd',
            'enable_registration' => 'required|in:true,false',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        // If language changed, update the session locale
        if ($request->has('language')) {
            app()->setLocale($validated['language']);
            session()->put('locale', $validated['language']);
        }

        return redirect()->route('settings.index')
            ->with('success', __('app.settings_updated'));
    }
}
