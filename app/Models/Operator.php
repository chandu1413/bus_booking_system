<?php

namespace App\Models;

use App\Enums\OperatorStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'registration_number',
        'status',
        'is_login_first_time',
        // Business Registration
        'business_type',
        'shop_establishment_file',
        'coi_file',
        'partnership_deed_file',
        // Mobile and Bank Details
        'mobile_no',
        'bank_account_holder',
        'bank_account_number',
        'bank_name',
        'ifsc_code',
        'bank_proof_file',
        // GST Details
        'gst_number',
        'gst_certificate',
        // Supporting Documents
        'voter_id_passport_file',
        'utility_bill_file',
        'rent_lease_agreement_file',
        // Company Details
        'company_website',
        'support_email',
        'support_number_1',
        'support_number_2',
        'company_address',
        // Transport Business
        'transport_license_file',
        'transport_license_number',
    ];


    protected $casts = [
        'status' => OperatorStatus::class,
    ];

    // Relationship: belongs to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'operator_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Relationship: has many buses
    public function buses()
    {
        return $this->hasMany(Bus::class);
    }
}
