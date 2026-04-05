<?php

namespace App\Http\Controllers\Auth\Operator;

use App\Events\OperatorRegistered;
use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisteredOperatorController extends Controller
{
    public function create()
    {
        return view('auth.operator.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'company_name' =>'required|string|max:225',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

           $operator = Operator::create([
                'user_id' =>  $user->id,
                'status' => 'pending',
                'company_name' => $request->company_name,
            ]);

            $profile = Profile::create([
                'user_id' => $user->id,
                'operator_id' => $operator->id,
            ]);

            $user->assignRole('operator');

            
            
            Auth::login($user);
            DB::commit();
            event(new Registered($user));
            event(new OperatorRegistered($user));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error('Operator Registration Failed: ' . $th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }

        return redirect()->route('dashboard');
    }
}
