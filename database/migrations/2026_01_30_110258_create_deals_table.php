<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();

            // --- Project & Classification (Foreign Keys) ---
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('project_type_id')->nullable()->constrained('project_types')->nullOnDelete();
            $table->foreignId('purpose_id')->nullable()->constrained('purposes')->nullOnDelete();
            $table->foreignId('purpose_type_id')->nullable()->constrained('purpose_types')->nullOnDelete();
            
            // --- General Deal Info ---
            $table->string('developer_name')->nullable();
            $table->date('deal_date')->nullable();
            $table->string('invoice_no')->nullable();
            
            // Source (Foreign Key)
            $table->foreignId('source_id')->nullable()->constrained('sources')->nullOnDelete();

            // --- Client Info ---
            $table->string('client_name')->nullable();
            $table->string('client_mobile_no')->nullable();
            $table->string('client_email')->nullable();

            // --- Financials (Price & Company Commission) ---
            $table->decimal('price', 15, 2)->nullable(); // Total Price
            $table->decimal('commission_percentage', 5, 2)->nullable(); // e.g. 2.00%
            $table->decimal('commission_amount', 15, 2)->nullable();    // Actual value
            
            // --- VAT Info ---
            $table->decimal('vat_percentage', 5, 2)->nullable(); // "VAT"
            $table->decimal('vat_amount', 15, 2)->nullable();
            $table->boolean('vat_paid')->default(0); // Checkbox for "vat paid"
            $table->decimal('total_invoice', 15, 2)->nullable();

            // --- Down Payment ---
            $table->decimal('down_payment_percentage', 5, 2)->nullable();
            $table->decimal('down_payment_amount', 15, 2)->nullable();
            $table->decimal('remaining_down_payment', 15, 2)->nullable();

            // --- Internal Commissions (Agents & Leaders) ---
            
            // Agent
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('agent_commission_percentage', 5, 2)->nullable();
            $table->decimal('agent_commission_amount', 15, 2)->nullable();

            // Leader
            $table->foreignId('leader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('leader_commission_percentage', 5, 2)->nullable();
            $table->decimal('leader_commission_amount', 15, 2)->nullable();

            // Sales Director
            $table->foreignId('sales_director_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('sales_director_commission_percentage', 5, 2)->nullable();
            $table->decimal('sales_director_commission_amount', 15, 2)->nullable();

            // --- Status & Notes ---
            // Links to the DealStatus table you created earlier
            $table->foreignId('deal_status_id')->nullable()->constrained('deal_statuses')->nullOnDelete();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};