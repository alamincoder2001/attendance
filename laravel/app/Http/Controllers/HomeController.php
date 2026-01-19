<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = null)
    {
        if ($id) {
            Device::query()->update(['status' => 'p']);
            session()->forget('device');
            $device = Device::find($id);
            $device->status = 'a';
            $device->save();
            session(['device' => (object) $device]);
        }

        $data['employees'] = getEmployee(session('device')->ipAddress ?? null);
        return view('dashboard', $data);
    }

    public function deviceDashboard()
    {
        return view('device_dashboard');
    }


    // Company Profile
    public function companyProfile(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = CompanyProfile::first();
            $dataKeys = $request->except('_token');
            $data->fill($dataKeys);
            $data->update();
            return redirect()->back()->with('success', 'Company profile updated successfully');
        } else {
            return view('company_profile');
        }
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully');
    }
}
