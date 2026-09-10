<?php

use Illuminate\Database\Seeder;
use App\WifiProviders;

class GenesisWifiProvidersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        WifiProviders::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'ruckus'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'prinsotel'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'cisco_meraki_cts'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'cisco_meraki_sos'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'netllar'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'colubris'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'nomadix'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 8],
            [
                'name' => 'cyberhoteles'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 9],
            [
                'name' => 'mikrotik'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 10],
            [
                'name' => 'unifi'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 11],
            [
                'name' => '4ipnet'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 12],
            [
                'name' => 'aerohive'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 13],
            [
                'name' => 'pfsense'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 14],
            [
                'name' => 'fortinet_protur'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 15],
            [
                'name' => 'fortinet'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 16],
            [
                'name' => 'hp'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 17],
            [
                'name' => 'mikrotik_artjoc'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 18],
            [
                'name' => 'kubi_lite'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 19],
            [
                'name' => 'tp_link'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 20],
            [
                'name' => 'aruba_center'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 21],
            [
                'name' => 'ruckus_vz'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 22],
            [
                'name' => 'zyxel'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 23],
            [
                'name' => 'grase'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 24],
            [
                'name' => 'aerohive_mac'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 25],
            [
                'name' => 'cyberhoteles_roomNumber'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 26],
            [
                'name' => 'mikrotik_belive'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 27],
            [
                'name' => 'aerohive_trh'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 28],
            [
                'name' => 'netllar_insotel'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 29],
            [
                'name' => 'teldat'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 30],
            [
                'name' => 'mikrotik_nologin'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 31],
            [
                'name' => 'ibrowse'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 32],
            [
                'name' => 'mikrotik_hipo-hotels'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 33],
            [
                'name' => 'lancom'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 34],
            [
                'name' => 'mikrotik_codes'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 35],
            [
                'name' => 'hoteles_lopez'
            ]
        );

        WifiProviders::updateOrCreate(
            ['id' => 36],
            [
                'name' => 'mikrotik_radius'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 37],
            [
                'name' => '4ipnet_nologin'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 38],
            [
                'name' => 'tplink_omada'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 39],
            [
                'name' => 'coova_chilli'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 40],
            [
                'name' => 'zyxel_nxc2500'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 41],
            [
                'name' => 'ruckus_unleashed'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 42],
            [
                'name' => 'mikrotik_identity_room_number'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 43],
            [
                'name' => 'aerohive_mac_https'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 44],
            [
                'name' => 'cyberhoteles_stayapp'
            ]
        );
        WifiProviders::updateOrCreate(
            ['id' => 45],
            [
                'name' => 'huawei_imaster_nce_campus'
            ]
        );
    }
}
