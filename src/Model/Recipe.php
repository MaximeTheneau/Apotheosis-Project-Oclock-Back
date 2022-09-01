<?php

namespace App\Model;

class Recipe
{
    public $recipes = [
        [
            "title" => "Pâte à crêpes",
            "caption" => "Découvrez cette recette de crêpes très rapide à préparer. Une recette simple très classique qui, grâce à sa quantité d'oeufs, ne nécessite aucun repos de la pâte. C'est une amie bretonne qui m'a donné ses secrets. En tout cas chez moi, ces crêpes ne font jamais long feu.",
            "steps" => [
                "etape1" => "Mettez la farine dans un saladier avec le sel et le sucre.",
                "etape2" => "Faites un puits au milieu et versez-y les œufs.",
                "etape3" => "Commencez à mélanger doucement. Quand le mélange devient épais, ajoutez le lait froid petit à petit.",
                "etape4" => "Quand tout le lait est mélangé, la pâte doit être assez fluide. Si elle vous paraît trop épaisse, rajoutez un peu de lait. Ajoutez ensuite le beurre fondu refroidi, mélangez bien.",
                "etape5" => "Faites cuire les crêpes dans une poêle chaude (par précaution légèrement huilée si votre poêle à crêpes n'est pas anti-adhésive). Versez une petite louche de pâte dans la poêle, faites un mouvement de rotation pour répartir la pâte sur toute la surface. Posez sur le feu et quand le tour de la crêpe se colore en roux clair, il est temps de la retourner.",
                "etape6" => "Laissez cuire environ une minute de ce côté et la crêpe est prête."
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

        ],
        [
            "title" => "Omelette nature",
            "steps" => [
                "etape1" => "Battez les oeufs à la fourchette, salez et poivrez.",
                "etape2" => "Faites chauffer le beurre, versez-en un peu dans les oeufs et mélangez. Versez les oeufs dans la poêle à feu vif, baissez le feu et laissez cuire doucement en ramenant les bords de l'omelette au centre au fur et à mesure qu'ils prennent.",
                "etape3" => "Secouez un peu la poêle pour éviter que l'omelette n'attache, vérifiez la texture baveuse ou bien prise.",
                "etape4" => "Pliez l'omelette en deux et servez"
            ],
            "duration" => 15,
            "difficulty" => 1,
            "category" => 2,
            "recipeIngredients" => [
                [
                    "ingredientId" => 3,
                    "unit" => "gr",
                    "quantity" => 50
                ],
                [
                    "ingredientId" => 20,
                    "unit" => "pincée",
                    "quantity" => 1
                ],
                [
                    "ingredientId" => 8,
                    "unit" => "pincée",
                    "quantity" => 1
                ],
                [
                    "ingredientId" => 6,
                    "unit" => "nombre",
                    "quantity" => 7
                ]
            ]

        ],
        [
            "title" => "Mojito",
            "caption" => "Découvrez la recette du Mojito, la star des cocktails de l'été grâce à sa menthe parfumée et son irrésistible fraîcheur.",
            "steps" => [
                "etape1" => "Dans un verre, écrasez les feuilles de menthe au pilon dans le rhum",
                "etape2" => "Ajoutez le sucre et le jus de citron et mélangez.",
                "etape3" => "Pilez les glaçons",
                "etape4" => "Terminez en rajoutant l'eau gazeuse et les glaçons pilés"
            ],
            "duration" => 5,
            "difficulty" => 1,
            "category" => 1,
            "recipeIngredients" => [
                [
                    "ingredientId" => 21,
                    "unit" => "cl",
                    "quantity" => 5
                ],
                [
                    "ingredientId" => 22,
                    "unit" => "feuille",
                    "quantity" => 7
                ],
                [
                    "ingredientId" => 26,
                    "unit" => "cl",
                    "quantity" => 0.5
                ],
                [
                    "ingredientId" => 4,
                    "unit" => "cuillère à café",
                    "quantity" => 2
                ],
                [
                    "ingredientId" => 23,
                    "unit" => "cl",
                    "quantity" => 6
                ],
                [
                    "ingredientId" => 25,
                    "unit" => "nombre",
                    "quantity" => 4
                ]
            ]

            ],
            [
                "title" => "Bamoto",
                "caption" => "Découvrez la recette du Mojito, la star des cocktails de l'été grâce à sa menthe parfumée et son irrésistible fraîcheur.",
                "steps" => [
                    "etape1" => "Dans un verre, écrasez les feuilles de menthe au pilon dans le rhum",
                    "etape2" => "Ajoutez le sucre et le jus de citron et mélangez.",
                    "etape3" => "Pilez les glaçons",
                    "etape4" => "Terminez en rajoutant l'eau gazeuse et les glaçons pilés"
                ],
                "duration" => 5,
                "difficulty" => 1,
                "category" => 1,
                "recipeIngredients" => [
                    [
                        "ingredientId" => 21,
                        "unit" => "cl",
                        "quantity" => 5
                    ],
                    [
                        "ingredientId" => 22,
                        "unit" => "feuille",
                        "quantity" => 7
                    ],
                    [
                        "ingredientId" => 26,
                        "unit" => "cl",
                        "quantity" => 0.5
                    ],
                    [
                        "ingredientId" => 4,
                        "unit" => "cuillère à café",
                        "quantity" => 2
                    ],
                    [
                        "ingredientId" => 23,
                        "unit" => "cl",
                        "quantity" => 6
                    ],
                    [
                        "ingredientId" => 25,
                        "unit" => "nombre",
                        "quantity" => 4
                    ]
                ]
    
            ]


    ];
}
