<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlnDepartment;
use App\Models\PlnResource;

class PlannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Main Bakery
        $bakery = PlnDepartment::create([
            'name' => 'Main Bakery',
            'color' => 'orange',
            'icon' => 'fire', // bootstrap icon name often used without prefix in backend logic if frontend handles it
            'status' => 1
        ]);

        PlnResource::create([
            'pln_department_id' => $bakery->id,
            'name' => 'Deck Oven 1',
            'type' => 'oven',
            'capacity' => 120,
            'status' => 1
        ]);

        PlnResource::create([
            'pln_department_id' => $bakery->id,
            'name' => 'Deck Oven 2',
            'type' => 'oven',
            'capacity' => 120,
            'status' => 1
        ]);

        PlnResource::create([
            'pln_department_id' => $bakery->id,
            'name' => 'Spiral Mixer A',
            'type' => 'mixer',
            'capacity' => 80,
            'status' => 1
        ]);

        // 2. Pastry Kitchen
        $pastry = PlnDepartment::create([
            'name' => 'Pastry Kitchen',
            'color' => 'pink', // standard css color name or hex
            'icon' => 'cake', 
            'status' => 1
        ]);

        PlnResource::create([
            'pln_department_id' => $pastry->id,
            'name' => 'Convection Oven',
            'type' => 'oven',
            'capacity' => 60,
            'status' => 1
        ]);

        PlnResource::create([
            'pln_department_id' => $pastry->id,
            'name' => 'Pastry Sheeter',
            'type' => 'machine',
            'capacity' => 100,
            'status' => 1
        ]);

        PlnResource::create([
            'pln_department_id' => $pastry->id,
            'name' => 'Prep Table 1',
            'type' => 'table',
            'capacity' => 100,
            'status' => 1
        ]);
        
        // 3. Decorating
        $decor = PlnDepartment::create([
            'name' => 'Decorating Room',
            'color' => 'purple',
            'icon' => 'brush',
            'status' => 1
        ]);

        PlnResource::create([
            'pln_department_id' => $decor->id,
            'name' => 'Finishing Table',
            'type' => 'table',
            'capacity' => 50,
            'status' => 1
        ]);
    }
}
