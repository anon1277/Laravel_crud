<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Administrator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{

    //


    public function view_register_page() {
        return view('administrators.register');
     }
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Administrator::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('admin.login')->with('success', 'Registration successful. Please log in.');
    }


     //method to display a user login page

     public function view_login_page() {
         return view('administrators.login');
      }

      //method login action

      public function login(Request $request)
      {
          $credentials = $request->validate([
              'email' => 'required|email',
              'password' => 'required',
          ]);

        //   dd($credentials);
    //    dd(Auth::attempt($credentials));


    if (Auth::guard('Administrator')->attempt($credentials)) {
        // dd(1);
       $ses = $request->session()->regenerate();

        return redirect()->route('admin1.index');
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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $employees = User::select(['id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at']);
        // dd($employees);
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

        return view('administrators.index');
    }

    /**
     * Show the form for creating a new administrator.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrators.create');
    }

    /**
     * Store a newly created administrator in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $administrator = Administrator::create($request->all());

        return redirect()->route('administrators.index')->with('success', 'Administrator created successfully');
    }

    /**
     * Display the specified administrator.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function show(Administrator $administrator)
    {
        return view('administrators.show', compact('administrator'));
    }

    /**
     * Show the form for editing the specified administrator.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function edit(Administrator $administrator)
    {
        return view('administrators.edit', compact('administrator'));
    }

    /**
     * Update the specified administrator in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Administrator $administrator)
    {
        $administrator->update($request->all());

        return redirect()->route('administrators.index')->with('success', 'Administrator updated successfully');
    }

    /**
     * Remove the specified administrator from storage.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Administrator $administrator)
    {
        $administrator->delete();

        return redirect()->route('administrators.index')->with('success', 'Administrator deleted successfully');
    }
}
