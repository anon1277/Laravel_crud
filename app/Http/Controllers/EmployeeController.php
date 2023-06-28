<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::select(['id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at']);
            return DataTables::of($employees)
                ->editColumn('created_at', function ($employee) {
                    return Carbon::parse($employee->created_at)->format('d-m-Y');
                })
                ->editColumn('updated_at', function ($employee) {
                    return Carbon::parse($employee->updated_at)->format('d-m-Y');
                })
                ->addColumn('action', function ($employee) {
                    return '
                        <button class="btn btn-sm btn-primary edit-btn" data-id="'.$employee->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$employee->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $categories = Category::all();
        return view('employee.index' , compact('categories'));
    }

    /**
     * Display the registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function view_register_page()
    {
        return view('employee.register');
    }

    /**
     * Handle the registration action.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Employee::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('admin.login')->with('success', 'Registration successful. Please log in.');
    }

    /**
     * Display the login form For Employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function view_login_page()
    {
        return view('employee.login');
    }

    /**
     * Handle the login action of Employee Login.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('employee')->attempt($credentials)) {
            $ses = $request->session()->regenerate();
            return redirect()->route('employees.index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Logout the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
        /**
     * Store a new resource in the database.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $admin = Auth::user(); // Get the authenticated admin

        // Create a new employee and associate it with the admin
        $employee = $admin->employees()->create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        if ($employee) {
            // Employee created successfully
            return response()->json(['success' => true, 'message' => 'Employee created successfully'], Response::HTTP_OK);
        } else {
            // Failed to create employee
            return response()->json(['success' => false, 'message' => 'Failed to create employee'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Display the specified employee.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['error' => 'Employee not found.'], 404);
        }
        return response()->json(['employee' => $employee]);
    }
        /**
     * Update the specified employee in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $password = Hash::make($validatedData['password']);

        // Find the employee by ID
        $employee = Employee::findOrFail($id);

        // Update the employee with the validated data
        $employee->update($validatedData);

        // Return a JSON response with success message and updated employee data
        return response()->json(['success' => true, 'message' => 'Employee updated successfully', 'employee' => $employee]);
    }

    /**
     * Remove the specified employee from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the employee by ID.
        $employee = Employee::findOrFail($id);

        // Delete the employee.
        $employee->delete();

        // Return a success response.
        return response()->json(['message' => 'Employee deleted successfully']);
    }
}
