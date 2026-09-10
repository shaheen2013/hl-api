<?php

use Illuminate\Database\Seeder;
use App\Controller;

class GenesisControladoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Controller::updateOrCreate(
            ['id' => 1],
            [
                'controlador'      => '404',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0

            ]
        );

        Controller::updateOrCreate(
            ['id' => 2],
            [
                'controlador'      => 'add-staff',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0

            ]
        );

        Controller::updateOrCreate(
            ['id' => 3],
            [
                'controlador'      => 'admin',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0

            ]
        );

        Controller::updateOrCreate(
            ['id' => 4],
            [
                'controlador'      => 'cadena-hotelera',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 5],
            [
                'controlador'      => 'campaigns-dashboard',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 6],
            [
                'controlador'      => 'chain-details',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 7],
            [
                'controlador'      => 'chain-management',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 8],
            [
                'controlador'      => 'checkin-paso1',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 9],
            [
                'controlador'      => 'checkin-paso2',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 10],
            [
                'controlador'      => 'checkout-ends',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 11],
            [
                'controlador'      => 'checkout-thanks',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 12],
            [
                'controlador'      => 'checkout-user',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 13],
            [
                'controlador'      => 'checkout',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 14],
            [
                'controlador'      => 'cupon',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 15],
            [
                'controlador'      => 'create-account',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 16],
            [
                'controlador'      => 'digital-loyalty-program',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 17],
            [
                'controlador'      => 'digital-loyalty-program-thanks',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 18],
            [
                'controlador'      => 'edit-staff',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 19],
            [
                'controlador'      => 'gestion-encuestas-timeline',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 20],
            [
                'controlador'      => 'gestion-encuestas',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 21],
            [
                'controlador'      => 'gestion-usuarios',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 22],
            [
                'controlador'      => 'guest-trends',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 23],
            [
                'controlador'      => 'hotel-change-password',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 24],
            [
                'controlador'      => 'hotel-crear-detalle-oferta',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 25],
            [
                'controlador'      => 'hotel-crear-oferta',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 26],
            [
                'controlador'      => 'hotel-detalle-oferta',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 27],
            [
                'controlador'      => 'hotel-eliminar-oferta',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 28],
            [
                'controlador'      => 'hotel-gestion-ofertas',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 29],
            [
                'controlador'      => 'hotel-guardar-oferta',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 30],
            [
                'controlador'      => 'hotel-home',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 31],
            [
                'controlador'      => 'hotel-landing',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 32],
            [
                'controlador'      => 'hotel-listado-cupones',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 33],
            [
                'controlador'      => 'hotel-login',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 34],
            [
                'controlador'      => 'hotel-profile-2',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 35],
            [
                'controlador'      => 'hotel-profile-3',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 36],
            [
                'controlador'      => 'hotel-profile-datos-de-reserva',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 37],
            [
                'controlador'      => 'hotel-profile-datos-landing',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 38],
            [
                'controlador'      => 'hotel-profile',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 39],
            [
                'controlador'      => 'hotel-publicar-oferta',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 40],
            [
                'controlador'      => 'hotel-security-preferences',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 41],
            [
                'controlador'      => 'hotel-staff-profile',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 42],
            [
                'controlador'      => 'hotel-welcome',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 43],
            [
                'controlador'      => 'hotel',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 44],
            [
                'controlador'      => 'inicio',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 45],
            [
                'controlador'      => 'invitar-usuarios-2',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 46],
            [
                'controlador'      => 'invitar-usuarios-result',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 47],
            [
                'controlador'      => 'invitar-usuarios',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 48],
            [
                'controlador'      => 'invite-thanks-hotel',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 49],
            [
                'controlador'      => 'invite-thanks',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 50],
            [
                'controlador'      => 'invite-users-drafts',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 51],
            [
                'controlador'      => 'leads-dashboard',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 52],
            [
                'controlador'      => 'login',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 53],
            [
                'controlador'      => 'logout',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 54],
            [
                'controlador'      => 'oferta',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 55],
            [
                'controlador'      => 'oferta-gracias',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 56],
            [
                'controlador'      => 'privacy',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 57],
            [
                'controlador'      => 'private-invitar-hotel',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 58],
            [
                'controlador'      => 'private-login',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 59],
            [
                'controlador'      => 'private-navbar',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 60],
            [
                'controlador'      => 'redeem-ko',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 61],
            [
                'controlador'      => 'redeem-ok',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 62],
            [
                'controlador'      => 'referrals-dashboard',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 63],
            [
                'controlador'      => 'referrals',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 64],
            [
                'controlador'      => 'referrals-details',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 65],
            [
                'controlador'      => 'referral-goals',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 66],
            [
                'controlador'      => 'referrers',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 67],
            [
                'controlador'      => 'reputation-dashboard',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 68],
            [
                'controlador'      => 'scrapper',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 69],
            [
                'controlador'      => 'staff-management',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 70],
            [
                'controlador'      => 'staff-profile',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 71],
            [
                'controlador'      => 'test',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 72],
            [
                'controlador'      => 'tienda',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 73],
            [
                'controlador'      => 'user-change-password',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 74],
            [
                'controlador'      => 'user-home',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 75],
            [
                'controlador'      => 'user-landing',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 76],
            [
                'controlador'      => 'user-ofertas',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 77],
            [
                'controlador'      => 'user-points-log',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 78],
            [
                'controlador'      => 'user-points',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 79],
            [
                'controlador'      => 'user-preferences',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 80],
            [
                'controlador'      => 'user-profile-2',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 81],
            [
                'controlador'      => 'user-profile-3',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 82],
            [
                'controlador'      => 'user-profile-4',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 83],
            [
                'controlador'      => 'user-profile',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 84],
            [
                'controlador'      => 'user-survey-2',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 85],
            [
                'controlador'      => 'user-survey-3',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 86],
            [
                'controlador'      => 'user-survey-list',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 87],
            [
                'controlador'      => 'user-survey-thanks',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 88],
            [
                'controlador'      => 'user-survey',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 89],
            [
                'controlador'      => 'user-tarjeta',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 90],
            [
                'controlador'      => 'user-wishlist',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 91],
            [
                'controlador'      => 'user',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 92],
            [
                'controlador'      => 'faq',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 93],
            [
                'controlador'      => 'facebook-login',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 94],
            [
                'controlador'      => 'verificar-email',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 95],
            [
                'controlador'      => 'landing',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 96],
            [
                'controlador'      => 'landing-faq',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 97],
            [
                'controlador'      => 'landing-invite-hotel',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 98],
            [
                'controlador'      => 'landing-invite-thanks',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 99],
            [
                'controlador'      => 'loyalty-management',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 100],
            [
                'controlador'      => 'unsuscribe-email',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 101],
            [
                'controlador'      => 'terms-and-conditions',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 102],
            [
                'controlador'      => 'hotel-faq',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 103],
            [
                'controlador'      => 'referral-share',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 104],
            [
                'controlador'      => 'referral-share-step-2',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 105],
            [
                'controlador'      => 'no-permission',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 106],
            [
                'controlador'      => 'hotel-profile-langs',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 107],
            [
                'controlador'      => 'bajaMailing',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 108],
            [
                'controlador'      => 'redeem-offer',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 109],
            [
                'controlador'      => 'unsuscribe',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 110],
            [
                'controlador'      => 'hotel-booking-share',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 111],
            [
                'controlador'      => 'stay-share',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 112],
            [
                'controlador'      => 'chain-email',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 113],
            [
                'controlador'      => 'stay-wifi-redirect',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 114],
            [
                'controlador'      => 'hotel-profile-sm',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 115],
            [
                'controlador'      => 'referrals-home',
                'LY'               => 0,
                'RF'               => 1,
                'MK'               => 0,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 116],
            [
                'controlador'      => 'review-urls',
                'LY'               => 0,
                'RF'               => 0,
                'MK'               => 0,
                'review'           => 1,
                'satisfaction'     => 1,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 117],
            [
                'controlador'      => 'hotel-satisfaction',
                'LY'               => 0,
                'RF'               => 0,
                'MK'               => 0,
                'review'           => 0,
                'satisfaction'     => 1,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 118],
            [
                'controlador'      => 'satisfaction-survey',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 119],
            [
                'controlador'      => 'satisfaction-list',
                'LY'               => 0,
                'RF'               => 0,
                'MK'               => 0,
                'review'           => 0,
                'satisfaction'     => 1,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 120],
            [
                'controlador'      => 'create-offer-from-token',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 121],
            [
                'controlador'      => 'chain-thirdparty-integrations',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 1,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 122],
            [
                'controlador'      => 'hotel-thirdparty-integrations',
                'LY'               => 1,
                'RF'               => 1,
                'MK'               => 1,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 1,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 123],
            [
                'controlador'      => 'clients',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 0,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 124],
            [
                'controlador'      => 'satisfaction-users',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 0,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

        Controller::updateOrCreate(
            ['id' => 125],
            [
                'controlador'      => 'clients-profile',
                'LY'               => 1,
                'RF'               => 0,
                'MK'               => 0,
                'review'           => 0,
                'satisfaction'     => 0,
                'wifi_offers'      => 0,
                'birthday_emails'  => 0,
                'pushtech'         => 0,
                'require_room_num' => 0,
                'user_enrichment'  => 0,
                'loyalty'          => 0
            ]
        );

    }
}
