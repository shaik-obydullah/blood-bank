<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\BloodInventory;
use App\Models\BloodGroup;
use App\Models\Appointment;
use App\Models\BloodDistribution;
use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $totalDonors = Donor::count();
        $activeAppointments = Appointment::whereIn('status', ['Confirmed', 'Pending'])->count();
        $bloodQuantity = BloodInventory::where('expiry_date', '>', now())->sum('quantity');
        $totalGroup = BloodGroup::count();
        $all_blood_groups = BloodGroup::pluck('code')->toArray();
        return view('website.home', compact('totalDonors', 'activeAppointments', 'bloodQuantity', 'totalGroup', 'all_blood_groups'));
    }

    public function searchBlood(Request $request)
    {
        $all_blood_groups = BloodGroup::pluck('code')->toArray();
        $bloodType = strtoupper(trim($request->input('blood_type')));

        $validTypes = $all_blood_groups;

        if (!$bloodType || !in_array($bloodType, $validTypes)) {
            return view('website.blood-search-results', [
                'error' => 'Invalid blood type. Please enter a valid blood type (A+, A-, B+, B-, AB+, AB-, O+, O-).',
                'searchedType' => $bloodType
            ]);
        }

        $bloodGroup = BloodGroup::where('code', $bloodType)->first();

        if (!$bloodGroup) {
            return view('website.blood-search-results', [
                'error' => 'Blood type not found in our system.',
                'searchedType' => $bloodType
            ]);
        }

        $inventory = BloodInventory::where('fk_blood_group_id', $bloodGroup->id)
            ->where('quantity', '>', 0)
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date', 'asc')
            ->get(['id', 'quantity', 'collection_date', 'expiry_date']);


        $totalQuantity = $inventory->sum('quantity');

        return view('website.blood-search-results', [
            'inventory' => $inventory,
            'bloodType' => $bloodType,
            'totalQuantity' => $totalQuantity,
            'bloodGroup' => $bloodGroup,
            'all_blood_groups' => $all_blood_groups,
        ]);
    }

    public function save_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inventory_id' => 'required|exists:blood_inventory,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'patient_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:patients,email',
            'address' => 'nullable|string|max:255',
            'request_ml' => 'required|integer|min:250',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $inventory = BloodInventory::find($validated['inventory_id']);

        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Blood inventory not found'
            ], 404);
        }

        if ($validated['request_ml'] > $inventory->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Requested amount exceeds available quantity'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $patient = Patient::create([
                'name' => $validated['patient_name'],
                'email' => $validated['email'],
                'address' => $validated['address'],
            ]);

            $bloodGroup = BloodGroup::find($validated['blood_group_id']);

            if (!$bloodGroup) {
                throw new \Exception('Blood group not found');
            }

            $bloodDistribution = BloodDistribution::create([
                'fk_patient_id' => $patient->id,
                'fk_blood_group_id' => $validated['blood_group_id'],
                'request_unit' => $validated['request_ml'],
            ]);

            $inventory->decrement('quantity', $validated['request_ml']);

            if ($inventory->quantity <= 0) {
                // Optional: Add status field to blood_inventories table
                // $inventory->update(['status' => 'depleted']);
            }

            DB::commit();

            // Send email notification (optional)
            // $this->sendRequestNotification($patient, $bloodDistribution, $bloodGroup);

            return response()->json([
                'success' => true,
                'message' => 'Blood request submitted successfully! We will contact you soon.',
                'data' => [
                    'patient_id' => $patient->id,
                    'distribution_id' => $bloodDistribution->id,
                    'patient_name' => $patient->name,
                    'blood_type' => $bloodGroup->code,
                    'requested_amount' => $validated['request_ml']
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Blood request failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to process request. Please try again.'
            ], 500);
        }
    }

    public function donor_registration()
    {
        $all_blood_groups = BloodGroup::pluck('code')->toArray();
        $bloodGroups = BloodGroup::orderBy('code')->get(['id', 'name', 'code']);
        return view('website.donor_registration', compact('all_blood_groups', 'bloodGroups'));
    }

    public function save_donor_registration(Request $request)
    {
        // Validate the request - this will automatically return JSON errors for AJAX
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'country' => 'required|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:20',
            'last_donation_date' => 'nullable|date|before_or_equal:today',
            'email' => 'required|email|max:100',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'hemoglobin_level' => 'nullable|numeric|between:0,20',
            'systolic' => 'nullable|integer|between:70,200',
            'diastolic' => 'nullable|integer|between:40,130',
            'fk_blood_group_id' => 'nullable|exists:blood_groups,id'
        ]);

        try {
            // Create the donor
            $donor = Donor::create($validated);

            // Check eligibility
            $eligibilityStatus = $donor->getEligibilityStatus();

            // Always return JSON since your JavaScript expects it
            return response()->json([
                'success' => true,
                'message' => 'Donor registered successfully!',
                'data' => [
                    'id' => $donor->id,
                    'name' => $donor->name,
                    'eligibility' => $eligibilityStatus
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Donor registration error: ' . $e->getMessage());

            // Return error as JSON
            return response()->json([
                'success' => false,
                'message' => 'Error registering donor. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }












    public function store_donor(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'country' => 'required|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:20',
            'last_donation_date' => 'nullable|date|before_or_equal:today',
            'email' => 'required|email|max:100',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'hemoglobin_level' => 'nullable|numeric|between:0,20',
            'systolic' => 'nullable|integer|between:70,200',
            'diastolic' => 'nullable|integer|between:40,130',
            'fk_blood_group_id' => 'nullable|exists:blood_groups,id'
        ]);

        try {
            // Create the donor
            $donor = Donor::create($validated);

            // Check eligibility
            $eligibilityStatus = $donor->getEligibilityStatus();

            // Return response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Donor registered successfully!',
                    'data' => [
                        'id' => $donor->id,
                        'name' => $donor->name,
                        'eligibility' => $eligibilityStatus
                    ]
                ]);
            }

            return redirect()->route('donor.registration')
                ->with([
                    'success' => 'Donor registered successfully! Donor ID: ' . $donor->id,
                    'eligibility' => $eligibilityStatus
                ]);

        } catch (\Exception $e) {
            \Log::error('Donor registration error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error registering donor. Please try again.',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return back()
                ->withErrors(['error' => 'Error registering donor. Please try again.'])
                ->withInput();
        }
    }

    // Additional method to view donors list
    public function donors_list()
    {
        $donors = Donor::with('bloodGroup')
            ->latest()
            ->paginate(20);

        $bloodGroups = BloodGroup::all();

        return view('website.donors_list', compact('donors', 'bloodGroups'));
    }

    // Method to show donor details
    public function donor_details($id)
    {
        $donor = Donor::with('bloodGroup')->findOrFail($id);
        $eligibility = $donor->getEligibilityStatus();

        return view('website.donor_details', compact('donor', 'eligibility'));
    }
}