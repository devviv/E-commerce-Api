<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();

            return response()->json($users, 200);
        } catch (Exception $e) {
            return response()->json([
                "Error" => $e->getMessage()
            ]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            "name" => "required",
            "email" => "required|unique:users,email|email",
            "password" => "required",
        ]);

        // Verifier si la validation a échoué
        if ($validator->fails()) {
            Log::error(serialize($validator->errors()));
            return response()->json([
                "statut" => "FAIL",
                "errors" => $validator->errors()
            ]);
        }

        //Enregistrer dans la DB
        $user = User::create([
            "name" => $request->json("name"),
            "email" => $request->json("email"),
            "password" => Hash::make($request->json("password")), //On hash le mot de passe
        ]);

        return response()->json([
            "status" => "SUCCESS",
            "information" => $user
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);


        // Verifier si la validation a échoué
        if ($validator->fails()) {
            Log::error(serialize($validator->errors()));
            return response()->json([
                "statut" => "FAIL",
                "errors" => $validator->errors()
            ]);
        }

        $user = User::where('email', $request->json("email"))->first();

        if (!$user) {
            return response()->json([
                "statut" => "FAIL",
                "errors" => "Votre email est incorrect"
            ]);
        }

        if (Hash::check($request->json("password"), $user->password)) {
            Auth::login($user);
            $token = $user->createToken('userToken')->plainTextToken;

            return response()->json([
                "status" => "SUCCESS",
                "information" => $user,
                "token" => $token
            ]);
        }


        // On retourne l'erreur du mot de passe
        return response()->json([
            "status" => "FAIL",
            "errors" => "Votre mot de passe est incorrect!"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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
    public function destroy(string $id)
    {
        //
    }
}
