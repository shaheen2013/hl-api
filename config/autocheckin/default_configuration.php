<?php

$iso3166 = new League\ISO3166\ISO3166();

return [
    'identification' => [
        "reservation_inputs" => [
            [
                [
                    "active" => "true",
                    "name" => "reservation_code",
                    "type" => "text",
                    "position" => "4",
                    "minLength" => "3",
                    "maxLength" => "20",
                ],
                [
                    "active" => "true",
                    "name" => "last_name",
                    "type" => "text",
                    "position" => "2",
                    "minLength" => "2",
                    "maxLength" => "50",
                ],
                [
                    "active" => "false",
                    "position" => "5",
                    "name" => "check_in",
                    "type" => "date",
                ],
                [
                    "active" => "false",
                    "position" => "6",
                    "name" => "check_out",
                    "type" => "date",
                ],
                [
                    "active" => "false",
                    "position" => "3",
                    "name" => "email",
                    "type" => "email",
                ],
                [
                    "active" => "false",
                    "name" => "first_name",
                    "type" => "text",
                    "position" => "1",
                    "minLength" => "2",
                    "maxLength" => "50",
                ],
            ]
        ],
        "reservation_filters" => [
            [
                [
                    "position" => 1,
                    "name" => "check_in",
                    "type" => "date"
                ]
            ],
            [
                [
                    "position" => 2,
                    "name" => "check_out",
                    "type" => "date"
                ]
            ],
            [
                [
                    "position" => 4,
                    "name" => "reservation_code",
                    "type" => "text"
                ]
            ],
            [
                [
                    "position" => 3,
                    "name" => "first_name",
                    "type" => "text"
                ]
            ]
        ],
        "validate_data_scan" => [
            [
                [
                    "position" => 1,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "name",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "50"
                ],
                [
                    "position" => 2,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "surname",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "50"
                ],
                [
                    "position" => 3,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "second_surname",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "50"
                ],
                [
                    "position" => 4,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "birthday_date",
                    "type" => "date"
                ],
                [
                    "position" => 5,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "gender",
                    "type" => "select",
                    "options" => ["male", "female"]
                ],
                [
                    "position" => 7,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "document_type",
                    "type" => "select",
                    "options" => ["identity_card", "passport", "driving_license", "residence_permit"]
                ],
                [
                    "position" => 8,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "document_number",
                    "type" => "text",
                    "minLength" => "4",
                    "maxLength" => "20"
                ],
                [
                    "position" => 11,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "date_of_issue",
                    "type" => "date"
                ],
                [
                    "position" => 12,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "date_of_expiry",
                    "type" => "date"
                ],
                [
                    "position" => 6,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "nationality",
                    "type" => "autocomplete",
                    "countryInput" => 'true'
                ],
                [
                    "position" => 13,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "residence_country",
                    "type" => "autocomplete",
                    "countryInput" => 'true',
                ],
                [
                    "position" => 14,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "address",
                    "type" => "autocomplete",
                    "minLength" => "5",
                    "maxLength" => "500"
                ],
                [
                    "position" => 15,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "postal_code",
                    "type" => "text",
                    "minLength" => "3",
                    "maxLength" => "20"
                ],
                [
                    "position" => 17,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "true",
                    "name" => "CCAA",
                    "type" => "select"
                ],
                [
                    "position" => 18,
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "province",
                    "type" => "select"
                ],
                [
                    "position" => 21,
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "telephone",
                    "type" => "phone",
                    "minLength" => "2",
                    "maxLength" => "50"
                ],
                [
                    "position" => 22,
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "email",
                    "type" => "email"
                ],
                [
                    "position" => 16,
                    "active" => "true",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "municipality",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "100"
                ],
                [
                    "position" => 19,
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "region",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "100"
                ],
                [
                    "position" => 20,
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "subregion",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "100"
                ],
                [
                    "position" => 9,
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "document_support_number",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "100"
                ],
                [
                    "position" => 10,
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "fiscal_code",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "100"
                ],
                [
                    "position" => 23,
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "kinship",
                    "type" => "select",
                    "options" => ["son", "nephew", "brother", "cousin", "other"]
                ],
            ]
        ],
        "child_form" => [
            [
                [
                    "active" => "false",
                    "required" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "name" => "name",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "50"
                ],
                [
                    "active" => "false",
                    "required" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "name" => "surname",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "50"
                ],
                [
                    "active" => "true",
                    "required" => "true",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "name" => "birthday",
                    "type" => "date",
                ],
                [
                    "active" => "false",
                    "required" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "name" => "nationality",
                    "type" => "autocomplete",
                    "countryInput" => "true"
                ],
                [
                    "active" => "false",
                    "required" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "name" => "kinship",
                    "type" => "select",
                    "options" => ["son", "nephew", "brother", "cousin", "other"]
                ],
                [
                    "active" => "false",
                    "required" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "name" => "postal_code",
                    "type" => "text",
                    "minLength" => "3",
                    "maxLength" => "20"
                ],
                [
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "address",
                    "type" => "autocomplete",
                    "minLength" => "5",
                    "maxLength" => "500"
                ],
                [
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "municipality",
                    "type" => "text",
                    "minLength" => "2",
                    "maxLength" => "100"
                ],
                [
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "telephone",
                    "type" => "phone",
                    "minLength" => "2",
                    "maxLength" => "50"
                ],
                [
                    "active" => "false",
                    "fill_from_holder" => "false",
                    "hide_filled" => "false",
                    "not_fill_on_reception" => "false",
                    "required" => "false",
                    "name" => "email",
                    "type" => "email"
                ],
            ]
        ]
    ],
    "max_attempts_reservation" => 3,
    "child_required_identity_documents_age" => 18,
    "max_attempts_child" => 3,
    "max_attempts_document" => 10,
    "partial_checkin" => false,
    "room_type_selection" => false,
    "telephone" => false,
    "telephone_notifications" => false,
    "max_attempts_telephone" => 3,
    "comments" => false,
    "show_comments_only_on_holder" => false,
    "signed_documents" => false,
    "optional_scan" => false,
    "custom_scan_text" => false,
    "custom_comments_text" => false,
    "send_identity_documents" => false,
    "time_limit_checkin" => 0,
    "scan_children_like_adults" => false,
    "children_process_on_reception" => false,
    "custom_confirmation_text" => false,
    "send_signed_documents_to_reception" => false,
    "send_identity_documents_to_reception" => false,
    "show_holder" => false,
    "allow_expired_documents" => false,
    "not_allow_passports_from_country_brand" => false,
    "redirect_link" => false,
    "reservation_holder_not_modifiable" => false,
    "custom_gdpr_text" => false,
    "show_qr_code" => true,
    "reception_signature" => false,
    "show_modal_in_confirmation_page" => false,
    "activate_time_limit" => false,
    "send_email_checkin_available" => false,
    "close_time_limit_checkin" => "",
    "advanced_scan" => false,
    "token_key" => "",
    "disable_address_autocomplete" => false,
    "disable_only_address_autocomplete" => false,
    "children_sign_documents" => false,
    "show_save_phone_in_database_checkbox" => true,
    "show_send_newsletter_checkbox" => true,
    "custom_phone_text" => false,
    "allow_driving_license" => false,
    "identity_document_signature_required" => false,
    "disable_scan" => false,
    "child_data_with_holder" => false,
    "disable_send_documents_page" => false,
    "arrival_time" => false,
    "second_surname_required_for_spanish" => false,
    "use_send_document_email_in_precheck" => true,
    "region_and_subregion_with_iso_codes" => false,
    "email_mandatory_on_holder" => false,
    "remove_duplicated_emails" => false,
    "email_or_phone_mandatory" => false,

];
