<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use App\Http\Controllers\Controller;

class GeneralSettingsController extends Controller
{
    public function show(GeneralSettings $settings)
    {
        return view('settings.show', [
            'restaurant_name' => $settings->restaurant_name,
            'opens_at' => $settings->opens_at,
            'closes_at' => $settings->closes_at,
            'tables' => $settings->tables,
            'intervals' => $settings->intervals,
        ]);
    }
}
