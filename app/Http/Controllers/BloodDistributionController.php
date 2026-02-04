<?php

namespace App\Http\Controllers;

use App\Models\BloodDistribution;
use App\Models\Patient;
use App\Models\BloodGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BloodDistributionController extends Controller
{
    /**
     * Display a listing of blood distributions.
     */
    public function index(Request $request)
    {
        $query = BloodDistribution::with(['patient', 'bloodGroup']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('bloodGroup', function ($bgQuery) use ($search) {
                    $bgQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            switch ($request->status) {
                case 'pending':
                    $query->whereNull('approved_unit');
                    break;
                case 'approved':
                    $query->whereNotNull('approved_unit')->where('approved_unit', '>', 0);
                    break;
                case 'rejected':
                    $query->whereNotNull('approved_unit')->where('approved_unit', 0);
                    break;
            }
        }

        // Patient filter
        if ($request->has('patient_id') && !empty($request->patient_id)) {
            $query->where('fk_patient_id', $request->patient_id);
        }

        // Blood Group filter
        if ($request->has('blood_group_id') && !empty($request->blood_group_id)) {
            $query->where('fk_blood_group_id', $request->blood_group_id);
        }

        // Date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'request_asc':
                    $query->orderBy('request_unit', 'asc');
                    break;
                case 'request_desc':
                    $query->orderBy('request_unit', 'desc');
                    break;
                case 'id_desc':
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $distributions = $query->paginate(10)->appends($request->query());
        
        // Get patients and blood groups for filter dropdowns
        $patients = Patient::orderBy('name')->get();
        $bloodGroups = BloodGroup::orderBy('name')->get();

        // Get statistics
        $stats = BloodDistribution::getStatistics();

        return view('blood-distributions.index', compact('distributions', 'patients', 'bloodGroups', 'stats'));
    }

    /**
     * Show the form for creating a new blood distribution.
     */
    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $bloodGroups = BloodGroup::orderBy('name')->get();
        
        return view('blood-distributions.create', compact('patients', 'bloodGroups'));
    }

    /**
     * Store a newly created blood distribution in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fk_patient_id' => 'required|exists:patients,id',
            'fk_blood_group_id' => 'required|exists:blood_groups,id',
            'request_unit' => 'required|integer|min:1',
        ]);

        BloodDistribution::create($validated);

        return redirect()->route('blood-distributions.index')
            ->with('success', 'Blood distribution request created successfully!');
    }

    /**
     * Display the specified blood distribution.
     */
    public function show(BloodDistribution $bloodDistribution)
    {
        $bloodDistribution->load(['patient', 'bloodGroup']);
        
        return view('blood-distributions.show', compact('bloodDistribution'));
    }

    /**
     * Show the form for editing the specified blood distribution.
     */
    public function edit(BloodDistribution $bloodDistribution)
    {
        $patients = Patient::orderBy('name')->get();
        $bloodGroups = BloodGroup::orderBy('name')->get();
        
        return view('blood-distributions.edit', compact('bloodDistribution', 'patients', 'bloodGroups'));
    }

    /**
     * Update the specified blood distribution in storage.
     */
    public function update(Request $request, BloodDistribution $bloodDistribution)
    {
        $validated = $request->validate([
            'fk_patient_id' => 'required|exists:patients,id',
            'fk_blood_group_id' => 'required|exists:blood_groups,id',
            'request_unit' => 'required|integer|min:1',
            'approved_unit' => 'nullable|integer|min:0|lte:request_unit',
        ]);

        $bloodDistribution->update($validated);

        return redirect()->route('blood-distributions.index')
            ->with('success', 'Blood distribution updated successfully.');
    }

    /**
     * Remove the specified blood distribution from storage.
     */
    public function destroy(BloodDistribution $bloodDistribution)
    {
        $bloodDistribution->delete();

        return redirect()->route('blood-distributions.index')
            ->with('success', 'Blood distribution deleted successfully.');
    }

    /**
     * Approve blood distribution.
     */
    public function approve(Request $request, BloodDistribution $bloodDistribution)
    {
        $request->validate([
            'approved_unit' => 'required|integer|min:1|max:' . $bloodDistribution->request_unit,
        ]);

        $bloodDistribution->approve($request->approved_unit);

        return redirect()->route('blood-distributions.index')
            ->with('success', 'Blood distribution approved successfully.');
    }

    /**
     * Reject blood distribution.
     */
    public function reject(BloodDistribution $bloodDistribution)
    {
        $bloodDistribution->reject();

        return redirect()->route('blood-distributions.index')
            ->with('success', 'Blood distribution rejected successfully.');
    }

    /**
     * Get statistics page.
     */
    public function statistics()
    {
        $stats = BloodDistribution::getStatistics();
        
        // Get monthly distribution data
        $monthlyData = BloodDistribution::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(request_unit) as total_requested'),
            DB::raw('SUM(approved_unit) as total_approved'),
            DB::raw('COUNT(*) as total_requests')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        // Get top requested blood groups
        $topBloodGroups = BloodDistribution::select(
            'fk_blood_group_id',
            DB::raw('COUNT(*) as request_count'),
            DB::raw('SUM(request_unit) as total_requested'),
            DB::raw('SUM(approved_unit) as total_approved')
        )
        ->with('bloodGroup')
        ->groupBy('fk_blood_group_id')
        ->orderBy('request_count', 'desc')
        ->limit(10)
        ->get();

        // Get status distribution
        $statusData = [
            'pending' => BloodDistribution::whereNull('approved_unit')->count(),
            'approved' => BloodDistribution::whereNotNull('approved_unit')->where('approved_unit', '>', 0)->count(),
            'rejected' => BloodDistribution::whereNotNull('approved_unit')->where('approved_unit', 0)->count(),
        ];

        return view('blood-distributions.statistics', compact('stats', 'monthlyData', 'topBloodGroups', 'statusData'));
    }
}