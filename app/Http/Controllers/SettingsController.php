<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdateRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Update the setting via ajax
     */
    public function updateAjax(UpdateRequest $request): JsonResponse
    {
        $setting = Setting::where('key', $request->get('key'))->first();

        /**
         * create the setting if it doesn't exist
         */
        if (empty($setting)) {
            Setting::create([
                'key' => $request->get('key'),
                'value' => $request->get('value'),
            ]);
        } else {
            $setting->update([
                'value' => $request->get('value'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Setting updated successfully',
        ]);
    }
}
