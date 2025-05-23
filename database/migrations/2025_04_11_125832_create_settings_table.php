<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $this->seedDefaultSettings();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }

    /**
     * Seed default settings
     */
    private function seedDefaultSettings()
    {
        $settings = [
            ['name' => 'language', 'value' => 'en'],
            ['name' => 'currency', 'value' => 'dollar'],
            ['name' => 'enable_registration', 'value' => 'true'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
