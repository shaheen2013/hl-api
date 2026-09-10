<?php

use Illuminate\Database\Seeder;
use App\BrandCustomModuleContent;

class EprivacyCustomContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = DB::table('languages')->get();
        $modules = DB::table('custom_module')->get();
        $texts = config('eprivacy.formal');

        BrandCustomModuleContent::whereNull('brand_custom_content_id')->delete();
        foreach ($languages as $language) {
            foreach ($modules as $module) {
                $text = $texts[$language->name][$module->name] ?? null;

                if (is_null($text)) {
                    continue;
                }

                BrandCustomModuleContent::insert([
                    'brand_custom_content_id' => null,
                    'custom_module_id'        => $module->id,
                    'language_id'             => $language->id,
                    'content'                 => $text,
                    'active'                  => 1
                ]);

            }
        }
    }
}
