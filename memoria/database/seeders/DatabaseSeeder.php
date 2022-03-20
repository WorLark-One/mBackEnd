<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comuna;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $comunas = [
            [
                'nombre' => 'Cauquenes',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Chanco',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Pelluhue',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Curicó',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Haulañé',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Licantén',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Molina',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Rauco',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Romeral',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Sagrada Familia',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Teno',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Vichuquén',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Colbún',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Linares',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Longaví',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Parral',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Retiro',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'San Javier',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Villa Alegre',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Yerbas Buenas',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Constitución',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Curepto',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Empedrado',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Maule',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Pelarco',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Pencahue',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Río Claro',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'San Clemente',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'San Rafael',
                'region' => 'Región del Maule',
            ],
            [
                'nombre' => 'Talca',
                'region' => 'Región del Maule',
            ],
        ];
        foreach ($comunas as $comuna) {
            Comuna::updateOrCreate($comuna);
        }
    }
}
