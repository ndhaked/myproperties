<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Timezone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the account details page.
     */
    public function account(Request $request): View
    {
        $timezones = Timezone::getActive();
        $user = $request->user();

        return view('profile.account', [
            'user' => $user,
            'timezones' => $timezones,
        ]);
    }

    public function update(ProfileUpdateRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

            DB::beginTransaction();

            // 1. Handle profile photo DELETION (User clicked "Remove Photo")
            if ($request->has('delete_profile_photo') && $request->input('delete_profile_photo') == '1') {
                // CHANGE: Check 'azure' disk instead of 'public'
                if ($user->profile_photo && Storage::disk('azure')->exists($user->profile_photo)) {
                    Storage::disk('azure')->delete($user->profile_photo);
                }
                $validated['profile_photo'] = null;
            }

            // 2. Handle profile photo UPLOAD (User selected a new file)
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');

                // Delete old photo from Azure if it exists
                // CHANGE: Check 'azure' disk
                if ($user->profile_photo && Storage::disk('azure')->exists($user->profile_photo)) {
                    Storage::disk('azure')->delete($user->profile_photo);
                }

                // Store new photo in Azure
                // CHANGE: Upload to 'azure' disk
                $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = Storage::disk('azure')->putFileAs('profile_photos', $file, $filename);
                
                $validated['profile_photo'] = $path;

            } elseif ($request->has('profile_photo_filename') && !$request->has('delete_profile_photo')) {
                // Keep existing photo if filename is provided and not deleting
                $validated['profile_photo'] = $request->input('profile_photo_filename');
            }

            // 3. Handle password update
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            // Clean up validated array
            unset($validated['password']);
            unset($validated['current_password']);
            unset($validated['password_confirmation']);

            // Update user profile
            $user->fill($validated);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            DB::commit();

            Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'updated_fields' => array_keys($user->getDirty()),
            ]);

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                // In your controller:
                $baseUrl = config('filesystems.disks.azure.base_url');
               
                return response()->json([
                    'status_code' => 200,
                    'type' => 'success',
                    'error' => false,
                    'message' => 'Profile updated successfully.',
                    //'isDisabledTrue'  => 'true',
                    //'reload' => true,
                    // OPTIONAL: Return the new Azure URL so the UI can update the image instantly
                    'profile_photo_url' => $user->profile_photo ? $baseUrl . $user->profile_photo : null,
                ]);
            }

            return Redirect::route('profile.account')->with('status', 'profile-updated');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Profile update failed', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status_code' => 500,
                    'type' => 'error',
                    'error' => true,
                    'message' => 'Profile update failed. Please try again.',
                ], 500);
            }

            return redirect()
                ->route('profile.account')
                ->with('error', 'Profile update failed. Please try again.');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function updateOld(ProfileUpdateRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

            DB::beginTransaction();

            // Handle profile photo deletion
            if ($request->has('delete_profile_photo') && $request->input('delete_profile_photo') == '1') {
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $validated['profile_photo'] = null;
            }

            // Handle profile photo file upload
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');

                // Delete old photo if exists
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                // Store new photo in local storage (public disk)
                $path = Storage::disk('public')->putFileAs('profile_photos', $file, $user->id . '_' . time() . '.' . $file->getClientOriginalExtension());
                $validated['profile_photo'] = $path;
            } elseif ($request->has('profile_photo_filename') && !$request->has('delete_profile_photo')) {
                // Keep existing photo if filename is provided and not deleting
                $validated['profile_photo'] = $request->input('profile_photo_filename');
            }

            // Handle password update if password fields are provided
            // Validation is already done in ProfileUpdateRequest, so we just need to update if password is present
            if ($request->filled('password')) {
                // Password validation was already done in ProfileUpdateRequest
                // Only update password if it was validated and provided
                $user->password = Hash::make($request->input('password'));
            }

            // Remove password-related fields from validated array if not updating password
            // This prevents trying to set password to null
            unset($validated['password']);
            unset($validated['current_password']);
            unset($validated['password_confirmation']);

            // Update user profile
            $user->fill($validated);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            DB::commit();

            Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'updated_fields' => array_keys($user->getDirty()),
            ]);

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status_code' => 200,
                    'type' => 'success',
                    'error' => false,
                    'message' => 'Profile updated successfully.',
                    'reload' => true
                ]);
            }

            return Redirect::route('profile.account')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Profile update failed', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status_code' => 500,
                    'type' => 'error',
                    'error' => true,
                    'message' => 'Profile update failed. Please try again.',
                ], 500);
            }

            return redirect()
                ->route('profile.account')
                ->with('error', 'Profile update failed. Please try again.');
        }
    }

    /**
     * Upload profile photo.
     */

    public function uploadPhoto(Request $request): JsonResponse
    {
        $request->validate([
            'files' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id'
        ]);

        $user = $request->user();

        // Check if user is authorized
        if ($user->id != $request->input('user_id')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        if ($request->hasFile('files')) {
            
            // 1. Delete old photo from Azure if exists
            // Changed: disk('public') -> disk('azure')
            if ($user->profile_photo && Storage::disk('azure')->exists($user->profile_photo)) {
                Storage::disk('azure')->delete($user->profile_photo);
            }

            $file = $request->file('files');
            
            // Generate a clean filename
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // 2. Upload new photo to Azure
            // Changed: disk('public') -> disk('azure')
            // Note: putFileAs returns the path: "profile_photos/filename.jpg"
            $path = Storage::disk('azure')->putFileAs('profile_photos', $file, $filename);

            $user->profile_photo = $path;
            $user->save();

            // Refresh to get any accessors (if you have them)
            $user->refresh();

            // 3. Generate the full Azure URL for the response
            // This requires the 'url' key we added to config/filesystems.php earlier
            $baseUrl = config('filesystems.disks.azure.base_url');
            $fullUrl =  $path ? $baseUrl . $path : null;
            
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'filename' => $path,
                // Updated to return the Azure URL
                's3FullPath' => $fullUrl, 
                'imageUrl' => $fullUrl,
                'message' => 'Profile photo uploaded successfully.',
                'reload' => false
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No file uploaded.'
        ], 400);
    }

    public function uploadPhotoOld(Request $request): JsonResponse
    {
        $request->validate([
            'files' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id'
        ]);

        $user = $request->user();

        // Check if user is authorized
        if ($user->id != $request->input('user_id')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        if ($request->hasFile('files')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $file = $request->file('files');
            $filename = 'profile_photos/' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('profile_photos', $file, $user->id . '_' . time() . '.' . $file->getClientOriginalExtension());

            $user->profile_photo = $path;
            $user->save();

            // Refresh to get the accessor
            $user->refresh();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'filename' => $path,
                's3FullPath' => $user->profile_photo_url,
                'imageUrl' => $user->profile_photo_url,
                'message' => 'Profile photo uploaded successfully.',
                'reload' => false
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No file uploaded.'
        ], 400);
    }

    /**
     * Delete profile photo.
     */
    public function deletePhoto(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $photoPath = $user->profile_photo;

            // Delete file from Azure storage if it exists
            if ($photoPath) {
                try {
                    // CHANGED: Use 'azure' disk instead of 'public'
                    if (Storage::disk('azure')->exists($photoPath)) {
                        Storage::disk('azure')->delete($photoPath);
                    } else {
                        Log::warning('Profile photo file not found in Azure storage', [
                            'user_id' => $user->id,
                            'photo_path' => $photoPath,
                        ]);
                    }
                } catch (\Exception $storageException) {
                    // Log storage error but continue with database update
                    Log::error('Failed to delete profile photo from Azure Storage', [
                        'user_id' => $user->id,
                        'photo_path' => $photoPath,
                        'error' => $storageException->getMessage(),
                    ]);
                    // Continue with database update even if file deletion fails
                }
            }

            // Set profile_photo to null in database
            $user->profile_photo = null;
            $user->save();

            DB::commit();

            Log::info('Profile photo deleted successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'deleted_photo_path' => $photoPath,
            ]);

            return response()->json([
                'status_code' => 200,
                'type' => 'success',
                'error' => false,
                'message' => 'Profile photo deleted successfully.',
                'reload' => false
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Profile photo deletion failed', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status_code' => 500,
                'type' => 'error',
                'error' => true,
                'message' => 'Failed to delete profile photo. Please try again.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Password updated successfully.',
                'reload' => true
            ]);
        }

        return Redirect::route('profile.account')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
