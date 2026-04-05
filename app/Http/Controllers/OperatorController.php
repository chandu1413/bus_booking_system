<?php

namespace App\Http\Controllers;

use App\Enums\OperatorStatus;
use App\Models\Operator;
use App\Models\User;
use App\Services\OperatorService;
use App\Services\UploadFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OperatorController extends Controller
{
    public $uploadFileService;
    public $operatorService;

    public function __construct(UploadFileService $uploadFileService, OperatorService $operatorService)
    {
        // $this->middleware(['auth', 'role:Operator']);
        $this->uploadFileService = $uploadFileService;
        $this->operatorService = $operatorService;
    }

    public function dashboard()
    { 

        if (Auth::user()->hasRole('Operator')) {
            $operator = Auth::user()->operator;
 
            if ($operator->status->isPending()) {
                return redirect()->route('operator.profile', $operator)
                    ->with('error', 'Your account is pending approval. Please complete your profile and wait for admin approval.');
            }
            
            if ($operator->status->isSuspended()) {
                return redirect()->route('operator.profile.message', $operator)
                    ->with('error', 'Your account has been suspended. Please contact support for more information.');
            }
            
            if ($operator->status->isSubmitted()) {
                return redirect()->route('operator.profile.message', $operator)
                    ->with('error', 'Your account has been submitted for review. Please contact support for more information.');
            }

             $operator->load('user');

            return view('operator.dashboard');
        }
        abort(403, 'Unauthorized');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $operators = User::role('Operator')->with('roles', 'operator')->orderBy('id', 'asc');
            // dd($operators);
            return DataTables::of($operators)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    $avatar = '<img src="' . $row->avatar_url . '" class="avatar w-9 h-9 rounded-full">';
                    $name = '<p class="name font-medium text-gray-800">' . $row->name . '</p><p class="email text-xs text-gray-500">' . $row->email . '</p>';
                    return '<div class="user-info flex items-center space-x-3">' . $avatar . $name . '</div>';
                })
                ->addColumn('role', function ($row) {
                    $roles = '';
                    foreach ($row->roles as $role) {
                        if ($role->name === 'SuperAdmin') $roles .= '<span class="badge role-superadmin bg-red-100 text-red-700 px-2 py-1 rounded-full">' . $role->name . '</span> ';
                        elseif ($role->name === 'Admin') $roles .= '<span class="badge role-admin bg-orange-100 text-orange-700 px-2 py-1 rounded-full">' . $role->name . '</span> ';
                        else $roles .= '<span class="badge role-user bg-blue-100 text-blue-700 px-2 py-1 rounded-full">' . $role->name . '</span> ';
                    }
                    return $roles;
                })
                ->addColumn('status', function ($row) {
                    $status = '';

                    if ($row?->operator?->status === OperatorStatus::APPROVED) {
                        $status = '<span class="badge status-active bg-green-100 text-green-700 px-2 py-1 rounded-full">Approved</span>';
                    }
                    if ($row?->operator?->status === OperatorStatus::SUBMITTED) {
                        $status = '<span class="badge status-inactive bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Submitted</span>';
                    }
                    if ($row?->operator?->status === OperatorStatus::SUSPENDED) {
                        $status = '<span class="badge status-inactive bg-red-100 text-red-700 px-2 py-1 rounded-full">Suspended</span>';
                    }
                    if ($row?->operator?->status === OperatorStatus::PENDING) {
                        $status .= ' <span class="badge status-deleted bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Pending</span>';
                    }

                    return $status;
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login_at?->diffForHumans() ?? 'Never';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.operators.edit', $row?->operator) . '" class="text-indigo-600 hover:text-indigo-800 text-sm mr-3">Edit</a>';
                    if ($row?->operator?->id !== auth()->id()) {
                        $btn .= '<form action="' . route('admin.users.destroy', $row?->operator) . '" method="POST" class="inline" onsubmit="return confirm(\'Deactivate user?\')">'
                            . csrf_field()
                            . method_field('DELETE')
                            . '<button class="text-red-400 hover:text-red-600 text-sm">Block</button></form>';
                    }
                    return $btn;
                })
                ->rawColumns(['user', 'role', 'status', 'action'])
                ->make(true);
        }

        return view('admin.operators.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Operator $operator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operator $operator)
    {
        $operator->load('user');
        $OperatorStatus = OperatorStatus::class;
        return view('admin.operators.edit', compact('operator', 'OperatorStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operator $operator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operator $operator)
    {
        //
    }

    public function profile(Operator $operator)
    {
        $authUser = auth()->user();
        $userOperator = $authUser->operator;

        // Block access if submitted and not owner
        if (
            $userOperator->status === OperatorStatus::SUBMITTED &&
            $authUser->id !== $operator->user_id
        ) {
            return redirect()
                ->route('operator.profile.message', $userOperator)
                ->with('error', 'Your profile is pending approval.');
        }

        // Eager load relationships
        $operator->load(['user', 'profile']);

        // Create profile if not exists (avoid double query)
        $operator->profile()->firstOrCreate([
            'operator_id' => $operator->id,
            'user_id' => $operator->user_id,
        ], ['user_id' => $operator->user_id,]);

        return view('operator.profile', compact('operator'));
    }

    public function updateProfile(Request $request, Operator $operator)
    {
        DB::beginTransaction();


        try {
            // Validate request with custom flash message for failures
            $validated = $request->validate([
                // Basic Details
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $operator->user_id,
                'mobile_no' => 'required|string|max:20',

                // KYC Details
                'pan_no' => ['required', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
                'pan_card_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'aadhar_no' => 'required|string|max:12',
                'aadhar_card_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

                // Business Registration
                'business_type' => 'nullable|in:individual,company,partnership',
                'shop_establishment_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'coi_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'partnership_deed_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

                // Bank Account
                'bank_account_holder' => 'required|string|max:255',
                'bank_account_number' => 'required|string|max:20',
                'bank_name' => 'required|string|max:255',
                'ifsc_code' => 'required|string|max:11',
                'bank_proof_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

                // GST Details
                'gst_number' => 'nullable|string|max:15',
                'gst_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

                // Supporting Documents
                'voter_id_passport_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'utility_bill_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'rent_lease_agreement_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

                // Company Details
                'company_website' => 'nullable|url',
                'support_email' => 'required|email',
                'support_number_1' => 'required|string|max:20',
                'support_number_2' => 'required|string|max:20',
                'company_address' => 'nullable|string|max:500',

                // Transport Business
                'transport_license_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'transport_license_number' => 'nullable|string|max:50',
                'status' => 'nullable|in:submitted,pending,approved,rejected,suspended',
            ]);

            // Load relationships
            $operator->load(['user', 'profile']);

            // Ensure profile exists
            $profile = $operator->profile ?? $operator->profile()->firstOrCreate([
                'operator_id' => $operator->id,
                'user_id' => $operator->user_id,
            ]);

            // Update User Info
            $operator->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (! Auth::user()->hasrole('operator')) {
                $status = $validated['status'];
            }
            // Prepare operator and profile data
            $operatorData = [
                'business_type' => $validated['business_type'] ?? $operator->business_type,
                'bank_account_holder' => $validated['bank_account_holder'],
                'bank_account_number' => $validated['bank_account_number'],
                'bank_name' => $validated['bank_name'],
                'ifsc_code' => $validated['ifsc_code'],
                'gst_number' => $validated['gst_number'] ?? $operator->gst_number,
                'company_website' => $validated['company_website'] ?? $operator->company_website,
                'support_email' => $validated['support_email'],
                'support_number_1' => $validated['support_number_1'],
                'support_number_2' => $validated['support_number_2'],
                'company_address' => $validated['company_address'] ?? $operator->company_address,
                'transport_license_number' => $validated['transport_license_number'] ?? $operator->transport_license_number,
                'status' => $status ?? OperatorStatus::SUBMITTED,
                'is_login_first_time' => 1, // Mark as completed first login after profile update
            ];

            $profileData = [
                'pan_no' => $validated['pan_no'],
                'aadhar_no' => $validated['aadhar_no'],
                'phone' => $validated['mobile_no'],

            ];

            // Handle file uploads
            $operatorFileFields = [
                'shop_establishment_file',
                'coi_file',
                'partnership_deed_file',
                'bank_proof_file',
                'gst_certificate',
                'voter_id_passport_file',
                'utility_bill_file',
                'rent_lease_agreement_file',
                'transport_license_file',
            ];

            $profileFileFields = ['pan_card_file', 'aadhar_card_file'];

            // Upload operator files
            foreach ($operatorFileFields as $field) {
                if ($request->hasFile($field)) {
                    $oldFile = $operator->$field;
                    $operatorData[$field] = $this->uploadFileService->upload(
                        $request->file($field),
                        'operator-documents',
                        $oldFile
                    );
                }
            }

            // Upload profile files
            foreach ($profileFileFields as $field) {
                if ($request->hasFile($field)) {
                    $oldFile = $profile->$field;
                    $profileData[$field] = $this->uploadFileService->upload(
                        $request->file($field),
                        'operator-documents',
                        $oldFile
                    );
                }
            }

            //Update operator & profile
            $operator->update($operatorData);
            $profile->update($profileData);

            DB::commit();

            if (! Auth::user()->hasrole('operator')) {
                return redirect()->route('admin.operators.index')
                    ->with('success', 'Operator profile updated successfully.');
            }

            return redirect()->route('operator.profile.message', ['operator' => $operator->id])
                ->with('success', 'Profile updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors with flash message
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please fix the highlighted errors.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Profile update failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function profileMessage(Operator $operator)
    {
        $status = $operator->status;
        $OperatorStatus = OperatorStatus::class; // Pass enum class to view for comparisons
        return view('operator.profile_message', compact('operator', 'status', 'OperatorStatus'));
    }
}
