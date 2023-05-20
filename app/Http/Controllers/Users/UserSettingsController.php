<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserSettings\UpdateRequest;
use App\Http\Resources\User\Settings\UserSettingsResource;
use App\Models\Setting;
use Illuminate\Http\Response;

class UserSettingsController extends Controller
{
    public function index(): Response
    {
        $settings = auth()->user()->settings()->first();

        if ($settings) {
            return response([
                'success' => true,
                'data' => new UserSettingsResource($settings)
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'There are no settings yet!'
            ], 204);
        }
    }

    public function update(UpdateRequest $request)
    {
        $settings = $request->validated()['settings'];
        $settings['value'] = json_encode($settings['value'], true);

        $where = [
            'model' => $settings['model'],
            'key' => $settings['key']
        ];

        $setting = auth()->user()->settings()->updateOrCreate($where, ['value' => $settings['value']]);

        if ($setting) {
            return response([
                'success' => true,
                'data' => new UserSettingsResource(auth()->user()->settings()->first()),
                'message' => 'Settings are saved!'
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Something went wrong during saving settings!'
            ], 500);
        }
    }
}
