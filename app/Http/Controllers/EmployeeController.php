<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    //index method to show the employee list

    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended($this->intendedUrl());
    }
   //method registers  action

   protected function intendedUrl()
{
    if (session()->has('url.intended')) {
        $url = session('url.intended');
        session()->forget('url.intended');
        return decrypt($url);
    }
}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = User::select(['id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at']);

            return DataTables::of($employees)
                ->addColumn('action', function ($employee) {
                    return '
                        <button class="btn btn-sm btn-info">View</button>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="'.$employee->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$employee->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
                // ->toJson();
        }

        return view('employee.index');
    }

    public function test_index(Request $request)
    {
        if ($request->ajax()) {
            $employees = User::select(['id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at']);

            return DataTables::of($employees)
                ->addColumn('action', function ($employee) {
                    return '
                        <button class="btn btn-sm btn-info">View</button>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="'.$employee->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$employee->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
                // ->toJson();
        }

        return view('employee.test');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        // Create a new employee
        $employee = User::create($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Employee created successfully', 'employee' => $employee]);
    }

    public function show($id)
    {
        $employee = User::find($id);
        if (!$employee) {
        return response()->json(['error' => 'Employee not found.'], 404);
    }

         return response()->json(['employee' => $employee]);
    }

    public function update(Request $request, $id)
    {

        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ]);

        // Find the employee by ID
        $employee = User::findOrFail($id);

        // Update the employee
        $employee->update($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Employee updated successfully', 'employee' => $employee]);
    }

    public function destroy($id)
    {
        // Find the employee by ID
        $employee = User::findOrFail($id);

        // Delete the employee
        $employee->delete();

        // Return a response indicating success
        return response()->json(['message' => 'Employee deleted successfully']);
    }
}
