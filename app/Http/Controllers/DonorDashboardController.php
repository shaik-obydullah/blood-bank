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

        return view('website.donor.appointments', compact('all_blood_groups', 'appointments'));
    }

    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'appointment_datetime' => 'required|date|after:now'
        ]);

        $donor = Auth::guard('donor')->user();
        $eligibility = $donor->getEligibilityStatus();

        if (!$eligibility['eligible']) {
            return redirect()->route('donor.appointments')
                ->with('error', 'You are not eligible to donate at this time. Please check your profile for details.')
                ->withInput();
        }

        $appointmentDate = \Carbon\Carbon::parse($validated['appointment_datetime'])->format('Y-m-d');
        $existingAppointment = Appointment::where('fk_donor_id', $donor->id)
            ->whereDate('appointment_time', $appointmentDate)
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->first();

        if ($existingAppointment) {
            return redirect()->route('donor.appointments')
                ->with('error', 'You already have an appointment scheduled for this date.')
                ->withInput();
        }

        $lastAppointment = Appointment::where('fk_donor_id', $donor->id)
            ->where('status', '!=', 'Cancelled')
            ->where('appointment_time', '>', now())
            ->orderBy('appointment_time', 'desc')
            ->first();

        if ($lastAppointment) {
            $minDate = $lastAppointment->appointment_time->addDays(7);
            if (\Carbon\Carbon::parse($validated['appointment_datetime'])->lt($minDate)) {
                return redirect('/donor-appointment')
                    ->with('error', 'You must wait at least 7 days between appointments.')
                    ->withInput();
            }
        }

        try {
            Appointment::create([
                'fk_donor_id' => $donor->id,
                'appointment_time' => $validated['appointment_datetime'],
                'status' => 'Pending'
            ]);

            return redirect('/donor-appointment')
                ->with('success', 'Appointment booked successfully for ' .
                    \Carbon\Carbon::parse($validated['appointment_datetime'])->format('F j, Y \a\t g:i A'));

        } catch (\Exception $e) {
            return redirect('/donor-appointment')
                ->with('error', 'Failed to book appointment. Please try again.')
                ->withInput();
        }
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