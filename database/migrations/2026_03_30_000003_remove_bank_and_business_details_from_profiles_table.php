<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'pan_no',
                'pan_card_file',
                'aadhar_no',
                'aadhar_card_file',
                'business_type',
                'shop_establishment_file',
                'coi_file',
                'partnership_deed_file',
                'bank_account_holder',
                'bank_account_number',
                'bank_name',
                'ifsc_code',
                'bank_proof_file',
                'gst_number',
                'gst_certificate',
                'voter_id_passport_file',
                'utility_bill_file',
                'rent_lease_agreement_file',
                'company_website',
                'support_email',
                'support_number_1',
                'support_number_2',
                'company_address',
                'transport_license_file',
                'transport_license_number',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Business Registration fields
            $table->string('business_type')->nullable();
            $table->string('shop_establishment_file')->nullable();
            $table->string('coi_file')->nullable();
            $table->string('partnership_deed_file')->nullable();

            // Bank Account Details
            $table->string('bank_account_holder')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_proof_file')->nullable();

            // GST Details
            $table->string('gst_number')->nullable();
            $table->string('gst_certificate')->nullable();

            // Supporting Documents
            $table->string('voter_id_passport_file')->nullable();
            $table->string('utility_bill_file')->nullable();
            $table->string('rent_lease_agreement_file')->nullable();

            // Company Details
            $table->string('company_website')->nullable();
            $table->string('support_email')->nullable();
            $table->string('support_number_1')->nullable();
            $table->string('support_number_2')->nullable();
            $table->text('company_address')->nullable();

            // Transport Business Details
            $table->string('transport_license_file')->nullable();
            $table->string('transport_license_number')->nullable();
            
            // PAN and Aadhar
            $table->string('pan_no')->nullable();
            $table->string('pan_card_file')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('aadhar_card_file')->nullable();
        });
    }
};
