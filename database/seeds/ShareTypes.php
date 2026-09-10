<?php

use Illuminate\Database\Seeder;

class ShareTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\ShareTypes::firstOrCreate([
            'tipo_es' => 'encuesta',
            'tipo_en' => 'survey',
            'tipo_sys' => 'srv'
        ]);
        
        App\ShareTypes::firstOrCreate([
            'tipo_es' => 'pre-stay',
            'tipo_en' => 'pre-stay',
            'tipo_sys' => 'pre'
        ]);
        
        App\ShareTypes::firstOrCreate([
            'tipo_es' => 'stay',
            'tipo_en' => 'stay',
            'tipo_sys' => 'stay'
        ]);
        
        App\ShareTypes::firstOrCreate([
            'tipo_es' => 'post-stay',
            'tipo_en' => 'post-stay',
            'tipo_sys' => 'post'
        ]);
        
        App\ShareTypes::updateOrCreate(
            ['id' => 10],
            [
                'tipo_es' => 'cumpleaños',
                'tipo_en' => 'birthday',
                'tipo_sys' => 'birth'
            ]
        );
        
    }
}
