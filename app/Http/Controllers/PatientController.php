<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'email_asc':
                    $query->orderBy('email', 'asc');
                    break;
                case 'email_desc':
                    $query->orderBy('email', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('last_blood_taking_date', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('last_blood_taking_date', 'desc');
                    break;
                case 'id_desc':
                    $query->orderBy('id', 'desc');
                    break;
                case 'id_asc':
                default:
                    $query->orderBy('id', 'asc');
            }
        } else {
            $query->orderBy('id', 'asc');
        }

        $patients = $query->paginate(10)->appends($request->query());
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'id' => 'required|integer|unique:patients,id',
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:100|unique:patients,email',
        'password' => 'nullable|string|min:6',
        'medical_history' => 'nullable|string',
        'address' => 'nullable|string|max:255',
        'last_blood_taking_date' => 'nullable|date',
    ]);

    // No need to manually hash password - model mutator will handle it
    Patient::create($validated);

    return redirect()->route('patients.index')
        ->with('success', 'Patient created successfully!');
}


    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

public function update(Request $request, Patient $patient)
{
    $validated = $request->validate([
        'id' => 'required|integer|unique:patients,id,' . $patient->id,
        'name' => 'required|string|max:100',
        'email' => [
            'required',
            'email',
            'max:100',
            Rule::unique('patients')->ignore($patient->id),
        ],
        'password' => 'nullable|string|min:6',
        'medical_history' => 'nullable|string',
        'address' => 'nullable|string|max:255',
        'last_blood_taking_date' => 'nullable|date',
    ]);

    // Update all fields including password - model mutator will hash it if provided
    $patient->update($validated);

    return redirect()->route('patients.index')
        ->with('success', 'Patient updated successfully.');
}

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}