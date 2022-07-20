<?php

namespace App\Model;

class Recipe
{
    public $recipes = [
        [
            "title" => "Pâte à crêpes",
            "caption" => "Découvrez cette recette de crêpes très rapide à préparer. Une recette simple très classique qui, grâce à sa quantité d'oeufs, ne nécessite aucun repos de la pâte. C'est une amie bretonne qui m'a donné ses secrets. En tout cas chez moi, ces crêpes ne font jamais long feu.",
            "steps" => [
                "etape 1" => "Mettez la farine dans un saladier avec le sel et le sucre.",
                "etape 2" => "Faites un puits au milieu et versez-y les œufs.",
                "etape 3" => "Commencez à mélanger doucement. Quand le mélange devient épais, ajoutez le lait froid petit à petit.",
                "etape 4" => "Quand tout le lait est mélangé, la pâte doit être assez fluide. Si elle vous paraît trop épaisse, rajoutez un peu de lait. Ajoutez ensuite le beurre fondu refroidi, mélangez bien.",
                "etape 5" => "Faites cuire les crêpes dans une poêle chaude (par précaution légèrement huilée si votre poêle à crêpes n'est pas anti-adhésive). Versez une petite louche de pâte dans la poêle, faites un mouvement de rotation pour répartir la pâte sur toute la surface. Posez sur le feu et quand le tour de la crêpe se colore en roux clair, il est temps de la retourner.",
                "etape 6" => "Laissez cuire environ une minute de ce côté et la crêpe est prête."
            ],
            "duration" => 25,
            "difficulty" => 1,
            "category" => 4,
            "recipeIngredients" => [
                [
                    "ingredientId" => 1,
                    "unit" => "cl",
                    "quantity" => 50
                ],
                [
                    "ingredientId" => 2,
                    "unit" => "gr",
                    "quantity" => 250
                ],
                [
                    "ingredientId" => 4,
                    "unit" => "cuillère à soupe",
                    "quantity" => 2
                ],
                [
                    "ingredientId" => 6,
                    "unit" => "nombre",
                    "quantity" => 4
                ],
                [
                    "ingredientId" => 8,
                    "unit" => "pincée",
                    "quantity" => 1
                ],
                [
                    "ingredientId" => 3,
                    "unit" => "gr",
                    "quantity" => 50
                ]
            ]

        ]
    ];
}
