<?php

namespace App\Http\Middleware;

use App\Helpers\SettingsHelper;
use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;

class ApplySettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Apply language setting
        $language = Setting::get('language', 'en');
        App::setLocale($language);
        Session::put('locale', $language);


        $currencySymbol = SettingsHelper::getCurrencySymbol();
        view()->share('currencySymbol', $currencySymbol);

        return $next($request);
    }

}
