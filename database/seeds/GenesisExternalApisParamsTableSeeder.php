<?php

use Illuminate\Database\Seeder;
use App\ExternalApisParam;

class GenesisExternalApisParamsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        ExternalApisParam::updateOrCreate(
            ['id' => 1],
            [
                'id_api'   => 1,
                'action'   => 'campaign_list',
                'endpoint' => 'campaigns',
                'params'   => '/[:status]/[:schedule_type]/[:limit]/[:order]',
                'method'   => 'GET'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 2],
            [
                'id_api'   => 1,
                'action'   => 'create_product',
                'endpoint' => 'products',
                'params'   => '/:UUID/:name/:price/:currency',
                'method'   => 'POST'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 3],
            [
                'id_api'   => 1,
                'action'   => 'create_user',
                'endpoint' => 'contact',
                'params'   => '/:name_first/[:name_last]/:user_id/:id_hotelinking/[:city]/:gender/:language/[:facebook_id]/[:facebook_friends]/:born_date/:email/:id_hotel_chain/:last_hotel_id/:last_hotel_name/:connection_DATE/[:id_hotels]/[:room_id]/[:chain_visits]/[:hotels_visits]/[:satisfaction_score]/[:pms_id]/[:interests]/[:booking_channel]/[:nationality]/:[is_customer]/[:housingScheme]/[:res_adults]/[:res_children]/[:res_babies]/[:res_seniors]/[:res_nights]/[:res_ttoo]/[:canal_id]/[:res_date]',
                'method'   => 'POST'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 4],
            [
                'id_api'   => 1,
                'action'   => 'current_balance',
                'endpoint' => 'balance/current',
                'params'   => null,
                'method'   => 'GET'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 5],
            [
                'id_api'   => 1,
                'action'   => 'export_bulk',
                'endpoint' => 'contact/bulk',
                'params'   => '/:webhook_url/:contacts{{/:name_first/:name_last/:user_id/:city/:gender/:language/[:facebook_id]/[:facebook_friends]/:born_date/:email/:id_hotel_chain/:id_hotel/:hotel_name/:conexion_hl_DATE/:conexions_wifi_NUMBER/:booking_DATE/:check_in_DATE/}}',
                'method'   => 'POST'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 6],
            [
                'id_api'   => 1,
                'action'   => 'get_user',
                'endpoint' => 'contact/:id_pushtech',
                'params'   => null,
                'method'   => 'GET'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 7],
            [
                'id_api'   => 1,
                'action'   => 'get_user_list',
                'endpoint' => 'contact',
                'params'   => null,
                'method'   => 'GET'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 8],
            [
                'id_api'   => 1,
                'action'   => 'update_user',
                'endpoint' => 'contact/:id_pushtech',
                'params'   => '/[:name_first]/[:name_last]/[:user_id]/[:id_hotelinking]/[:city]/[:gender]/[:language]/[:facebook_id]/[:facebook_friends]/[:born_date]/[:email]/[:id_hotel_chain]/[:last_hotel_id]/[:last_hotel_name]/[:connection_DATE]/[:connection_wifi_NUMBER]/[:room_id]/[:chain_visits]/[:hotels_visits]/[:satisfaction_score]/[:pms_id]/[:pms_interest]/[:booking_channel]/[:nationality]/[:is_customer]/[:housingScheme]/[:res_adults]/[:res_children]/[:res_babies]/[:res_seniors]/[:res_nights]/[:res_ttoo]/[:canal_id]/[:res_date]',
                'method'   => 'PUT'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 9],
            [
                'id_api'   => 2,
                'action'   => 'get_user',
                'endpoint' => 'person.json',
                'params'   => '/:email',
                'method'   => 'GET'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 10],
            [
                'id_api'   => 1,
                'action'   => 'add_custom_field',
                'endpoint' => 'custom_fields',
                'params'   => null,
                'method'   => 'POST'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 11],
            [
                'id_api'   => 1,
                'action'   => 'delete_user',
                'endpoint' => 'contact/:id_pushtech',
                'params'   => null,
                'method'   => 'DELETE'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 12],
            [
                'id_api'   => 1,
                'action'   => 'update_pms_records',
                'endpoint' => 'contact/:id_pushtech',
                'params'   => '/[:pms_id]/[:pms_interest]/[:booking_channel]/[:nationality]',
                'method'   => 'PUT'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 13],
            [
                'id_api'   => 1,
                'action'   => 'bulk_update_users',
                'endpoint' => 'contact/bulk',
                'params'   => '/:webhook_url/:contacts{{/[:pms_id]/[:pms_interest]/[:booking_channel]/[:nationality]}}',
                'method'   => 'POST'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 14],
            [
                'id_api'   => 1,
                'action'   => 'bulk_create_users',
                'endpoint' => 'contact/bulk',
                'params'   => '/:webhook_url/:contacts{{/:name_first/[:name_last]/:user_id/:id_hotelinking/[:city]/:gender/:language/[:facebook_id]/[:facebook_friends]/:born_date/:email/:id_hotel_chain/:last_hotel_id/:last_hotel_name/:connection_DATE/[:room_id]/[:chain_visits]/[:hotels_visits]/[:satisfaction_score]/[:pms_id]/[:pms_interest]/[:booking_channel]/[:nationality]/:[is_customer]}}',
                'method'   => 'POST'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 15],
            [
                'id_api'   => 1,
                'action'   => 'get_bulk',
                'endpoint' => 'contact/bulk/:idBulk',
                'params'   => null,
                'method'   => 'GET'
            ]
        );

        ExternalApisParam::updateOrCreate(
            ['id' => 16],
            [
                'id_api'   => 1,
                'action'   => 'update_user_satisfac',
                'endpoint' => 'contact/:id_pushtech',
                'params'   => '/[:satisfaction_score]',
                'method'   => 'PUT'
            ]
        );
    }
}
