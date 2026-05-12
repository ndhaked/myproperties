<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact; // Imports your existing model
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming AJAX data
        $validator = Validator::make($request->all(), [
            'contact_name'    => 'required|string|max:255',
            'contact_email'   => 'required|email',
            'contact_phone'   => 'required',
            'contact_message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error', 
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Save using your existing UUID Model
        // We map the form fields (left) to your database columns (right)
        Contact::create([
            'full_name' => $request->contact_name, // Maps to 'full_name'
            'email'     => $request->contact_email,
            'phone'     => $request->contact_phone,
            'subject'   => 'Conatct Us',
            'message'   => $request->contact_message,
            // 'phone', 'subject', 'country_id' are left null as they aren't in this specific form
        ]);

        // 3. Return JSON response for the AJAX script
        return response()->json([
            'success' => true, 
            'message' => 'Success! Thanks for contacting us!'
        ]);
    }
}