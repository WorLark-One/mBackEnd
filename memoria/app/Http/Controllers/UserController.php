<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesValidation());
        if ($validator->fails()) {
            return response()->json(['code' => '400', 'message' => 'Request not valid for create user'], 400);
        }
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $user->assignRole($request['rol']);
        if ($request->shopper) {
            $user->assignRole('shopper');
            $user->shopper_comunes = $request->comunas;
            $user->save();
        }

        return response()->json(['code' => '200','data' => $user], 200);
    }

    /**
    * Rules validation of request producto
    * @return array of rules validation
    */
    public function rulesValidation()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'rol' => 'required',
            'shopper' => 'required',
            'comunas' => 'required'
        ];
        return $rules;
    }
}
