<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
   /*  function optimize($file, $name, $role)
    {
        $imageName = $name . $role . time() . '.' . $file->extension();
        $file->move(public_path('img'), $imageName);
        return $imageName;
    } */

    public function index()
    {
        $allProducts = Product::all();
        return response()->json(['success' => true, 'data' => $allProducts]);
    }


    public function store(Request $request, Product $product)
    {
        // Vérifier si un fichier image a été téléchargé
        if ($request->hasFile('image')) {
            // Générer un nom aléatoire pour l'image
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
            // Enregistrer l'image dans le répertoire de stockage
            Storage::putFileAs('public/images', $request->file('image'), $imageName);
        } else {
            // Si aucune image n'a été téléchargée, utiliser un nom d'image par défaut ou laisser vide
            $imageName = null;
        }

        // Créer le produit dans la base de données
        $newProduct = Product::create([
            'image' => $imageName, // Enregistrer le nom de l'image dans la base de données
            'productName' => $request->productName,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'desc' => $request->desc,
            'statut' => $request->statut,
            'category_id' => $request->category_id,
        ]);

        // Retourner une réponse JSON avec le nouveau produit créé
        return response()->json(['success' => true, 'data' => $newProduct], 201);
    }

    public function show(Product $product)
    {
        return response()->json(['success' => true, 'data' => $product], 200);
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'productName' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'desc' => 'required|string',
            'status' => 'required|integer',
            'image' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        if ($request->hasFile('')) {
            $imageName = $this->optimize($request->file('image'), 'product', 'image');
            $validatedData['image'] = $imageName;
        }

        $product->update($validatedData);

        return response()->json(['success' => true, 'data' => $product], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully'], 200);
    }
}
