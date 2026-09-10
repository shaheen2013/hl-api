<?php

namespace App\Http\Resources\ProductsConfigurations;

use Illuminate\Http\Resources\Json\JsonResource;

class AutocheckinResource extends JsonResource
{
    public function __construct($resource, $paymentsActive)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);

        $this->resource = $resource;
        $this->paymentsActive = $paymentsActive->active ?? 0;
    }
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $actualBrandIdentification = isset($this->identification) ? $this->identification : [];
        $defaultIdentificationConfiguration = config('autocheckin.default_configuration.identification');
        $identification = array_replace_recursive($defaultIdentificationConfiguration, $actualBrandIdentification);

        return [
            'data' => [
                'active'                                => $this->active,
                'paymentsActive'                        => $this->paymentsActive,
                'identification'                        => $identification ?? [],
                'max_attempts_reservation'              => $this->max_attempts_reservation ?? config('autocheckin.default_configuration.max_attempts_reservation'),
                'child_required_identity_documents_age' => $this->child_required_identity_documents_age ?? config('autocheckin.default_configuration.child_required_identity_documents_age'),
                'max_attempts_child'                    => $this->max_attempts_child ?? config('autocheckin.default_configuration.max_attempts_child'),
                'max_attempts_document'                 => $this->max_attempts_document ?? config('autocheckin.default_configuration.max_attempts_document'),
                'partial_checkin'                       => $this->partial_checkin ?? config('autocheckin.default_configuration.partial_checkin'),
                'room_type_selection'                   => $this->room_type_selection ?? config('autocheckin.default_configuration.room_type_selection'),
                'telephone'                             => $this->telephone ?? config('autocheckin.default_configuration.telephone'),
                'telephone_notifications'               => $this->telephone_notifications ?? config('autocheckin.default_configuration.telephone_notifications'),
                'max_attempts_telephone'                => $this->max_attempts_telephone ?? config('autocheckin.default_configuration.max_attempts_telephone'),
                'comments'                              => $this->comments ?? config('autocheckin.default_configuration.comments'),
                'show_comments_only_on_holder'          => $this->show_comments_only_on_holder ?? config('autocheckin.default_configuration.show_comments_only_on_holder'),
                'signed_documents'                      => $this->signed_documents ?? config('autocheckin.default_configuration.signed_documents'),
                'optional_scan'                         => $this->optional_scan ?? config('autocheckin.default_configuration.optional_scan'),
                'advanced_scan'                         => $this->advanced_scan ?? config('autocheckin.default_configuration.advanced_scan'),
                'custom_scan_text'                      => $this->custom_scan_text ?? config('autocheckin.default_configuration.custom_scan_text'),
                'custom_comments_text'                  => $this->custom_comments_text ?? config('autocheckin.default_configuration.custom_comments_text'),
                'send_identity_documents_to_PMS'        => $this->send_identity_documents ?? config('autocheckin.default_configuration.send_identity_documents'),
                'time_limit_checkin'                    => $this->time_limit_checkin ?? config('autocheckin.default_configuration.time_limit_checkin'),
                'close_time_limit_checkin'              => $this->close_time_limit_checkin ?? config('autocheckin.default_configuration.close_time_limit_checkin'),
                'scan_children_like_adults'             => $this->scan_children_like_adults ?? config('autocheckin.default_configuration.scan_children_like_adults'),
                'children_process_on_reception'         => $this->children_process_on_reception ?? config('autocheckin.default_configuration.children_process_on_reception'),
                'custom_confirmation_text'              => $this->custom_confirmation_text ?? config('autocheckin.default_configuration.custom_confirmation_text'),
                'send_identity_documents_to_reception'  => $this->send_identity_documents_to_reception ?? config('autocheckin.default_configuration.send_identity_documents_to_reception'),
                'send_signed_documents_to_reception'    => $this->send_signed_documents_to_reception ?? config('autocheckin.default_configuration.send_signed_documents_to_reception'),
                'show_holder'                           => $this->show_holder ?? config('autocheckin.default_configuration.show_holder'),
                'allow_expired_documents'               => $this->allow_expired_documents ?? config('autocheckin.default_configuration.allow_expired_documents'),
                'not_allow_passports_from_country_brand' => $this->not_allow_passports_from_country_brand ?? config('autocheckin.default_configuration.not_allow_passports_from_country_brand'),
                'redirect_link'                         => $this->redirect_link ?? config('autocheckin.default_configuration.redirect_link'),
                'reservation_holder_not_modifiable'     => $this->reservation_holder_not_modifiable ?? config('autocheckin.default_configuration.reservation_holder_not_modifiable'),
                'custom_gdpr_text'                      => $this->custom_gdpr_text ?? config('autocheckin.default_configuration.custom_gdpr_text'),
                'show_qr_code'                          => $this->show_qr_code ?? config('autocheckin.default_configuration.show_qr_code'),
                'reception_signature'                   => $this->reception_signature ?? config('autocheckin.default_configuration.reception_signature'),
                'show_modal_in_confirmation_page'       => $this->show_modal_in_confirmation_page ?? config('autocheckin.default_configuration.show_modal_in_confirmation_page'),
                'activate_time_limit'                   => $this->activate_time_limit ?? config('autocheckin.default_configuration.activate_time_limit'),
                'send_email_checkin_available'          => $this->send_email_checkin_available ?? config('autocheckin.default_configuration.send_email_checkin_available'),
                'disable_address_autocomplete'          => $this->disable_address_autocomplete ?? config('autocheckin.default_configuration.disable_address_autocomplete'),
                'disable_only_address_autocomplete'     => $this->disable_only_address_autocomplete ?? config('autocheckin.default_configuration.disable_only_address_autocomplete'),
                'token_key'                             => $this->token_key ?? config('autocheckin.default_configuration.token_key'),
                'children_sign_documents'               => $this->children_sign_documents ?? config('autocheckin.default_configuration.children_sign_documents'),
                'show_save_phone_in_database_checkbox'  => $this->show_save_phone_in_database_checkbox ?? config('autocheckin.default_configuration.show_save_phone_in_database_checkbox'),
                'show_send_newsletter_checkbox'         => $this->show_send_newsletter_checkbox ?? config('autocheckin.default_configuration.show_send_newsletter_checkbox'),
                'custom_phone_text'                     => $this->custom_phone_text ?? config('autocheckin.default_configuration.custom_phone_text'),
                'allow_driving_license' => $this->allow_driving_license ?? config('autocheckin.default_configuration.allow_driving_license'),
                'identity_document_signature_required'  => $this->identity_document_signature_required ?? config('autocheckin.default_configuration.identity_document_signature_required'),
                'disable_scan'                          => $this->disable_scan ?? config('autocheckin.default_configuration.disable_scan'),
                'child_data_with_holder'                => $this->child_data_with_holder ?? config('autocheckin.default_configuration.child_data_with_holder'),
                'disable_send_documents_page'                 => $this->disable_send_documents_page ?? config('autocheckin.default_configuration.disable_send_documents_page'),
                'arrival_time'                          => $this->arrival_time ?? config('autocheckin.default_configuration.arrival_time'),
                'second_surname_required_for_spanish'   => $this->second_surname_required_for_spanish ?? config('autocheckin.default_configuration.second_surname_required_for_spanish'),
                'use_send_document_email_in_precheck'   => $this->use_send_document_email_in_precheck ?? config('autocheckin.default_configuration.use_send_document_email_in_precheck'),
                'region_and_subregion_with_iso_codes'   => $this->region_and_subregion_with_iso_codes ?? config('autocheckin.default_configuration.region_and_subregion_with_iso_codes'),

                'email_mandatory_on_holder'             => $this->email_mandatory_on_holder ?? config('autocheckin.default_configuration.email_mandatory_on_holder'),
                'remove_duplicated_emails'              => $this->remove_duplicated_emails ?? config('autocheckin.default_configuration.remove_duplicated_emails'),
                'email_or_phone_mandatory'              => $this->email_or_phone_mandatory ?? config('autocheckin.default_configuration.email_or_phone_mandatory'),
            ]
        ];
    }
}
