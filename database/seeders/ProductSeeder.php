<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Création de quelques produits fictifs
        Product::create([
            'productName' => 'Produit 1',
            'quantity' => 10,
            'price' => 50.99,
            'desc' => 'Description du produit 1',
            'statut' => 1,
            'image' => 'image1.jpg',
            'category_id' => 1,
        ]);

        Product::create([
            'productName' => 'Produit 2',
            'quantity' => 5,
            'price' => 35.75,
            'desc' => 'Description du produit 2',
            'statut' => 1,
            'image' => 'image2.jpg',
            'category_id' => 2,
        ]);

        // Ajoutez d'autres produits au besoin
    }
}
