<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDealRequest extends FormRequest
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
            // --- Client Info ---
            'client_name'      => 'required|string|max:150',
            'client_mobile_no' => 'required|string|max:50',
            'client_email'     => 'nullable|email|max:150',
            
            // --- Project & Classification ---
            'project_id'      => 'nullable|integer|exists:projects,id',
            'project_type_id' => 'nullable|integer|exists:project_types,id',
            'purpose_id'      => 'required|integer|exists:purposes,id', // Marked required based on your HTML asterisk
            'purpose_type_id' => 'required|integer|exists:purpose_types,id', // Marked required based on your HTML asterisk
            
            // --- General Deal Info ---
            'developer_name' => 'nullable|string|max:150',
            'deal_date'      => 'nullable|date',
            'invoice_no'     => 'nullable|string|max:100',
            'source_id'      => 'nullable|integer|exists:sources,id',
            'medium_id'      => 'nullable|integer|exists:mediums,id',
            
            // --- Financials ---
            'price'                 => 'nullable|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_amount'     => 'nullable|numeric|min:0',
            
            // --- VAT ---
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'vat_amount'     => 'nullable|numeric|min:0',
            'vat_paid'       => 'nullable|boolean',
            'total_invoice'  => 'nullable|numeric|min:0',
            
            // --- Down Payment ---
            'down_payment_percentage' => 'nullable|numeric|min:0|max:100',
            'down_payment_amount'     => 'nullable|numeric|min:0',
            'remaining_down_payment'  => 'nullable|numeric|min:0',
            
            // --- Internal Commissions (Agents) ---
            'agent_id'                    => 'nullable|integer|exists:users,id',
            'agent_commission_percentage' => 'nullable|numeric|min:0|max:100',
            'agent_commission_amount'     => 'nullable|numeric|min:0',

            'leader_id'                    => 'nullable|integer|exists:users,id',
            'leader_commission_percentage' => 'nullable|numeric|min:0|max:100',
            'leader_commission_amount'     => 'nullable|numeric|min:0',

            'sales_director_id'                    => 'nullable|integer|exists:users,id',
            'sales_director_commission_percentage' => 'nullable|numeric|min:0|max:100',
            'sales_director_commission_amount'     => 'nullable|numeric|min:0',
            
            // --- Status & Misc ---
            'deal_status_id'   => 'nullable|integer|exists:deal_statuses,id',
            'lead_category_id' => 'nullable|integer|exists:lead_categories,id',
            'budget'           => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
            
            // Location (if you added these to Deals as well)
            //'country' => 'nullable|string|max:100',
            //'city'    => 'required|string|max:100',
            //'campaign'=> 'nullable|string|max:150',
        ];
    }

    /**
     * Custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'client_name'       => 'client name',
            'client_mobile_no'  => 'client mobile number',
            'project_id'        => 'project',
            'project_type_id'   => 'project type',
            'purpose_id'        => 'purpose',
            'purpose_type_id'   => 'purpose type',
            'source_id'         => 'source',
            'medium_id'         => 'medium',
            'agent_id'          => 'agent',
            'leader_id'         => 'leader',
            'sales_director_id' => 'sales director',
            'deal_status_id'    => 'deal status',
            'vat_paid'          => 'VAT paid status',
        ];
    }
}