<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
     //index method to show the employee list

     public function index(Request $request)
     {
         if ($request->ajax()) {
             $employees = Employee::select(['id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at']);
             return DataTables::of($employees)
             ->addColumn('created_at', function ($employee) {
                return Carbon::parse($employee->created_at)->format('d-m-Y');
            })
            ->addColumn('updated_at', function ($employee) {
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

         return view('employee.index');
     }

     //method to show the register form
    public function view_register_page() {
        return view('employee.register');
     }

     //method to  perform the register action
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

     //method to display a user login page

     public function view_login_page() {
         return view('employee.login');
      }

      //method to perform login o action

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

     //method to logout
    public function logout(Request $request)
     {
         Auth::logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();

         return redirect('/');
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
        // Hash the password
        $password = Hash::make($validatedData['password']);

        // Assign the authenticated administrator's ID to the employee
        $validatedData['administrator_id'] = Auth::user()->id;

        // Create a new employee
        $employee = Employee::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => $password,
            'administrator_id' => $validatedData['administrator_id'],
        ]);

        //  dd($employee);
        // Return a response indicating success
        return redirect()->route('employees.index')->with('success','Employee created successfully');
        // return response()->json(['message' => 'Employee created successfully', 'employee' => $employee]);
    }
     //show the edit form
    public function show($id)
    {
        $employee = Employee::find($id);
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
        $employee = Employee::findOrFail($id);

        // Update the employee
        $employee->update($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Employee updated successfully', 'employee' => $employee]);
    }

     public function destroy($id)
     {
        // Find the employee by ID
        $employee = Employee::findOrFail($id);

        // Delete the employee
        $employee->delete();

        // Return a response indicating success
        return response()->json(['message' => 'Employee deleted successfully']);
     }
}
