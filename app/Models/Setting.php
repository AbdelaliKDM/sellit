<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value'];

    /**
     * Get a setting value by name
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        $setting = self::where('name', $name)->first();

        if ($setting) {
            return $setting->value;
        }

        return $default;
    }

    /**
     * Set a setting value
     *
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public static function set($name, $value)
    {
        $setting = self::firstOrNew(['name' => $name]);
        $setting->value = $value;
        return $setting->save();
    }
}
