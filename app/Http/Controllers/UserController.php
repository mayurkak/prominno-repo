<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use League\Config\Exception\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layouts/login');
    }
    public function view()
    {
        return view('layouts/welcome');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        try {
            // dd($request->all(),Auth::user());

            $credentials = $request->only('email', 'password', 'role');
            if (Auth::attempt($credentials)) {

                session([
                    'user_id' => Auth::user()->id,
                    'user_name' => Auth::user()->name,
                    'user_email' => Auth::user()->email,
                    'role' => Auth::user()->role,

                ]);

                return redirect()->route('view')->withSuccess('Signed in');
            }
            $validator['emailPassword'] = 'Email address or password is incorrect.';
            return redirect("login")->withErrors($validator);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function registration()
    {
        return view('layouts.register');
    }

    public function customRegistration(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id'   => 3,

            ]);

            session([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'role_id' => $user->role_id,
            ]);

            return redirect('index')->with('success', 'You have successfully registered!');
        } catch (ValidationException $e) {
            dd($e);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function signOut()
    {
        Auth::logout();
        return Redirect('/');
    }

    public function register(Request $request)
    {
        dd("hii");
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);
    }
}
