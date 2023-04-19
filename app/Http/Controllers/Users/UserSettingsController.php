<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserSettings\UpdateRequest;
use App\Http\Resources\User\Settings\UserSettingsResource;
use App\Models\Setting;

class UserSettingsController extends Controller
{
    public function index(): UserSettingsResource
    {
        return new UserSettingsResource(auth()->user()->settings()->first());
    }

    public function update(UpdateRequest $request)
    {
        $settings = $request->validated()['settings'];
        $settings['value'] = json_encode($settings['value'], true);

        $setting = Setting::where([
            'model' => $settings['model'],
            'key' => $settings['key']
        ])->first();

        if ($setting) {
            $setting->update(['value' => $settings['value']]);
        }
    }
}
