<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Account.
     */
    public function index()
    {
        $user = Auth::user();
        return view("hasLogin.user.index", compact("user"));
    }

    /**
     * Update Account.
     */
    public function update(Request $request)
    {
        if ($request->exists("new_password")) {
            $this->validate($request, [
                "new_password" => ["required", "string", "min:8", "confirmed"],
                "old_password" => ["required", function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        return $fail(__("The old password is incorrect."));
                    }
                }]
            ]);
            $request->user()->update(["password" => $request->new_password]);
        } else {
            $this->validate($request, [
                "name" => "required|string|max:255",
                "email" => "required|string|email|max:255|unique:users,email," . $request->user()->id,
                "gender" => "required|in:male,female",
                "birthdate" => "required|date",
                "address" => "required|string|max:255",
            ]);
            $request->user()->update($request->all());
        }
        return redirect("account");
    }

    /**
     * Delete Account.
     */
    public function destroy(User $user)
    {
        $user = User::find(Auth::user()->id);
        Auth::logout();
        $user->delete();
        return redirect("login");
    }
}
