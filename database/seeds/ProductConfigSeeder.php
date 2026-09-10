<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ProductConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requireRoomNumberProduct = App\Product::where('producto', 'require_room_num')->first();
        App\ProductConfig::updateOrCreate(
            ['id' => 1],
            [
                'product_id' => $requireRoomNumberProduct->id,
                'label' => 'display_room_number',
                'type' => 'boolean'
            ]
        );

        $birthdayProduct = App\Product::where('producto', 'birthday_emails')->first();
        App\ProductConfig::updateOrCreate(
            ['id' => 2],
            [
                'product_id' => $birthdayProduct->id,
                'label' => 'birthday_alarm',
                'type' => 'boolean'
            ]
        );

        $widgetProduct = App\Product::where('producto', 'widget')->first();
        App\ProductConfig::updateOrCreate(
            ['id' => 3],
            [
                'product_id' => $widgetProduct->id,
                'label' => 'byChain',
                'type' => 'boolean'
            ]
        );

        $autocheckinProduct = App\Product::where('producto', 'autocheckin')->first();
        App\ProductConfig::updateOrCreate(
            ['id' => 4],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'identification',
                'type' => 'json'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 5],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'max_attempts_reservation',
                'type' => 'integer'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 6],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'child_required_identity_documents_age',
                'type' => 'integer'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 7],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'max_attempts_child',
                'type' => 'integer'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 8],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'max_attempts_document',
                'type' => 'integer'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 9],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'partial_checkin',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 10],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'room_type_selection',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 11],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'telephone',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 12],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'telephone_notifications',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 13],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'comments',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 14],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'signed_documents',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 15],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'max_attempts_telephone',
                'type' => 'integer'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 16],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'optional_scan',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 17],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'custom_scan_text',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 18],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'send_identity_documents',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 19],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'time_limit_checkin',
                'type' => 'integer'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 21],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'scan_children_like_adults',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 22],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'custom_confirmation_text',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 23],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'send_signed_documents_to_reception',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 35],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'close_time_limit_checkin',
                'type' => 'integer'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 36],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'advanced_scan',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 37],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'disable_address_autocomplete',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 55],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'disable_only_address_autocomplete',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 38],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'token_key',
                'type' => 'string'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 40],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'children_sign_documents',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 41],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'show_save_phone_in_database_checkbox',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 42],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'show_send_newsletter_checkbox',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 43],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'custom_phone_text',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 44],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'allow_driving_license',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 45],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'identity_document_signature_required',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 47],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'disable_scan',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 48],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'child_data_with_holder',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 49],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'disable_send_documents_page',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 50],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'arrival_time',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 51],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'second_surname_required_for_spanish',
                'type' => 'boolean'
            ]
        );
        $genericConfigurationProduct = App\Product::where('producto', 'portal')->first();
        App\ProductConfig::updateOrCreate(
            ['id' => 20],
            [
                'product_id' => $genericConfigurationProduct->id,
                'label' => 'hide_unsubscribed_clients',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 24],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'show_holder',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 25],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'allow_expired_documents',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 26],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'redirect_link',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 27],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'reservation_holder_not_modifiable',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 28],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'custom_gdpr_text',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 29],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'show_qr_code',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 30],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'show_modal_in_confirmation_page',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 31],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'reception_signature',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 32],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'send_identity_documents_to_reception',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 33],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'activate_time_limit',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 34],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'send_email_checkin_available',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 39],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'show_comments_only_on_holder',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 52],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'custom_comments_text',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 53],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'not_allow_passports_from_country_brand',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 54],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'children_process_on_reception',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 56],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'use_send_document_email_in_precheck',
                'type' => 'boolean'
            ]
        );

        App\ProductConfig::updateOrCreate(
            ['id' => 57],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'email_mandatory_on_holder',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 58],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'remove_duplicated_emails',
                'type' => 'boolean'
            ]
        );

         App\ProductConfig::updateOrCreate(
            ['id' => 59],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'region_and_subregion_with_iso_codes',
                'type' => 'boolean'
            ]
        );
        App\ProductConfig::updateOrCreate(
            ['id' => 61],
            [
                'product_id' => $autocheckinProduct->id,
                'label' => 'email_or_phone_mandatory',
                'type' => 'boolean'
            ]
        );
    }
}
