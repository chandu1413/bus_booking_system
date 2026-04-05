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
        Schema::table('operators', function (Blueprint $table) {
            // KYC Business Details
            $table->string('pan_no')->nullable()->after('registration_number');
            $table->string('pan_card_file')->nullable()->after('pan_no');
            $table->string('aadhar_no')->nullable()->after('pan_card_file');
            $table->string('aadhar_card_file')->nullable()->after('aadhar_no');
            
            // Business Registration
            $table->string('business_type')->nullable()->after('aadhar_card_file');
            $table->string('shop_establishment_file')->nullable()->after('business_type');
            $table->string('coi_file')->nullable()->after('shop_establishment_file');
            $table->string('partnership_deed_file')->nullable()->after('coi_file');
            
            // Bank Account Details
            $table->string('mobile_no')->nullable()->after('partnership_deed_file');
            $table->string('bank_account_holder')->nullable()->after('mobile_no');
            $table->string('bank_account_number')->nullable()->after('bank_account_holder');
            $table->string('bank_name')->nullable()->after('bank_account_number');
            $table->string('ifsc_code')->nullable()->after('bank_name');
            $table->string('bank_proof_file')->nullable()->after('ifsc_code');
            
            // GST Details
            $table->string('gst_number')->nullable()->after('bank_proof_file');
            $table->string('gst_certificate')->nullable()->after('gst_number');
            
            // Supporting Documents
            $table->string('voter_id_passport_file')->nullable()->after('gst_certificate');
            $table->string('utility_bill_file')->nullable()->after('voter_id_passport_file');
            $table->string('rent_lease_agreement_file')->nullable()->after('utility_bill_file');
            
            // Company Details
            $table->string('company_website')->nullable()->after('rent_lease_agreement_file');
            $table->string('support_email')->nullable()->after('company_website');
            $table->string('support_number_1')->nullable()->after('support_email');
            $table->string('support_number_2')->nullable()->after('support_number_1');
            $table->text('company_address')->nullable()->after('support_number_2');
            
            // Transport Business Details
            $table->string('transport_license_file')->nullable()->after('company_address');
            $table->string('transport_license_number')->nullable()->after('transport_license_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operators', function (Blueprint $table) {
            $table->dropColumn([
                'pan_no',
                'pan_card_file',
                'aadhar_no',
                'aadhar_card_file',
                'business_type',
                'shop_establishment_file',
                'coi_file',
                'partnership_deed_file',
                'mobile_no',
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
};
