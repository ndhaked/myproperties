<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\AdaiTutorial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(Request $request): View
    {
        // Only allow ADAI admin to access settings
        if (!$request->user()->hasRole('adai_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $user = $request->user();

        // Get or create settings (single record for admin)
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            ['notification_email' => $user->email ?? '']
        );

        return view('settings.index', [
            'setting' => $setting,
            'user' => $user,
        ]);
    }

    /**
     * Update the settings.
     */
    public function update(Request $request): JsonResponse|RedirectResponse
    {
        // Only allow ADAI admin to access settings
        if (!$request->user()->hasRole('adai_admin')) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $validated = $request->validate([
                'notification_email' => ['required', 'email', 'max:255'],
            ], [
                'notification_email.required' => 'Notification email is required.',
                'notification_email.email' => 'Please enter a valid email address.',
            ]);

            $user = $request->user();

            DB::beginTransaction();

            // Get or create settings (single record for admin)
            $setting = Setting::firstOrCreate(
                ['id' => 1],
                ['notification_email' => $user->email ?? '']
            );

            // Update notification email
            $setting->notification_email = $validated['notification_email'];
            $setting->save();

            DB::commit();

            Log::info('Settings updated successfully', [
                'user_id' => $user->id,
                'notification_email' => $setting->notification_email,
            ]);

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status_code' => 200,
                    'type' => 'success',
                    'error' => false,
                    'message' => 'Settings updated successfully.',
                    'reload' => true
                ]);
            }

            return redirect()
                ->route('settings.index')
                ->with('success', 'Settings updated successfully.');

        } catch (ValidationException $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status_code' => 422,
                    'type' => 'error',
                    'error' => true,
                    'message' => 'Validation failed. Please check the form fields.',
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()
                ->route('settings.index')
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Settings update failed', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status_code' => 500,
                    'type' => 'error',
                    'error' => true,
                    'message' => 'Settings update failed. Please try again.',
                ], 500);
            }

            return redirect()
                ->route('settings.index')
                ->with('error', 'Settings update failed. Please try again.');
        }
    }

    /**
     * Display the Help & Support page.
     */
    public function helpSupport(Request $request): View
    {
        // Use support@adai.art as per requirements
        $supportEmail = 'nirbhay@yopmail.com';
        $adaiTutorials = AdaiTutorial::where('status',1)->get();
        return view('help-support.index', [
            'supportEmail' => $supportEmail,
            'adaiTutorials' => $adaiTutorials,
        ]);
    }
}
