<?php

namespace App\Http\Controllers;

use App\Models\BloodInventory;
use App\Models\BloodGroup;
use App\Models\Donor;
use Illuminate\Http\Request;

class BloodInventoryController extends Controller
{
    public function index()
    {
        $query = BloodInventory::query();
        
        // Eager load relationships
        $query->with(['bloodGroup', 'donor']);
        
        // Search
        if(request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                // Search in blood groups
                $q->whereHas('bloodGroup', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
                
                // Search in donors if donor model exists
                if(class_exists(Donor::class)) {
                    $q->orWhereHas('donor', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }
                
                // Search by quantity
                $q->orWhere('quantity', 'like', "%{$search}%");
            });
        }
        
        // Sort
        if(request('sort') == 'id_asc') {
            $query->orderBy('id', 'asc');
        } elseif(request('sort') == 'id_desc') {
            $query->orderBy('id', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }
        
        $inventory = $query->paginate(10);
        
        return view('blood-inventory.index', compact('inventory'));
    }

    public function create()
    {
        $bloodGroups = BloodGroup::all();
        
        // Check if Donor model exists before trying to fetch
        $donors = [];
        if(class_exists(Donor::class)) {
            $donors = Donor::all();
        }
        
        return view('blood-inventory.create', compact('bloodGroups', 'donors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fk_blood_group_id' => 'required|exists:blood_groups,id',
            'fk_donor_id' => 'nullable|exists:donors,id',
            'quantity' => 'required|integer|min:1',
            'collection_date' => 'required|date',
            'expiry_date' => 'required|date|after:collection_date',
        ]);

        BloodInventory::create($request->all());

        return redirect()->route('blood-inventory.index')
            ->with('success', 'Blood inventory added successfully.');
    }

    public function show(BloodInventory $bloodInventory)
    {
        // Load relationships
        $bloodInventory->load(['bloodGroup', 'donor']);
        return view('blood-inventory.show', compact('bloodInventory'));
    }

    public function edit(BloodInventory $bloodInventory)
    {
        $bloodGroups = BloodGroup::all();
        
        // Check if Donor model exists
        $donors = [];
        if(class_exists(Donor::class)) {
            $donors = Donor::all();
        }
        
        return view('blood-inventory.edit', compact('bloodInventory', 'bloodGroups', 'donors'));
    }

    public function update(Request $request, BloodInventory $bloodInventory)
    {
        $request->validate([
            'fk_blood_group_id' => 'required|exists:blood_groups,id',
            'fk_donor_id' => 'nullable|exists:donors,id',
            'quantity' => 'required|integer|min:0',
            'collection_date' => 'required|date',
            'expiry_date' => 'required|date|after:collection_date',
        ]);

        $bloodInventory->update($request->all());

        return redirect()->route('blood-inventory.index')
            ->with('success', 'Blood inventory updated successfully.');
    }

    public function destroy(BloodInventory $bloodInventory)
    {
        $bloodInventory->delete();

        return redirect()->route('blood-inventory.index')
            ->with('success', 'Blood inventory deleted successfully.');
    }
}