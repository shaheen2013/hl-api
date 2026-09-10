<?php

use Illuminate\Database\Seeder;
use App\Product;

class GenesisProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Product::updateOrCreate(
            ['id' => 1],
            [
                'producto' => 'LY',
                'config_schema' => null
            ]

        );

        Product::updateOrCreate(
            ['id' => 2],
            [
                'producto' => 'RF',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 3],
            [
                'producto' => 'MK',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 4],
            [
                'producto' => 'review',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 5],
            [
                'producto' => 'satisfaction',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 6],
            [
                'producto' => 'wifi_offers',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 7],
            [
                'producto' => 'birthday_emails',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 8],
            [
                'producto' => 'pushtech',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 9],
            [
                'producto' => 'require_room_num',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 10],
            [
                'producto' => 'user_enrichment',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 11],
            [
                'producto' => 'loyalty',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 12],
            [
                'producto' => 'not_hotel',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 13],
            [
                'producto' => 'portal_pro',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 14],
            [
                'producto' => 'widget',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 15],
            [
                'producto' => 'customized_satisfaction_surveys',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 16],
            [
                'producto' => 'trans_comments',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 17],
            [
                'producto' => 'followup_mail',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 18],
            [
                'producto' => 'ip_binding',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 19],
            [
                'producto' => 'eprivacy_responsible',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 20],
            [
                'producto' => 'datamatch',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 21],
            [
                'producto' => 'autocheckin',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 22],
            [
                'producto' => 'portal_redirect',
                'config_schema' => null
            ]
        );

        Product::updateOrCreate(
            ['id' => 23],
            [
                'producto' => 'payments',
                'config_schema' => null
            ]
        );
        Product::updateOrCreate(
            ['id' => 24],
            [
                'producto' => 'portal',
                'config_schema' => json_encode([
                    "\$schema" => "http://json-schema.org/draft-07/schema#",
                    "type" => "object",
                    "properties" => [
                        "phone_number" => [
                            "type" => "boolean",
                            "title" => "Phone Number"
                        ],
                        "hide_unsubscribed_clients" => [
                            "type" => "boolean",
                            "title" => "Hide Unsubscribe"
                        ],
                        "commercial_profile" => [
                            "type" => "boolean",
                            "title" => "Commercial profile"
                        ],
                        "url_redirect" => [
                            "type" => "string",
                            "title" => "URL Redirect",
                            "maxLength" => 55
                        ],
                        "show_radius_ticket_to_hosted" => [
                            "type" => "boolean",
                            "title" => "Show Radius Ticket to Hosted Guests"
                        ],
                        "only_hosted_guests" => [
                            "type" => "boolean",
                            "title" => "Only Hosted Guests"
                        ],
                        "has_access_to_hotspot" => [
                            "type" => "boolean",
                            "title" => "Has Access to Hotspot"
                        ],
                        "add_other_option_on_gender" => [
                            "type" => "boolean",
                            "title" => "Add Other option on Gender"
                        ],
                        "remove_gender_field" => [
                            "type" => "boolean",
                            "title" => "Remove Gender field"
                        ],
                    ],
                ])
            ]
        );
        Product::updateOrCreate(
            ['id' => 25],
            [
                'producto' => 'autocheckout',
                'config_schema' => null
            ]
        );
        Product::updateOrCreate(
            ['id' => 26],
            [
                'producto' => 'choose_my_room',
                'config_schema' => null
            ]
        );
        Product::updateOrCreate(
            ['id' => 27],
            [
                'producto' => 'wifibot',
                'config_schema' => null
            ]
        );
    }
}
