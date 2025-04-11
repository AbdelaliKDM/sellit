<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    /**
     * Get a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Get application name
     *
     * @return string
     */
    public static function getAppName()
    {
        return Setting::get('app_name', 'Sellit');
    }

    /**
     * Check if registration is enabled
     *
     * @return bool
     */
    public static function isRegistrationEnabled()
    {
        return Setting::get('enable_registration', 'true') === 'true';
    }

    /**
     * Get currency symbol
     *
     * @return string
     */
    public static function getCurrencySymbol()
    {
        $currency = Setting::get('currency', 'dollar');

        switch ($currency) {
            case 'euro':
                return '€';
            case 'dzd':
                // Return Arabic symbol if app locale is Arabic, otherwise return Latin
                return app()->getLocale() === 'ar' ? 'دج' : 'DZD';
            case 'dollar':
            default:
                return '$';
        }
    }
}
