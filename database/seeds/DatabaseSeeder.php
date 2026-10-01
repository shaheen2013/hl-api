<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GenesisBookingEnginesTableSeeder::class);
        $this->call(GenesisCustomTextsTableSeeder::class);
        $this->call(GenesisCustomVarsTableSeeder::class);
        $this->call(GenesisBrandCustomTextsTableSeeder::class);
        $this->call(GenesisBrandCustomVarsTableSeeder::class);
        $this->call(GenesisBrandTypesTableSeeder::class);
        $this->call(GenesisCategoriaOfertaTableSeeder::class);
        $this->call(GenesisControladoresTableSeeder::class);
        $this->call(GenesisCountryLangsTableSeeder::class);
        $this->call(GenesisCustomContentTableSeeder::class);
        $this->call(GenesisCustomContentStateTableSeeder::class);
        $this->call(GenesisCustomModuleTableSeeder::class);
        $this->call(GenesisCustomContentModulesTableSeeder::class);
        $this->call(GenesisCustomModuleTextsTableSeeder::class);
        $this->call(GenesisCustomModuleVarsTableSeeder::class);
        $this->call(GenesisExternalApisTableSeeder::class);
        $this->call(GenesisExternalApisParamsTableSeeder::class);
        $this->call(GenesisHotelStaffRolesTableSeeder::class);
        $this->call(GenesisHotelStaffPermisosTableSeeder::class);
        $this->call(GenesisLangTableSeeder::class);
        $this->call(GenesisLanguagesTableSeeder::class);
        $this->call(GenesisMarketingMultipliersTableSeeder::class);
        $this->call(GenesisOfferPlatformsTableSeeder::class);
        $this->call(GenesisOfferTriggersTableSeeder::class);
        $this->call(GenesisOrigenCuponTableSeeder::class);
        $this->call(GenesisPaisesTableSeeder::class);
        $this->call(GenesisPrivateLoginTableSeeder::class);
        $this->call(GenesisProductsTableSeeder::class);
        $this->call(GenesisTimeZoneTableSeeder::class);
        $this->call(GenesisTiposOfertaTableSeeder::class);
        $this->call(GenesisWifiProvidersTableSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(EprivacyCustomContentSeeder::class);
        $this->call(TreatmentProtocolSeeder::class);
        $this->call(NewAccessType::class);
        $this->call(NewSocialMedia::class);
        $this->call(ShareTypes::class);
        $this->call(DeviceBlacklistSeeder::class);
        $this->call(CreateDefaultQuestionsAndCategories::class);
        $this->call(EmailTypeSeeder::class);
        $this->call(ProductConfigSeeder::class);
        $this->call(DemoDataSeeder::class);
    }
}
