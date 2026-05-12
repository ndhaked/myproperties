<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            // Contact Info
            'full_name' => 'required|string|max:150',
            'email'     => 'nullable|email|max:255',
            'mobile'    => 'required|string|max:50',
            'country'   => 'nullable|string|max:100', // Selected from dynamic list
            'city'      => 'nullable|string|max:100',

            // Marketing Info
            'campaign'   => 'nullable|string|max:255',
            'project_id' => 'nullable|integer|exists:projects,id',

            // Classifications
            // Note: 'budget' is a string now (from config), not an ID
            'budget'           => 'nullable|string|max:255', 
            'lead_category_id' => 'nullable|integer|exists:lead_categories,id',

            // Source & Medium
            'source_id' => 'nullable|integer|exists:sources,id',
            'medium_id' => 'nullable|integer|exists:mediums,id',

            // Purpose
            'purpose_id'      => 'nullable|integer|exists:purposes,id',
            'purpose_type_id' => 'nullable|integer|exists:purpose_types,id',

            // Management
            'assigned_agent_id' => 'nullable|integer|exists:users,id',
            'status'            => 'required|string|in:new,contacted,qualified,lost,closed',
        ];
    }

    /**
     * Custom attribute names for validation errors.
     * This makes error messages look nicer (e.g. "The project field is required" instead of "The project id field...")
     */
    public function attributes(): array
    {
        return [
            'project_id'        => 'project',
            'source_id'         => 'source',
            'medium_id'         => 'medium',
            'purpose_id'        => 'purpose',
            'purpose_type_id'   => 'purpose type',
            'lead_category_id'  => 'lead category',
            'assigned_agent_id' => 'assigned agent',
        ];
    }
}