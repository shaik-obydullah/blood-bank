<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['doctor', 'donor']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('doctor', function ($doctorQuery) use ($search) {
                    $doctorQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('donor', function ($donorQuery) use ($search) {
                    $donorQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Doctor filter
        if ($request->has('doctor_id') && !empty($request->doctor_id)) {
            $query->where('fk_doctor_id', $request->doctor_id);
        }

        // Donor filter
        if ($request->has('donor_id') && !empty($request->donor_id)) {
            $query->where('fk_donor_id', $request->donor_id);
        }

        // Date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('appointment_time', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('appointment_time', '<=', $request->date_to);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('appointment_time', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('appointment_time', 'desc');
                    break;
                case 'created_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'created_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'id_desc':
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $appointments = $query->paginate(10)->appends($request->query());
        
        // Get doctors and donors for filter dropdowns (all doctors and donors since no status field)
        $doctors = Doctor::orderBy('name')->get();
        $donors = Donor::orderBy('name')->get();

        return view('appointments.index', compact('appointments', 'doctors', 'donors'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $doctors = Doctor::orderBy('name')->get();
        $donors = Donor::orderBy('name')->get();
        
        return view('appointments.create', compact('doctors', 'donors'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fk_donor_id' => 'required|exists:donors,id',
            'fk_doctor_id' => 'required|exists:doctors,id',
            'appointment_time' => 'required|date',
            'status' => 'required|in:Pending,Confirmed,Cancelled,Completed',
        ]);

        // Create appointment
        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully!');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor', 'donor']);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::orderBy('name')->get();
        $donors = Donor::orderBy('name')->get();
        
        return view('appointments.edit', compact('appointment', 'doctors', 'donors'));
    }

    /**
 * Update the specified appointment in storage.
 */
public function update(Request $request, Appointment $appointment)
{
    // Combine date and time fields if they exist separately
    if ($request->has('appointment_date') && $request->has('appointment_time')) {
        $dateTime = $request->appointment_date . ' ' . $request->appointment_time;
        $request->merge(['appointment_time' => $dateTime]);
    }

    $validated = $request->validate([
        'fk_donor_id' => 'required|exists:donors,id',
        'fk_doctor_id' => 'required|exists:doctors,id',
        'appointment_time' => 'required',
        'status' => 'required|in:Pending,Confirmed,Cancelled,Completed',
        'notes' => 'nullable|string',
    ]);

    $appointment->update($validated);

    return redirect()->route('appointments.index')
        ->with('success', 'Appointment updated successfully.');
}

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Update appointment status.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Cancelled,Completed',
        ]);

        $oldStatus = $appointment->status;
        $newStatus = $request->status;

        $appointment->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Appointment status changed from {$oldStatus} to {$newStatus}.");
    }

  
}