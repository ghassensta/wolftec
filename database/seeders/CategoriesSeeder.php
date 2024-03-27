<?php

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        // Créez ici les données de catégorie fictives à insérer dans la table des catégories
        Categorie::create([
            'name' => 'Category 1',
            'type' => 'Type 1',
        ]);

        Categorie::create([
            'name' => 'Category 2',
            'type' => 'Type 2',
        ]);

        // Ajoutez d'autres catégories au besoin
    }
}
