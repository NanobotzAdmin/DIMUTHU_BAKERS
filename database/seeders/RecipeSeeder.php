<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeInstruction;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample recipes
        $recipes = [
            [
                'name' => 'Classic Croissant',
                'description' => 'Buttery, flaky croissant with multiple layers',
                'category' => 'Pastry',
                'yield' => '24 pcs',
                'prep_time' => '3.5 hrs',
                'cost' => 12.50,
                'status' => 1,
                'version' => 'v2.3',
                'is_waste' => false,
                'shelf_life' => 2,
                'shelf_life_unit' => 'days'
            ],
            [
                'name' => 'Sourdough Bread',
                'description' => 'Traditional sourdough bread with tangy flavor',
                'category' => 'Bread',
                'yield' => '2 loaves',
                'prep_time' => '24 hrs',
                'cost' => 85.00,
                'status' => 1,
                'version' => 'v3.1',
                'is_waste' => false,
                'shelf_life' => 5,
                'shelf_life_unit' => 'days'
            ],
            [
                'name' => 'Breadcrumbs from Trimmings',
                'description' => 'Transform bread trimmings into useful breadcrumbs',
                'category' => 'Waste Processing',
                'yield' => '500 g',
                'prep_time' => '0.5 hrs',
                'cost' => 8.00,
                'status' => 1,
                'version' => 'v1.0',
                'is_waste' => true,
                'shelf_life' => 30,
                'shelf_life_unit' => 'days'
            ],
            [
                'name' => 'Chocolate Cake',
                'description' => 'Rich and moist chocolate cake',
                'category' => 'Cake',
                'yield' => '1 cake (8")',
                'prep_time' => '2 hrs',
                'cost' => 320.00,
                'status' => 1,
                'version' => 'v1.5',
                'is_waste' => false,
                'shelf_life' => 3,
                'shelf_life_unit' => 'days'
            ],
            [
                'name' => 'Vanilla Cupcakes',
                'description' => 'Light and fluffy vanilla cupcakes',
                'category' => 'Cake',
                'yield' => '12 pcs',
                'prep_time' => '1.5 hrs',
                'cost' => 180.00,
                'status' => 0,
                'version' => 'v1.0',
                'is_waste' => false,
                'shelf_life' => 2,
                'shelf_life_unit' => 'days'
            ]
        ];

        foreach ($recipes as $recipeData) {
            $recipe = Recipe::create($recipeData);

            // Create ingredients for each recipe
            $ingredients = [];
            switch ($recipe->name) {
                case 'Classic Croissant':
                    $ingredients = [
                        ['name' => 'All-purpose flour', 'quantity' => 500, 'unit' => 'g', 'cost_per_unit' => 0.02, 'type' => 'ingredient', 'sort_order' => 1],
                        ['name' => 'Butter', 'quantity' => 300, 'unit' => 'g', 'cost_per_unit' => 0.05, 'type' => 'ingredient', 'sort_order' => 2],
                        ['name' => 'Milk', 'quantity' => 200, 'unit' => 'ml', 'cost_per_unit' => 0.01, 'type' => 'ingredient', 'sort_order' => 3],
                        ['name' => 'Yeast', 'quantity' => 10, 'unit' => 'g', 'cost_per_unit' => 0.1, 'type' => 'ingredient', 'sort_order' => 4],
                        ['name' => 'Sugar', 'quantity' => 50, 'unit' => 'g', 'cost_per_unit' => 0.01, 'type' => 'ingredient', 'sort_order' => 5],
                        ['name' => 'Salt', 'quantity' => 10, 'unit' => 'g', 'cost_per_unit' => 0.01, 'type' => 'ingredient', 'sort_order' => 6],
                    ];
                    break;
                case 'Sourdough Bread':
                    $ingredients = [
                        ['name' => 'Bread flour', 'quantity' => 500, 'unit' => 'g', 'cost_per_unit' => 0.02, 'type' => 'ingredient', 'sort_order' => 1],
                        ['name' => 'Water', 'quantity' => 350, 'unit' => 'ml', 'cost_per_unit' => 0.001, 'type' => 'ingredient', 'sort_order' => 2],
                        ['name' => 'Sourdough starter', 'quantity' => 100, 'unit' => 'g', 'cost_per_unit' => 0.05, 'type' => 'ingredient', 'sort_order' => 3],
                        ['name' => 'Salt', 'quantity' => 10, 'unit' => 'g', 'cost_per_unit' => 0.01, 'type' => 'ingredient', 'sort_order' => 4],
                    ];
                    break;
                case 'Breadcrumbs from Trimmings':
                    $ingredients = [
                        ['name' => 'Bread trimmings', 'quantity' => 500, 'unit' => 'g', 'cost_per_unit' => 0.01, 'type' => 'waste_input', 'sort_order' => 1],
                    ];
                    break;
                case 'Chocolate Cake':
                    $ingredients = [
                        ['name' => 'All-purpose flour', 'quantity' => 240, 'unit' => 'g', 'cost_per_unit' => 0.02, 'type' => 'ingredient', 'sort_order' => 1],
                        ['name' => 'Cocoa powder', 'quantity' => 60, 'unit' => 'g', 'cost_per_unit' => 0.08, 'type' => 'ingredient', 'sort_order' => 2],
                        ['name' => 'Sugar', 'quantity' => 240, 'unit' => 'g', 'cost_per_unit' => 0.01, 'type' => 'ingredient', 'sort_order' => 3],
                        ['name' => 'Butter', 'quantity' => 200, 'unit' => 'g', 'cost_per_unit' => 0.05, 'type' => 'ingredient', 'sort_order' => 4],
                        ['name' => 'Eggs', 'quantity' => 4, 'unit' => 'pcs', 'cost_per_unit' => 0.2, 'type' => 'ingredient', 'sort_order' => 5],
                        ['name' => 'Milk', 'quantity' => 240, 'unit' => 'ml', 'cost_per_unit' => 0.01, 'type' => 'ingredient', 'sort_order' => 6],
                        ['name' => 'Baking powder', 'quantity' => 2, 'unit' => 'tsp', 'cost_per_unit' => 0.05, 'type' => 'ingredient', 'sort_order' => 7],
                    ];
                    break;
                case 'Vanilla Cupcakes':
                    $ingredients = [
                        ['name' => 'All-purpose flour', 'quantity' => 180, 'unit' => 'g', 'cost_per_unit' => 0.02, 'type' => 'ingredient', 'sort_order' => 1],
                        ['name' => 'Sugar', 'quantity' => 180, 'unit' => 'g', 'cost_per_unit' => 0.01, 'type' => 'ingredient', 'sort_order' => 2],
                        ['name' => 'Butter', 'quantity' => 120, 'unit' => 'g', 'cost_per_unit' => 0.05, 'type' => 'ingredient', 'sort_order' => 3],
                        ['name' => 'Eggs', 'quantity' => 3, 'unit' => 'pcs', 'cost_per_unit' => 0.2, 'type' => 'ingredient', 'sort_order' => 4],
                        ['name' => 'Milk', 'quantity' => 120, 'unit' => 'ml', 'cost_per_unit' => 0.01, 'type' => 'ingredient', 'sort_order' => 5],
                        ['name' => 'Vanilla extract', 'quantity' => 2, 'unit' => 'tsp', 'cost_per_unit' => 0.1, 'type' => 'ingredient', 'sort_order' => 6],
                        ['name' => 'Baking powder', 'quantity' => 1.5, 'unit' => 'tsp', 'cost_per_unit' => 0.05, 'type' => 'ingredient', 'sort_order' => 7],
                    ];
                    break;
            }

            foreach ($ingredients as $ingredient) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'cost_per_unit' => $ingredient['cost_per_unit'],
                    'type' => $ingredient['type'],
                    'sort_order' => $ingredient['sort_order']
                ]);
            }

            // Create instructions for each recipe
            $instructions = [];
            switch ($recipe->name) {
                case 'Classic Croissant':
                    $instructions = [
                        ['step_description' => 'Mix flour, sugar, salt, and yeast in a large bowl', 'step_number' => 1, 'sort_order' => 1],
                        ['step_description' => 'Add milk and butter, knead until smooth dough forms', 'step_number' => 2, 'sort_order' => 2],
                        ['step_description' => 'Rest dough for 30 minutes', 'step_number' => 3, 'sort_order' => 3],
                        ['step_description' => 'Roll out dough and add butter layer', 'step_number' => 4, 'sort_order' => 4],
                        ['step_description' => 'Fold and chill multiple times to create layers', 'step_number' => 5, 'sort_order' => 5],
                        ['step_description' => 'Shape into croissants and proof for 2 hours', 'step_number' => 6, 'sort_order' => 6],
                        ['step_description' => 'Bake at 200°C for 15-20 minutes until golden', 'step_number' => 7, 'sort_order' => 7],
                    ];
                    break;
                case 'Sourdough Bread':
                    $instructions = [
                        ['step_description' => 'Mix flour and water to create autolyse', 'step_number' => 1, 'sort_order' => 1],
                        ['step_description' => 'Add sourdough starter and salt', 'step_number' => 2, 'sort_order' => 2],
                        ['step_description' => 'Knead until smooth dough forms', 'step_number' => 3, 'sort_order' => 3],
                        ['step_description' => 'Bulk fermentation for 4-6 hours with folds', 'step_number' => 4, 'sort_order' => 4],
                        ['step_description' => 'Shape into boule and proof for 2-4 hours', 'step_number' => 5, 'sort_order' => 5],
                        ['step_description' => 'Bake in Dutch oven at 230°C for 20 minutes covered, then 15 minutes uncovered', 'step_number' => 6, 'sort_order' => 6],
                    ];
                    break;
                case 'Breadcrumbs from Trimmings':
                    $instructions = [
                        ['step_description' => 'Collect bread trimmings and stale bread', 'step_number' => 1, 'sort_order' => 1],
                        ['step_description' => 'Cut into small pieces and dry in oven at 100°C for 1 hour', 'step_number' => 2, 'sort_order' => 2],
                        ['step_description' => 'Pulse in food processor until fine crumbs form', 'step_number' => 3, 'sort_order' => 3],
                        ['step_description' => 'Store in airtight container', 'step_number' => 4, 'sort_order' => 4],
                    ];
                    break;
                case 'Chocolate Cake':
                    $instructions = [
                        ['step_description' => 'Preheat oven to 180°C and grease cake pans', 'step_number' => 1, 'sort_order' => 1],
                        ['step_description' => 'Mix dry ingredients in one bowl', 'step_number' => 2, 'sort_order' => 2],
                        ['step_description' => 'Mix wet ingredients in another bowl', 'step_number' => 3, 'sort_order' => 3],
                        ['step_description' => 'Combine wet and dry ingredients gradually', 'step_number' => 4, 'sort_order' => 4],
                        ['step_description' => 'Pour into pans and bake for 25-30 minutes', 'step_number' => 5, 'sort_order' => 5],
                        ['step_description' => 'Cool completely before frosting', 'step_number' => 6, 'sort_order' => 6],
                    ];
                    break;
                case 'Vanilla Cupcakes':
                    $instructions = [
                        ['step_description' => 'Preheat oven to 180°C and line muffin tin', 'step_number' => 1, 'sort_order' => 1],
                        ['step_description' => 'Cream butter and sugar until light and fluffy', 'step_number' => 2, 'sort_order' => 2],
                        ['step_description' => 'Add eggs one at a time, then vanilla extract', 'step_number' => 3, 'sort_order' => 3],
                        ['step_description' => 'Alternately add flour and milk, beginning and ending with flour', 'step_number' => 4, 'sort_order' => 4],
                        ['step_description' => 'Fill muffin cups 2/3 full and bake for 18-20 minutes', 'step_number' => 5, 'sort_order' => 5],
                        ['step_description' => 'Cool completely before frosting', 'step_number' => 6, 'sort_order' => 6],
                    ];
                    break;
            }

            foreach ($instructions as $instruction) {
                RecipeInstruction::create([
                    'recipe_id' => $recipe->id,
                    'step_description' => $instruction['step_description'],
                    'step_number' => $instruction['step_number'],
                    'sort_order' => $instruction['sort_order']
                ]);
            }
        }
    }
}
