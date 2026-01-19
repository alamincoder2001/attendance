<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //
    }

    public function create()
    {
        $data['devices'] = Device::orderBy('id', 'asc')->withTrashed()->get();
        return view('device.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'ipAddress' => 'required|ip',
        ]);
        try {
            $data = new Device();
            $dataKeys = $request->except('_token');
            $data->fill($dataKeys);
            $data->save();
            return response()->json(['success' => true, 'message' => 'Device added successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:devices,id',
            'name' => 'required|string|max:255',
            'ipAddress' => 'required|ip',
        ]);
        try {
            $data = Device::where('id', $request->id)->withTrashed()->first();
            $dataKeys = $request->except('_token', 'id');
            $data->fill($dataKeys);
            $data->save();
            return response()->json(['success' => true, 'message' => 'Device updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:devices,id',
        ]);

        try {
            $data = Device::where('id', $request->id)->first();
            if (!empty($data)) {
                $data->deleted_by = auth()->user()->id;
                $data->status = 'd';
                $data->save();
                $data->delete();
            } else {
                Device::where('id', $request->id)->withTrashed()->first()->forceDelete();
            }
            return response()->json(['success' => true, 'message' => 'Device deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Something went wrong']);
        }
    }
}
