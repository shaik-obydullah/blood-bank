<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\BloodGroup;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index()
    {
        $query = Donor::with('bloodGroup');

        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $sort = request('sort', 'recent_donors');
        switch ($sort) {
            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;
            case 'id_desc':
                $query->orderBy('id', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'eligible_first':
                $query->orderByRaw('
                CASE 
                    WHEN last_donation_date IS NULL THEN 1
                    WHEN last_donation_date < ? THEN 1
                    ELSE 2
                END ASC
            ', [now()->subMonths(3)->format('Y-m-d')]);
                break;
            default:
                $query->orderBy('last_donation_date', 'desc');
                break;
        }

        $donors = $query->paginate(20);
        return view('donors.index', compact('donors'));
    }

    /**
     * Show the form for creating a new donor.
     */
    public function create()
    {
        $bloodGroups = BloodGroup::all();
        return view('donors.create', compact('bloodGroups'));
    }

    /**
     * Store a newly created donor in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'country' => 'required|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'last_donation_date' => 'nullable|date|before_or_equal:today',
            'hemoglobin_level' => 'nullable|numeric|between:0,20',
            'systolic' => 'nullable|integer|between:70,200',
            'diastolic' => 'nullable|integer|between:40,130',
            'fk_blood_group_id' => 'nullable|exists:blood_groups,id'
        ]);

        try {
            // Create donor
            $donor = Donor::create($validated);

            // Return to donors list with success message
            return redirect()->route('donors.index')
                ->with('success', 'Donor created successfully!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to create donor: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified donor.
     */
    public function show($id)
    {
        $donor = Donor::with('bloodGroup')->findOrFail($id);
        $eligibility = $donor->getEligibilityStatus();

        return view('donors.show', compact('donor', 'eligibility'));
    }

    /**
     * Show the form for editing the specified donor.
     */
    public function edit($id)
    {
        $donor = Donor::findOrFail($id);
        $bloodGroups = BloodGroup::all();

        return view('donors.edit', compact('donor', 'bloodGroups'));
    }

    /**
     * Update the specified donor in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'country' => 'required|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'last_donation_date' => 'nullable|date|before_or_equal:today',
            'hemoglobin_level' => 'nullable|numeric|between:0,20',
            'systolic' => 'nullable|integer|between:70,200',
            'diastolic' => 'nullable|integer|between:40,130',
            'fk_blood_group_id' => 'nullable|exists:blood_groups,id'
        ]);

        $donor = Donor::findOrFail($id);
        $donor->update($validated);

        return redirect()->route('donors.index')
            ->with('success', 'Donor updated successfully!');
    }

    /**
     * Remove the specified donor from storage.
     */
    public function destroy($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->delete();

        return redirect()->route('donors.index')
            ->with('success', 'Donor deleted successfully!');
    }

    /**
     * Search donors by name, mobile, or email.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $donors = Donor::where('name', 'like', "%{$search}%")
            ->orWhere('mobile', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->with('bloodGroup')
            ->paginate(20);

        return view('donors.index', compact('donors', 'search'));
    }
}