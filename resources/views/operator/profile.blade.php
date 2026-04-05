@extends('layouts.operator_welcome')
@section('title', 'Profile')
@section('page-title', 'Operator Profile')
@section('breadcrumb')
    {!! '<a href="' . route('operator.dashboard') .
        '" class="text-indigo-600 hover:underline">Dashboard</a> / Profile' !!}
@endsection
@section('content')
    <div class="max-w-2xlx">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('operator.update.profile', $operator) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <h4 class="text-lg font-medium text-gray-900 mb-4">Basic Details</h4>
                <div class="grid grid-cols-1 gap-5">
                    <div class="grid grid-cols-3  gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name"  placeholder="e.g. John Doe " value="{{ old('name', $operator?->user->name) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror" required
                                readonly required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $operator->user->email) }}" required
                                placeholder="e.g. john.doe@example.com"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror" readonly>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="mobile_no"  required
                                placeholder="e.g. 9876543210" value="{{ old('mobile_no', $operator?->profile?->phone) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('mobile_no') border-red-500 @enderror">
                            @error('mobile_no')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                    </div>
 
                    
                    <div class="mt-5">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">KYC Details</h4>
                        <div class="grid grid-cols-4 grid-sm-cols-2 gap-5 ">
                            <div class="">
                                <x-input-pan-card name="pan_no" label="PAN Card" :value="old('pan_no', $operator?->profile?->pan_no)" :required="true" />
                            </div>
                            <div>
                                <x-input-file-upload name="pan_card_file" id="pan_card_file" label="Upload PAN Card"
                                    :value="old('pan_card_file')" inputClass="border p-2 w-full rounded"
                                    accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                            <div class="">
                                <x-input-aadhar-no name="aadhar_no" id="aadhar_no" label="Aadhaar Number" :value="old('aadhar_no', $operator?->profile?->aadhar_no)"
                                    inputClass="border p-2 w-full rounded" />
                            </div>
                            <div>
                                <x-input-file-upload name="aadhar_card_file" id="aadhar_card_file"
                                    label="Upload Aadhaar Card" :value="old('aadhar_card_file')" inputClass="border p-2 w-full rounded"
                                    accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                        </div>
                    </div>



                    <!-- Business Registration Proof Section -->
                    <div class="mt-5">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Business Registration Proof</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Type <span
                                        class="text-red-500">*</span></label>
                                <select name="business_type" id="business_type"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Select Business Type</option>
                                    <option value="individual"
                                        {{ old('business_type', $operator->business_type) === 'individual' ? 'selected' : '' }}>Individual/Proprietor
                                    </option>
                                    <option value="company" {{ old('business_type', $operator->business_type) === 'company' ? 'selected' : '' }}>
                                        Company</option>
                                    <option value="partnership"
                                        {{ old('business_type', $operator->business_type) === 'partnership' ? 'selected' : '' }}>Partnership</option>
                                </select>
                            </div>
                            <div class="">
                                <div id="individual-docs" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Shop & Establishment
                                        Certificate / Udyam Registration <span class="text-gray-500 text-xs">(For
                                            Individuals)</span></label>
                                    <x-input-file-upload name="shop_establishment_file" id="shop_establishment_file"
                                          :value="old('shop_establishment_file')" inputClass="border p-2 w-full rounded"
                                        accept=".pdf,.jpg,.jpeg,.png" />
                                </div>
                                <div id="company-docs" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Certificate of Incorporation
                                        (CoI) <span class="text-gray-500 text-xs">(For Companies)</span></label>
                                    <x-input-file-upload name="coi_file" id="coi_file"   :value="old('coi_file')"
                                        inputClass="border p-2 w-full rounded" accept=".pdf,.jpg,.jpeg,.png" />
                                </div>
                                <div id="partnership-docs" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Partnership Deed <span
                                            class="text-gray-500 text-xs">(For Partnerships)</span></label>
                                    <x-input-file-upload name="partnership_deed_file" id="partnership_deed_file"
                                        :value="old('partnership_deed_file')" inputClass="border p-2 w-full rounded"
                                        accept=".pdf,.jpg,.jpeg,.png" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Bank Account Proof Section -->
                    <div class="mt-5">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Bank Account Proof <span
                                class="text-red-500">*</span></h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account Holder Name</label>
                                <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $operator->bank_account_holder) }}"
                                    placeholder="Account holder name" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('bank_account_holder')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account Number</label>
                                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $operator->bank_account_number) }}"
                                    placeholder="Account number" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('bank_account_number')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $operator->bank_name) }}"
                                    placeholder="Bank name" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('bank_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">IFSC Code</label>
                                <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $operator->ifsc_code) }}"
                                    placeholder="IFSC code" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('ifsc_code')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cancelled Cheque / Bank
                                    Passbook <span class="text-red-500">*</span></label>
                                <x-input-file-upload name="bank_proof_file" id="bank_proof_file"
                                  :value="old('bank_proof_file', $operator->bank_proof_file)"
                                    inputClass="border p-2 w-full rounded" accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                        </div>
                    </div>

                    <!-- GST Certificate Section -->
                    <div class="mt-5">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">GST Certificate <span
                                class="text-gray-500 text-xs">(If Registered Taxpayer)</span></h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GST Number</label>
                                <input type="text" name="gst_number" value="{{ old('gst_number', $operator->gst_number) }}"
                                    placeholder="GST number"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('gst_number')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror

                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GST Document </label>
                                <x-input-file-upload name="gst_certificate" id="gst_certificate" 
                                    :value="old('gst_certificate')" inputClass="border p-2 w-full rounded"
                                    accept=".pdf,.jpg,.jpeg,.png" />
                            </div>

                        </div>
                    </div>

                    <!-- Optional Supporting Documents -->
                    <div class="mt-5">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Optional Supporting Documents</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Voter ID / Passport <span
                                        class="text-gray-500 text-xs">(Alternative ID Proof)</span></label>
                                <x-input-file-upload name="voter_id_passport_file" id="voter_id_passport_file"
                                     :value="old('voter_id_passport_file')" inputClass="border p-2 w-full rounded"
                                    accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Utility Bill <span
                                        class="text-gray-500 text-xs">(Not older than 3 months)</span></label>
                                <x-input-file-upload name="utility_bill_file" id="utility_bill_file"
                                     :value="old('utility_bill_file')" inputClass="border p-2 w-full rounded"
                                    accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rent/Lease Agreement <span
                                        class="text-gray-500 text-xs">(If Rented Premises)</span></label>
                                <x-input-file-upload name="rent_lease_agreement_file" id="rent_lease_agreement_file"
                                     :value="old('rent_lease_agreement_file')" inputClass="border p-2 w-full rounded"
                                    accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                        </div>
                    </div>

                    <!-- Company Details -->
                    <div class="mt-5">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Company Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Company Website</label>
                                <input type="url" name="company_website" value="{{ old('company_website', $operator->company_website) }}"
                                    placeholder="https://example.com"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('company_website')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Support Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="support_email" value="{{ old('support_email', $operator->support_email) }}"
                                    placeholder="support@example.com" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('support_email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Support Number 1 <span
                                        class="text-red-500">*</span></label>
                                <input type="tel" name="support_number_1" value="{{ old('support_number_1', $operator->support_number_1) }}"
                                    placeholder="+91 XXXXX XXXXX" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('support_number_1')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror

                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Support Number 2 <span
                                        class="text-red-500">*</span></label>
                                <input type="tel" name="support_number_2" value="{{ old('support_number_2', $operator->support_number_2) }}"
                                    placeholder="+91 XXXXX XXXXX" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                    @error('support_number_2')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>
                                            <div class="grid grid-cold-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Address</label>
                        <textarea name="company_address" placeholder="e.g. 123 Main Street"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 @error('company_address') border-red-500 @enderror">{{ old('company_address', $operator->company_address) }}</textarea>
                        @error('company_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                    </div>
                    </div>

                    <!-- Transport Certifications & Details -->
                    <div class="mt-5">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Transport Business Certifications</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                               
                                <x-input-file-upload name="transport_license_file" id="transport_license_file"
                                    label="Transport License" labelClass="block text-sm font-medium text-gray-700 mb-1" :value="old('transport_license_file')"
                                     inputClass="border p-2 w-full border border-gray-300 rounded-lg" 
                                    accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Transport License Number <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="transport_license_number"
                                    value="{{ old('transport_license_number', $operator->transport_license_number) }}" placeholder="e.g. DL01AB1234" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                                     @error('transport_license_number')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>
                        {{-- <p class="text-xs text-gray-500 mt-2">Other vehicle details will be added when registering
                            vehicles.</p> --}}
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-100">
                    <button type="reset"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">
                        Reset
                    </button>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium">
                       Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Function to show/hide business documents based on selection
                function toggleBusinessDocs() {
                    const businessType = $('#business_type').val();

                    // Hide all document sections
                    $('#individual-docs, #company-docs, #partnership-docs').hide();

                    // Show the appropriate section based on selection
                    if (businessType === 'individual') {
                        $('#individual-docs').show();
                    } else if (businessType === 'company') {
                        $('#company-docs').show();
                    } else if (businessType === 'partnership') {
                        $('#partnership-docs').show();
                    }
                }

                // Trigger on dropdown change
                $('#business_type').on('change', function() {
                    toggleBusinessDocs();
                });

                // Trigger on page load to show correct section based on old data
                toggleBusinessDocs();
            });
        </script>
    @endpush
@endsection
