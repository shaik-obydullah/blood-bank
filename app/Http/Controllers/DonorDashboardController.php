<?php

namespace App\Http\Controllers;

use App\Models\BloodGroup;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\BloodInventory;
use Illuminate\Support\Facades\Auth;

class DonorDashboardController extends Controller
{
    public function index()
    {
        $all_blood_groups = BloodGroup::pluck('code')->toArray();
        return view('website.donor.dashboard', compact('all_blood_groups'));
    }

    public function appointments()
    {
        $all_blood_groups = BloodGroup::pluck('code')->toArray();
        $appointments = Appointment::where('fk_donor_id', Auth::guard('donor')->id())
            ->orderBy('appointment_time', 'desc')
            ->get();

        $pending_appointments = Appointment::where('status', 'Pending')
            ->where('appointment_time', '>', now())
            ->get();

        return view('website.donor.appointments', compact('all_blood_groups', 'appointments', 'pending_appointments'));
    }

 public function storeAppointment(Request $request)
{
    $donor = Auth::guard('donor')->user();

    $appointment = Appointment::find($request->appointment_id);
    
    if ($appointment) {
        $appointment->update([
            'fk_donor_id' => $donor->id,
            'status' => 'Confirmed'
        ]);
        
        return redirect('/donor-appointment')
            ->with('success', 'Appointment requested successfully!');
    }
    
    return redirect('/donor-appointment')
        ->with('error', 'Appointment not found.');
}

    public function history()
    {
        $all_blood_groups = BloodGroup::pluck('code')->toArray();
        $donations = BloodInventory::where('fk_donor_id', Auth::guard('donor')->id())
            ->with('bloodGroup')
            ->orderBy('collection_date', 'desc')
            ->get();

        return view('website.donor.history', compact('all_blood_groups', 'donations'));
    }

    public function profile()
    {
        $all_blood_groups = BloodGroup::pluck('code')->toArray();
        $donor = Auth::guard('donor')->user()->load('bloodGroup');
        return view('website.donor.profile', compact('all_blood_groups', 'donor'));
    }

    public function logout()
    {
        Auth::guard('donor')->logout();
        return redirect('/donor-login');
    }
}