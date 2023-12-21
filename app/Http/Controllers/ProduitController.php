<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $produits = Produit::with("categorie")->get();

            return response()->json($produits, 200);
        } catch (Exception $e) {
            return response()->json([
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

            $validator = Validator::make($request->json()->all(), [
                "nom" => "required|unique:produits,nom",
                "description" => "required",
                "prix" => "required",
                "image" => "required|",
            ], [
                "nom.required" => "Le nom est obligatoire",
                "nom.unique" => "Le nom existe déjà",
                "description.required" => "La description est obigatoire",
                "prix.required" => "Le prix est obigatoire",
                "image.required" => "L'image est obligatoire",
            ]);

            $validator->validate();
            if (Categorie::where('id', $request->categorie_id)->exists()) {
                $produit = Produit::create([
                    "categorie_id" => $request->categorie_id,
                    "nom" => $request->nom,
                    "description" => $request->description,
                    "prix" => $request->prix,
                    "slug" => $request->slug,
                    "image" => $request->image,
                    "visible" => $request->visible,
                ]);
                return response()->json([
                    "statut" => "SUCCESS",
                    "article" => $produit,
                    "message" => "Le produit a été bien ajouté"
                ], 200);
            } else {
                return response()->json([
                    "statut" => "FAIL",
                    "message" => "Cette categorie n'existe pas"
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json($e->validator->errors());
        } catch (Exception $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if (Produit::where('id', $id)->exists()) {
                $article = Produit::find($id);

                return response()->json([
                    "statut" => "SUCCESS",
                    "article" => $article,
                ]);
            } else {
                return response()->json([
                    "statut" => "FAIL",
                    "message" => "Ce produit n'existe pas"
                ], 500);
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
            $validator = Validator::make($request->json()->all(), [
                "nom" => "required|unique:produits,nom",
                "description" => "required",
                "prix" => "required",
                "image" => "required|",
            ], [
                "nom.required" => "Le nom est obligatoire",
                "nom.unique" => "Le nom existe déjà",
                "description.required" => "La description est obigatoire",
                "prix.required" => "Le prix est obigatoire",
                "image.required" => "L'image est obligatoire",
            ]);

            $validator->validate();

            $produit = Produit::find($id);

            if (Categorie::where('id', $request->categorie_id)->exists()) {
                $produit = Produit::create([
                    "categorie_id" => $request->categorie_id,
                    "nom" => $request->nom,
                    "description" => $request->description,
                    "prix" => $request->prix,
                    "slug" => $request->slug,
                    "image" => $request->image,
                    "visible" => $request->visible,
                ]);
                return response()->json([
                    "statut" => "SUCCESS",
                    "article" => $produit,
                    "message" => "Le produit a été bien ajouté"
                ], 200);
            } else {
                return response()->json([
                    "statut" => "FAIL",
                    "message" => "Cette categorie n'existe pas"
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json($e->validator->errors());
        } catch (Exception $th) {
            return response()->json($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            if (Produit::where('id', $id)->exists()) {
                Produit::destroy($id);
                return response()->json([
                    "statut" => "SUCCESS",
                    "message" => "Le produit a été bien supprimé"
                ], 000);
            } else {
                return response()->json([
                    "statut" => "FAIL",
                    "message" => "Ce produit n'existe pas"
                ], 002);
            }
        } catch (Exception $e) {
            return response()->json([
                "statut" => "FAIL",
                "Error" => $e->getMessage()
            ]);
        }
    }
}
