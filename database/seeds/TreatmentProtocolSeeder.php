<?php

use Illuminate\Database\Seeder;

class TreatmentProtocolSeeder extends Seeder
{
    private $customModules;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->customModules = [
            'eprivacy_text_informal',
            'second_eprivacy_text_informal',
            'restrictive_eprivacy_text_informal',
            'restrictive_second_eprivacy_text_informal',
            'not_hotel_second_eprivacy_text_informal',
            'second_eprivacy_not_client_text_informal'
        ];

        $this->setCustomModules();
        $this->setBrandCustomModuleContent();
        $this->setFormalTextForCurrentModuleContent();
        $this->setInformalTextForCurrentModuleContent();
        $this->setCustomModuleVars();
    }

    private function setCustomModules()
    {

        foreach ($this->customModules as $moduleInformal) {
            try {
                DB::table('custom_module')->insert([
                    'name' => $moduleInformal
                ]);
            } catch (\Exception $exception) {
                info("Seed error: " . $exception->getMessage());
            }
        }
    }

    private function setBrandCustomModuleContent()
    {
        foreach ($this->customModules as $moduleInformal) {
            $moduleFormal = str_replace('_informal', '', $moduleInformal);

            $currentBrandCustomModules = DB::table('brand_custom_module_content')
                ->select(
                    'brand_custom_module_content.brand_custom_content_id',
                    'cm.id AS custom_module_id',
                    'brand_custom_module_content.language_id',
                    'brand_custom_module_content.content',
                    'brand_custom_module_content.active'
                )
                ->join(
                    'custom_module',
                    'custom_module.id',
                    'brand_custom_module_content.custom_module_id'
                )
                ->leftJoin(
                    'custom_module AS cm',
                    'cm.name',
                    DB::raw("'{$moduleInformal}'")
                )
                ->leftJoin(
                    'brand_custom_module_content AS bcmc',
                    'bcmc.custom_module_id',
                    'cm.id'
                )
                ->where('custom_module.name', $moduleFormal)
                ->whereNull('brand_custom_module_content.brand_custom_content_id')
                ->whereNull('bcmc.id');

            foreach ($currentBrandCustomModules->get() as $currentBrandCustomModule) {
                DB::table('brand_custom_module_content')->insert((array)$currentBrandCustomModule);
            }
        }
    }

    private function setFormalTextForCurrentModuleContent()
    {
        $this->setTexts(config('eprivacy.formal'));
    }

    private function setInformalTextForCurrentModuleContent()
    {
        $this->setTexts(config('eprivacy.informal'));
    }

    private function setTexts($moduleTexts)
    {
        $exclude = [
            'legal_text',
            'restrictive_legal_text',
            'not_hotel_legal_text'
        ];

        foreach ($moduleTexts as $lang => $moduleText) {
            $keys = array_diff(array_keys($moduleText), $exclude);

            $brandsCustomDefaultContent = DB::table('brand_custom_module_content')
                ->select(
                    'brand_custom_module_content.id',
                    'custom_module.name'
                )
                ->join(
                    'custom_module',
                    'custom_module.id',
                    'brand_custom_module_content.custom_module_id'
                )
                ->join(
                    'languages',
                    'languages.id',
                    'brand_custom_module_content.language_id'
                )
                ->whereIn('custom_module.name', $keys)
                ->where('languages.name', $lang)
                ->whereNull('brand_custom_module_content.brand_custom_content_id');

            foreach ($brandsCustomDefaultContent->get() as $brandCustomDefaultContent) {
                DB::table('brand_custom_module_content')
                    ->where('id', $brandCustomDefaultContent->id)
                    ->update([
                        'content' => $moduleText[$brandCustomDefaultContent->name]
                    ]);
            }
        }
    }

    private function setCustomModuleVars()
    {
        $customModuleVarNew = [];
        $customModuleVars = DB::table('custom_module AS cm')
            ->select(
                'cm.id AS custom_module_id',
                'cv.id AS custom_vars_id'
            )
            ->leftJoin('custom_vars AS cv', function ($join) {
                //'cv.name',
                //DB::raw("IN ('{{brandLegalName}}', '{{brandLegalAddress}}', '{{brandNIF}}', '{{brandLegalEmail}}')")
                $varNames = ["'{{brandLegalName}}'", "'{{brandLegalAddress}}'", "'{{brandNIF}}'", "'{{brandLegalEmail}}'"];
                foreach ($varNames as $varName) {
                    $join->orOn('cv.name', DB::raw($varName));
                }
            })
            ->leftJoin(
                'custom_module_vars AS cmv',
                'cmv.custom_module_id',
                'cm.id'
            )
            ->whereIn('cm.name', $this->customModules)
            ->whereNull('cmv.custom_module_id');

        foreach ($customModuleVars->get() as $customModuleVar) {
            $customModuleVarNew[] = [
                'custom_module_id' => $customModuleVar->custom_module_id,
                'custom_vars_id'   => $customModuleVar->custom_vars_id
            ];
        }

        if (!empty($customModuleVarNew)) {
            DB::table('custom_module_vars')
                ->insert($customModuleVarNew);
        }
    }
}
