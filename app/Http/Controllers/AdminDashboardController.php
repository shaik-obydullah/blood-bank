<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\BloodDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get pending blood distributions with related data
        $pendingDistributions = BloodDistribution::pending()
            ->with(['patient', 'bloodGroup'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Return view with data
        return view('dashboard.content', [
            'distributions' => $pendingDistributions,
            'pendingCount' => $pendingDistributions->count()
        ]);
    }

    public function logout()
    {
        Session::flush();
        return redirect('/admin-login');
    }

    /**
     * Show the form for creating a new donor.
     */
    public function create()
    {
        return view('donors.create');
    }

    /**
     * Store a newly created donor in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'country' => 'nullable|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'last_donation_date' => 'nullable|date',
        ]);

        try {
            // Create donor using Eloquent
            $donor = Donor::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Donor saved successfully!',
                'data' => $donor
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save donor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified donor.
     */
    public function show($id)
    {
        $donor = Donor::findOrFail($id);
        return response()->json($donor);
    }

    /**
     * Show the form for editing the specified donor.
     */
    public function edit($id)
    {
        $donor = Donor::findOrFail($id);
        return view('donors.edit', compact('donor'));
    }

    /**
     * Update the specified donor in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'country' => 'nullable|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'last_donation_date' => 'nullable|date',
        ]);

        $donor = Donor::findOrFail($id);
        $donor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Donor updated successfully!',
            'data' => $donor
        ]);
    }

    /**
     * Remove the specified donor from storage.
     */
    public function destroy($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Donor deleted successfully!'
        ]);
    }

    /**
     * Search donors by name or mobile.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $donors = Donor::where('name', 'like', "%{$search}%")
            ->orWhere('mobile', 'like', "%{$search}%")
            ->get();

        return response()->json($donors);
    }
}