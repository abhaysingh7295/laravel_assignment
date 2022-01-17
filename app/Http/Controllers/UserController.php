<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get team projects
        $user = User::orderBy('created_at', 'desc')->get();

        $payload = [
            'message' => 'success',
            'users' => $user,

        ];
        return response()->json($payload, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
         //validate the form
         $validator = Validator::make(request()->all(), [
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'phone_number' => ['required','numeric','digits:10'],
            'date_of_birth'=>['nullable','date_format:Y-m-d','before:today'],
            'is_vaccinated'=>[
                "required","in:YES,NO"
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
           
        ]);

        //validation errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['message' => $errors], 409);
        }
        $user = new User();
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone_number = $request->phone_number;
        $user->date_of_birth = $request->date_of_birth;
        $user->address = $request->address;
        $user->is_vaccinated = $request->is_vaccinated;
        if($request->is_vaccinated=='YES'){
            if($request->vaccine_name=="COVAXIN" || $request->vaccine_name=="COVISHIELD"){
                $user->vaccine_name = $request->vaccine_name;
            }else{
                return response()->json(['message' => 'Vaccine name mustbe COVAXIN or COVISHIELD'], 409);
            }
        }else{
            $user->vaccine_name = $request->vaccine_name;
        }
       
        $user->save();
        return response()->json(['message' => 'user created Successfully', 'user' => $user], 201);
    }

   
}
