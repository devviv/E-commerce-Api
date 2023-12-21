<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\Produit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PanierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $paniers = Panier::where('user_id', auth()->id())->with('produits')->get();

            return response()->json([
                "paniers" => $paniers,
                "statut" => "SUCCESS",
            ]);
        } catch (Exception $th) {
            return response()->json($th->getMessage());
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
                    "produit_id" => "required",
                    "etat_commande" => "required",
                    "quantite" => "required",
                ],
                [
                    "produit_id.required" => "L'identifiant du produis est requis",
                    "etat_commande.required" => "Veuillez préciser l'état de la commande",
                    "quantite.required" => "La quantite est obligatoire",
                ]
            );
            $validator->validate();

            //Vérifier l'existence du produit dans le panier
            $panier = Panier::where('produit_id', $request->produit_id)->where('user_id', auth()->id())->get();
            if ($panier) {
                $panier->quantite++;
                return response()->json([
                    "message" => "Produit mise à jour",
                    "statut" => "SUCCESS",
                    "panier" => $panier,
                ]);
            } else {
                $panier = Panier::create([
                    "user_id" => auth()->id(),
                    "produit_id" => $request->produit_id,
                    "etat_commande" => $request->etat_commande,
                    "prix_total" => $request->prix * $request->quantite,
                    "quantite" => $request->quantite,
                ]);

                return response()->json([
                    "message" => "Produit ajouté au panier",
                    "statut" => "SUCCESS",
                    "panier" => $panier,
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->validator->errors(),
                "statut" => "FAIL",
            ]);
        } catch (Exception $th) {
            return response()->json([
                "message" => $th->getMessage(),
                "statut" => "FAIL",
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if (Panier::where('id', $id)->where('user_id', auth()->id())->exists()) {
                $panier = Panier::find($id);
                return response()->json([
                    "statut" => "SUCCESS",
                    "panier" => $panier,
                ]);
            } else {
                return response()->json([
                    "statut" => "FAIL",
                    "message" => "Aucun enregistrement ne correspond à ce id"
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
                    "quantite" => "required",
                ],
                [
                    "quantite.required" => "La quantite est obligatoire",
                ]
            );

            $validator->validate();

            $panier = Panier::where('id', $id)->where('user_id', auth()->id())->get();
            if($panier){
                $panier->update([
                    "quantite" => $request->quantite,
                ]);

                return response()->json([
                    "message" => "Panier mise à jour",
                    "statut" => "SUCCESS",
                    "panier" => $panier,
                ]);
            }
            else{
                return response()->json([
                    "statut" => "FAIL",
                    "message" => "Aucun enregistrement ne correspond à ce id"
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->validator->errors(),
                "statut" => "FAIL",
            ]);
        } catch (Exception $th) {
            return response()->json([
                "message" => $th->getMessage(),
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

            $panier = Panier::where('id', $id)->where('user_id', auth()->id())->get();
            if ($panier) {
                Panier::destroy($id);
                return response()->json([
                    "message" => "Panier supprimer",
                    "statut" => "SUCCESS",
                ]);
            } else {
                return response()->json([
                    "statut" => "FAIL",
                    "message" => "Aucun enregistrement ne correspond à ce id"
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->validator->errors(),
                "statut" => "FAIL",
            ]);
        } catch (Exception $th) {
            return response()->json([
                "message" => $th->getMessage(),
                "statut" => "FAIL",
            ]);
        }
    }
}
