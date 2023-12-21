<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Categorie::with("produits")->get();
            return response()->json([
                "statut" => "SUCCESS",
                "categorie" => $categories
            ]);
        } catch (Exception $e) {
            return response()->json([
                "statut" => "FAIL",
                "Error" => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->json()->all(),
                [
                    "nom" => "required|min:2|unique:categories, nom"
                ],
                [
                    "nom" => "Le nom de la categorie est obligatoire",
                    "nom.unique" => "Le nom existe déjà",
                    "nom.min" => "Le nom ne doit pas être moins de 2 caractères",
                ]
            );

            $validator->validate();

            $categorie = Categorie::create([
                "nom" => $request->nom,
                "icone" => $request->icone,
            ]);

            return response()->json([
                "statut" => "SUCCESS",
                "message" => "Categorie crée avec succes",
                "categori" => $categorie
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->validator->errors());
        } catch (Exception $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if (Categorie::where('id', $id)->exists()) {
                $categorie = Categorie::find($id);

                return response()->json([
                    "statut" => "SUCCESS",
                    "categorie" => $categorie,
                ]);
            } else {
                return response()->json([
                    "statut" => "FAIL",
                    "message" => "Cette categorie n'existe pas"
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                "statut" => "FAIL",
                "Error" => $e->getMessage()
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make(
                $request->json()->all(),
                [
                    "nom" => "required|unique:categories,nom|min:2",
                ],
                [
                    "nom.required" => "Le nom est obligatoire",
                    "nom.unique" => "Le nom existe déjà",
                    "nom.min" => "Le nom ne doit pas être moins de 2 caractères",
                ]
            );
            $validator->validate();


            $categorie = Categorie::find($id);
            $categorie->update([
                "nom" => $request->nom,
                "icon" => $request->icone
            ]);

            return response()->json([
                "categorie" => $categorie,
                "message" => "La catégorie a été bien mise à jour",
                "statut" => "SUCCESS",
            ], 000);
        } catch (ValidationException $e) {
            return response()->json($e->validator->errors());
        } catch (Exception $e) {
            return response()->json([
                "Error" => $e->getMessage(),
                "statut" => "FAIL",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            if (Categorie::where('id', $id)->exists()) {
                Categorie::destroy($id);
                return response()->json([
                    "message" => "La catégorie a été bien supprimée",
                    "statut" => "SUCCESS",
                ], 200);
            } else {
                return response()->json([
                    "message" => "Cette catégorie n'existe pas",
                    "statut" => "FAIL",
                ], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                "Error" => $e->getMessage(),
                "statut" => "FAIL",
            ]);
        }
    }
}
