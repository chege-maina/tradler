<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\CustomerCsvProcess;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
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

        //Hash and Randomize password 
        $input = $request->all();
        $input['password'] = bcrypt($this->generateRandomString());

        //Create User
        try {

            $user = User::create($input);

            $this->assignCustomers($user);

            $response = [
                'success' => true,
                'message' => 'User Created Successfully'
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => "User Email Already Exists" . $th
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

    public function getUser($id)
    {
        return response()->json([
            'user' => User::with('customers')->where('id', '=', $id)->orderBy('id')->get()
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

    function assignCustomers($user)
    {

        $path = $user->role_id == 1 ? 'uploads/Customers-10K.csv' : 'uploads/Customers-20K.csv';
        $chunks = array_chunk(file(public_path($path)), 2000);
        $header = [];
        $batch  = Bus::batch([])->dispatch();

        foreach ($chunks as $key => $chunk) {
            $data = array_map('str_getcsv', $chunk);

            if ($key === 0) {
                $header = $data[0];
                unset($data[0]);
            }
            $batch->add(new CustomerCsvProcess($data, $header, $user));
        }
    }
}
