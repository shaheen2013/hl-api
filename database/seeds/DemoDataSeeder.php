<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Cadena;
use App\Hotel;
use App\HotelGuid;
use App\LangHotel;
use App\Brand;
use App\HotelStaffRole;
use App\HotelStaff;
use App\HotelStaffHotels;
use App\HotelStaffPermission;
use App\User;
use App\UserGuid;
use App\UserHotel;
use App\UserVisits;
use App\ConnectionHistory;
use App\Satisfaction;
use App\Survey;
use App\SurveyQuestion;
use App\QuestionBrand;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds for demo & role-wise users.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Seeding Role-wise Users and Demo Data...");

        // 1. Seed Roles in hotel_staff_roles
        $roles = [
            1 => ['role_es' => 'Administrador de cuenta', 'role_en' => 'Account Admin'],
            2 => ['role_es' => 'Administrador de establecimientos', 'role_en' => 'Brand Admin'],
            3 => ['role_es' => 'Personal', 'role_en' => 'Staff']
        ];
        foreach ($roles as $id => $roleData) {
            HotelStaffRole::updateOrCreate(['id' => $id], $roleData);
        }

        // 2. Ensure Cadena (Chain)
        $chain = Cadena::updateOrCreate(
            ['id' => 1],
            [
                'nombre'    => 'Grand Medius Hotels & Resorts',
                'email'     => 'chain@hotelinking.com',
                'password'  => sha1('secret'),
            ]
        );

        // Chain Brand
        $chainBrand = Brand::updateOrCreate(
            ['id' => 1],
            [
                'uuid'          => '11111111-1111-1111-1111-111111111111',
                'chain_id'      => 1,
                'hotel_id'      => null,
                'parent_id'     => null,
                'brand_type_id' => 1,
            ]
        );

        // 3. Ensure Hotels
        $hotel1 = Hotel::updateOrCreate(
            ['id' => 1],
            [
                'hotelName'         => 'Mediusware Grand Resort Palma',
                'email'             => 'hotel@hotelinking.com',
                'password'          => sha1('secret'),
                'city'              => 'Palma de Mallorca',
                'place_name'        => 'Palma',
                'place_country'     => 'ES',
                'website'           => 'https://hotelinking.com',
                'telefonoReservas'  => '+34 971 000 000',
                'logo'              => 'logo-demo.png',
                'lang'              => 'en',
                'verificado'        => 1,
                'activated'         => 1,
                'stay_time'         => 5,
                'loyalty_min_visits'=> 2,
            ]
        );
        HotelGuid::updateOrCreate(['id_hotel' => 1], ['guid' => 'demo-guid-resort-001']);
        LangHotel::updateOrCreate(['id_hotel' => 1, 'id_lang' => 2]);

        // Hotel 1 Brand
        $hotel1Brand = Brand::updateOrCreate(
            ['id' => 2],
            [
                'uuid'          => '22222222-2222-2222-2222-222222222222',
                'chain_id'      => null,
                'hotel_id'      => 1,
                'parent_id'     => 1,
                'brand_type_id' => 2,
            ]
        );

        // Hotel 2
        $hotel2 = Hotel::updateOrCreate(
            ['id' => 2],
            [
                'hotelName'         => 'Mediusware Boutique Hotel Barcelona',
                'email'             => 'hotel2@hotelinking.com',
                'password'          => sha1('secret'),
                'city'              => 'Barcelona',
                'place_name'        => 'Barcelona',
                'place_country'     => 'ES',
                'website'           => 'https://hotelinking.com',
                'telefonoReservas'  => '+34 930 000 000',
                'logo'              => 'logo-demo.png',
                'lang'              => 'en',
                'verificado'        => 1,
                'activated'         => 1,
                'stay_time'         => 4,
                'loyalty_min_visits'=> 2,
            ]
        );
        HotelGuid::updateOrCreate(['id_hotel' => 2], ['guid' => 'demo-guid-boutique-002']);
        LangHotel::updateOrCreate(['id_hotel' => 2, 'id_lang' => 2]);

        // Hotel 2 Brand
        $hotel2Brand = Brand::updateOrCreate(
            ['id' => 3],
            [
                'uuid'          => '33333333-3333-3333-3333-333333333333',
                'chain_id'      => null,
                'hotel_id'      => 2,
                'parent_id'     => 1,
                'brand_type_id' => 2,
            ]
        );

        // Link Chain and Hotels
        DB::table('cadena_hotel')->updateOrInsert(['id_cadena' => 1, 'id_hotel' => 1]);
        DB::table('cadena_hotel')->updateOrInsert(['id_cadena' => 1, 'id_hotel' => 2]);

        // 4. Hotel Staff Role Users (Account Admin, Brand Admin, Staff)
        $staffUsers = [
            [
                'id'        => 1,
                'nombre'    => 'Mediusware Account Admin',
                'email'     => 'admin@hotelinking.com',
                'password'  => sha1('secret'),
                'id_role'   => 1, // Account Admin
                'activo'    => 1,
                'deleted'   => 0,
                'hotel_id'  => 1,
            ],
            [
                'id'        => 2,
                'nombre'    => 'Mediusware Brand Admin',
                'email'     => 'brandadmin@hotelinking.com',
                'password'  => sha1('secret'),
                'id_role'   => 2, // Brand Admin
                'activo'    => 1,
                'deleted'   => 0,
                'hotel_id'  => 1,
            ],
            [
                'id'        => 3,
                'nombre'    => 'Mediusware Staff Member',
                'email'     => 'staff@hotelinking.com',
                'password'  => sha1('secret'),
                'id_role'   => 3, // Staff
                'activo'    => 1,
                'deleted'   => 0,
                'hotel_id'  => 1,
            ],
        ];

        foreach ($staffUsers as $staff) {
            $hotelId = $staff['hotel_id'];
            unset($staff['hotel_id']);
            HotelStaff::updateOrCreate(['id' => $staff['id']], $staff);
            DB::table('hotel_staff_hotels')->updateOrInsert(
                ['hotel_staff_id' => $staff['id'], 'hotel_id' => $hotelId]
            );
        }

        // Grant comprehensive controller permissions to all 3 staff roles
        $allControllers = DB::table('controladores')->pluck('id')->toArray();
        if (empty($allControllers)) {
            // Default essential controllers if table is empty
            $defaultCtrlNames = [
                'hotel-home', 'hotel-profile', 'clients', 'clients-profile', 'gestion-usuarios',
                'hotel-gestion-ofertas', 'hotel-crear-oferta', 'hotel-detalle-oferta', 'staff-management',
                'hotel-satisfaction', 'referrals-home', 'referrals', 'campaigns-dashboard', 'chain-management'
            ];
            foreach ($defaultCtrlNames as $idx => $cname) {
                $ctrlId = $idx + 1;
                DB::table('controladores')->updateOrInsert(['id' => $ctrlId], ['controlador' => $cname]);
                $allControllers[] = $ctrlId;
            }
        }

        foreach ([1, 2, 3] as $roleId) {
            foreach ($allControllers as $cId) {
                DB::table('hotel_staff_permisos')->updateOrInsert(
                    ['id_staff_role' => $roleId, 'id_controller' => $cId],
                    ['default' => ($cId == 1 || $cId == 32 || $cId == 70) ? 1 : 0]
                );
            }
        }

        // 5. Seed Guest / Customer Users
        DB::table('users')->updateOrInsert(
            ['id' => 1],
            [
                'user_card'        => 'CARD001',
                'nombre'           => 'John Doe',
                'email'            => 'guest@hotelinking.com',
                'pass'             => sha1('secret'),
                'created'          => now(),
                'verificado'       => 1,
                'pais'             => 'ES',
                'provincia'        => 'Palma',
                'location'         => 'Palma de Mallorca',
                'lang'             => 'en',
                'fecha_nacimiento' => '1990-01-15',
                'sexo'             => 'male',
                'unsubscribed'     => 0,
            ]
        );
        UserGuid::updateOrCreate(['id_usuario' => 1], ['guid' => 'demo-user-guid-001']);
        DB::table('user_hotels')->updateOrInsert(['id_usuario' => 1, 'id_hotel' => 1]);

        // Insert additional fake guests for analytics
        $fakeGuests = [
            ['id' => 2, 'nombre' => 'Sarah Connor', 'email' => 'sarah.c@example.com', 'card' => 'CARD002', 'sex' => 'female'],
            ['id' => 3, 'nombre' => 'Alex Murphy', 'email' => 'alex.m@example.com', 'card' => 'CARD003', 'sex' => 'male'],
            ['id' => 4, 'nombre' => 'Elena Rostova', 'email' => 'elena.r@example.com', 'card' => 'CARD004', 'sex' => 'female'],
            ['id' => 5, 'nombre' => 'David Kim', 'email' => 'david.k@example.com', 'card' => 'CARD005', 'sex' => 'male'],
        ];

        foreach ($fakeGuests as $fg) {
            DB::table('users')->updateOrInsert(['id' => $fg['id']], [
                'user_card'        => $fg['card'],
                'nombre'           => $fg['nombre'],
                'email'            => $fg['email'],
                'pass'             => sha1('secret'),
                'created'          => now()->subDays(rand(10, 200)),
                'verificado'       => 1,
                'pais'             => 'ES',
                'provincia'        => 'Palma',
                'location'         => 'Palma de Mallorca',
                'lang'             => 'en',
                'fecha_nacimiento' => '1992-05-20',
                'sexo'             => $fg['sex'],
                'unsubscribed'     => 0,
            ]);
            UserGuid::updateOrCreate(['id_usuario' => $fg['id']], ['guid' => 'demo-user-guid-00' . $fg['id']]);
            DB::table('user_hotels')->updateOrInsert(['id_usuario' => $fg['id'], 'id_hotel' => 1]);

            // Add visits
            DB::table('users_visits')->updateOrInsert(
                ['user_id' => $fg['id'], 'hotel_id' => 1],
                ['chain_id' => 1, 'num_visits' => rand(1, 5), 'recurrent' => 1]
            );
        }

        // Add hotel satisfaction configs
        DB::table('hotel_satisfaction')->updateOrInsert(
            ['id_hotel' => 1],
            [
                'diasEnvio'            => 15,
                'puntMin'              => 5,
                'review_average_score' => 5,
                'ignoreRating'         => 0,
                'sendThanksMail'       => 1,
                'sendToNonCustomers'   => 0,
                'send_hour'            => 0,
                'total_followup_email' => 0
            ]
        );
        DB::table('hotel_review')->updateOrInsert(
            ['id_hotel' => 1],
            [
                'diasEnvio'    => 15,
                'ignoreRating' => 0
            ]
        );

        $this->command->info("Demo Role-wise Users seeded successfully!");
        $this->command->table(
            ['Role', 'Email', 'Password', 'Target Login'],
            [
                ['Account Admin (Staff)', 'admin@hotelinking.com', 'secret', 'Hotel / Staff Portal'],
                ['Brand Admin (Staff)', 'brandadmin@hotelinking.com', 'secret', 'Hotel / Staff Portal'],
                ['Staff Member', 'staff@hotelinking.com', 'secret', 'Hotel / Staff Portal'],
                ['Hotel Owner / Admin', 'hotel@hotelinking.com', 'secret', 'Hotel Direct Portal'],
                ['Chain Owner / Admin', 'chain@hotelinking.com', 'secret', 'Chain Management Portal'],
                ['Guest / End User', 'guest@hotelinking.com', 'secret', 'Loyalty / Guest Portal'],
            ]
        );
    }
}
