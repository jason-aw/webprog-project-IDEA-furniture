<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        // only members can access edit profile page
        $this->middleware('role:member');
    }
    
    public function getEditProfilePage ()
    {
        return view('edit-profile', ['user' => Auth::user()]);
    }
    
    public function editProfile (Request $request)
    {
        // validate input
        $this->validate($request, [
            'name' => ['required', 'string', 'min:10', 'max:255'],
            'address' => ['required', 'min:10'],
            'gender' => ['required', 'in:male,female'],
            'dob' => ['required', 'before:today'],
        ]);
        
        // get user
        $user = User::find(Auth::user()->id);

        // update user
        $user->name = $request->name;
        $user->address = $request->address;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->save();
        
        // return success message
        return redirect()->back()->withSuccess("User profile " . $request->name . " has been successfully edited!");
    }
}
