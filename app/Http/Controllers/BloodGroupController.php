<?php

namespace App\Http\Controllers;

use App\Models\BloodGroup;
use Illuminate\Http\Request;

class BloodGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodGroup::query();
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'code_asc':
                    $query->orderBy('code', 'asc');
                    break;
                case 'code_desc':
                    $query->orderBy('code', 'desc');
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

        $bloodGroups = $query->paginate(10)->appends($request->query());
        return view('blood-group.index', compact('bloodGroups'));
    }

    public function create()
    {
        return view('blood-group.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20|unique:blood_groups,name',
            'code' => 'required|string|max:15|unique:blood_groups,code',
            'description' => 'nullable|string',
        ]);

        BloodGroup::create($validated);

        return redirect()->route('blood-groups.index')
            ->with('success', 'Blood group created successfully!');
    }

    public function show(BloodGroup $bloodGroup)
    {
        $donorsCount = $bloodGroup->donors()->count();
        $distributionsCount = $bloodGroup->distributions()->count();

        return view('blood-groups.show', compact('bloodGroup', 'donorsCount', 'distributionsCount'));
    }

    public function edit(BloodGroup $bloodGroup)
    {
        return view('blood-group.edit', compact('bloodGroup'));
    }

    public function update(Request $request, BloodGroup $bloodGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20|unique:blood_groups,name,' . $bloodGroup->id,
            'code' => 'required|string|max:15|unique:blood_groups,code,' . $bloodGroup->id,
            'description' => 'nullable|string',
        ]);

        $bloodGroup->update($validated);

        return redirect()->route('blood-groups.index')
            ->with('success', 'Blood group updated successfully.');
    }

    public function destroy(BloodGroup $bloodGroup)
    {
        if ($bloodGroup->donors()->exists()) {
            return redirect()->route('blood-groups.index')
                ->with('error', 'Cannot delete blood group. It is being used by donors.');
        }

        $bloodGroup->delete();

        return redirect()->route('blood-groups.index')
            ->with('success', 'Blood group deleted successfully.');
    }
}