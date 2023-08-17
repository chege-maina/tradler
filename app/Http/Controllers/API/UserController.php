<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    public function createUser(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'role_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => "Fill in all the Fields Below Properly"
            ];
            return response()->json($response, 400);
        }

        //Trial CSV Data

        $data = array_map('str_getcsv', file(public_path('uploads/Customers-100K.csv')));
        $header=$data[0];
        unset($data[0]);
        return($data);
        die();

        //Randomize password and Hash
        $input = $request->all();
        $input['password'] = bcrypt($this->generateRandomString());

        //Save User
        try {

            User::create($input);

            //Assign User Customers

            $response = [
                'success' => true,
                'message' => 'User Created Successfully'
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => "User Email Already Exists"
            ];
            return response()->json($response, 400);
        }
    }

    public function listRoles()
    {
        return response()->json([
            'roles' => Role::orderBy('id')->get()
        ]);
    }

    public function listUsers()
    {
        return response()->json([
            'users' => User::with('roles')->orderBy('id')->get()
        ]);
    }

    public function deleteUser(Request $request, $id)
    {
        User::findOrfail($id)->delete();
        $response = [
            'success' => true,
            'message' => 'User Deleted Successfully'
        ];

        return response()->json($response, 200);
    }

    public function editUser(Request $request, $id)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => "Fill in all the Fields Below Properly"
            ];
            return response()->json($response, 400);
        }

        //Update User
        User::where('id', $id)->first()->update($request->all());
        $response = [
            'success' => true,
            'message' => 'User Updated Successfully'
        ];

        return response()->json($response, 200);
    }


    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
