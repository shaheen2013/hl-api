<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveObsoleteTablesApp extends Migration
{
    const PREFIX = 'old_';
    private $tables = [
        'cadena_benef_fideliz',
        'cadena_fideliz',
        'chain_privacy_policy',
        'dolar_puntos',
        'hotels_privacy_policy',
        'hotel_benef_fideliz',
        'hotel_fideliz',
        'hotel_list_users',
        'hotel_list',
        'hotel_user_rate',
        'hotel_oferta_wifi',
        'hotel_rooms',
        'invitaciones_users_pendientes',
        'pushtech_bulk',
        'pushtech_bulk_results',
        'pushtech_data',
        'todelete_adq_ret',
        'todelete_invitaciones_hotel_staff',
        'todelete_user_share_token',
        'user_cadena_follow',
        'user_checkin',
        'user_decoraciones',
        'user_enrichment_company',
        'user_enrichment_user_interest',
        'user_enricment_interest_relations',
        'user_enrichment_interests',
        'user_enrichment_profiles',
        'user_enrichment_sources',
        'user_extras',
        'user_hotels_follow',
        'user_hotel_gasto_noches_inicial',
        'user_money_hotel',
        'user_paises',
        'user_points',
        'user_servicios',
        'user_tipos_hab',
        'user_user_follow',
        'user_wishlist',
        'vincular_redes_sociales',
        'api',
        'offers',
        'action_points',
        'ciudad',
        'equiv_estrellas',
        'extras_hotel',
        'ips_webservice',
        'offer_method',
        'servicios_hotel',
        'subcategoria_oferta',
        'tipo_fidelizacion',
        'tipos_hab_hotel',
        'tipos_hotel',
        'tipos_hotel_decoracion',
        'user_twitter',
        'hotel_total_users',
        'last_checkout_ip',
        'hoteles_img',
        'pushtech_campaigns_mappings'
    ];


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::dropIfExists(self::PREFIX . $table);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::create(self::PREFIX . $table, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
            });
        }
    }
}
