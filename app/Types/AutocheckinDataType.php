<?php

namespace App\Types;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AutocheckinDataType
{
    protected $identification;
    protected $maxAttemptsReservation;
    protected $childRequiredIdentityDocumentsAge;
    protected $maxAttemptsChild;
    protected $maxAttemptsDocument;
    protected $partialCheckin;
    protected $roomTypeSelection;
    protected $telephone;
    protected $telephoneNotifications;
    protected $maxAttemptsTelephone;
    protected $comments;
    protected $showCommentsOnlyOnHolder;
    protected $signedDocuments;
    protected $optionalScan;
    protected $advancedScan;
    protected $customScanText;
    protected $customConfirmationText;
    protected $sendIdentityDocumentsToReception;
    protected $sendSignedDocumentsToReception;
    protected $timeLimitCheckin;
    protected $closeTimeLimitCheckin;
    protected $sendIdentityDocuments;
    protected $scanChildrenLikeAdults;
    protected $showHolder;
    protected $allowExpiredDocuments;
    protected $notAllowPassportsFromCountryBrand;
    protected $childrenProcessOnReception;
    protected $redirect_link;
    protected $reservationHolderNotModifiable;
    protected $customGdprText;
    protected $showQrCode;
    protected $reception_signature;
    protected $showModalInConfirmationPage;
    protected $activateTimeLimit;
    protected $sendEmailCheckinAvailable;
    protected $token_key;
    protected $disableAddressAutocomplete;
    protected $disableOnlyAddressAutocomplete;
    protected $children_sign_documents;
    protected $showSavePhoneInDatabaseCheckbox;
    protected $showSendNewsletterCheckbox;
    protected $customPhoneText;
    protected $allowDrivingLicense;
    protected $identityDocumentSignatureRequired;
    protected $disableScan;
    protected $childDataWithHolder;
    protected $disableSendDocumentsPage;
    protected $arrivalTime;
    protected $secondSurnameRequiredForSpanish;
    protected $useSendDocumentEmailInPrecheck;
    protected $regionAndSubregionWithIsoCodes;
    protected $emailMandatoryOnHolder;
    protected $removeDuplicatedEmails;
    protected $emailOrPhoneMandatory;
    private $productId;
    private $validatorMessages = [];
    private $rules;

    public function __construct(Request $request)
    {
        $this->identification = $request->input('identification');
        $this->maxAttemptsReservation = $request->input('max_attempts_reservation');
        $this->childRequiredIdentityDocumentsAge = $request->input('child_required_identity_documents_age');
        $this->maxAttemptsChild = $request->input('max_attempts_child');
        $this->maxAttemptsDocument = $request->input('max_attempts_document');
        $this->partialCheckin = $request->input('partial_checkin');
        $this->roomTypeSelection = $request->input('room_type_selection');
        $this->telephone = $request->input('telephone');
        $this->telephoneNotifications = $request->input('telephone_notifications');
        $this->maxAttemptsTelephone = $request->input('max_attempts_telephone');
        $this->comments = $request->input('comments');
        $this->showCommentsOnlyOnHolder = $request->input('show_comments_only_on_holder');
        $this->signedDocuments = $request->input('signed_documents');
        $this->optionalScan = $request->input('optional_scan');
        $this->advancedScan = $request->input('advanced_scan');
        $this->scanChildrenLikeAdults = $request->input('scan_children_like_adults');
        $this->customScanText = $request->input('custom_scan_text');
        $this->customCommentsText = $request->input('custom_comments_text');
        $this->customConfirmationText = $request->input('custom_confirmation_text');
        $this->sendIdentityDocumentsToReception = $request->input('send_identity_documents_to_reception');
        $this->sendSignedDocumentsToReception = $request->input('send_signed_documents_to_reception');
        $this->timeLimitCheckin = $request->input('time_limit_checkin');
        $this->closeTimeLimitCheckin = $request->input('close_time_limit_checkin');
        $this->sendIdentityDocuments = $request->input('send_identity_documents_to_PMS');
        $this->showHolder = $request->input('show_holder');
        $this->allowExpiredDocuments = $request->input('allow_expired_documents');
        $this->notAllowPassportsFromCountryBrand = $request->input('not_allow_passports_from_country_brand');
        $this->childrenProcessOnReception = $request->input('children_process_on_reception');
        $this->redirect_link = $request->input('redirect_link');
        $this->reservationHolderNotModifiable = $request->input('reservation_holder_not_modifiable');
        $this->customGdprText = $request->input('custom_gdpr_text');
        $this->showQrCode = $request->input('show_qr_code');
        $this->reception_signature = $request->input('reception_signature');
        $this->showModalInConfirmationPage = $request->input('show_modal_in_confirmation_page');
        $this->activateTimeLimit = $request->input('activate_time_limit');
        $this->sendEmailCheckinAvailable = $request->input('send_email_checkin_available');
        $this->token_key = $request->input('token_key');
        $this->disableAddressAutocomplete = $request->input('disable_address_autocomplete');
        $this->disableOnlyAddressAutocomplete = $request->input('disable_only_address_autocomplete');
        $this->children_sign_documents = $request->input('children_sign_documents');
        $this->showSavePhoneInDatabaseCheckbox = $request->input('show_save_phone_in_database_checkbox');
        $this->showSendNewsletterCheckbox = $request->input('show_send_newsletter_checkbox');
        $this->customPhoneText = $request->input('custom_phone_text');
        $this->allowDrivingLicense = $request->input('allow_driving_license');
        $this->identityDocumentSignatureRequired = $request->input('identity_document_signature_required');
        $this->disableScan = $request->input('disable_scan');
        $this->childDataWithHolder = $request->input('child_data_with_holder');
        $this->disableSendDocumentsPage = $request->input('disable_send_documents_page');
        $this->arrivalTime = $request->input('arrival_time');
        $this->secondSurnameRequiredForSpanish = $request->input('second_surname_required_for_spanish');
        $this->useSendDocumentEmailInPrecheck = $request->input('use_send_document_email_in_precheck');
        $this->regionAndSubregionWithIsoCodes = $request->input('region_and_subregion_with_iso_codes');
        $this->emailMandatoryOnHolder = $request->input('email_mandatory_on_holder');
        $this->removeDuplicatedEmails = $request->input('remove_duplicated_emails');
        $this->emailOrPhoneMandatory = $request->input('email_or_phone_mandatory');

        $this->productId = (int)$request->product_id;

        $this->createRules();
    }

    /**
     * @return bool
     */
    public function isInvalidData(): bool
    {
        $data = $this->getAttributes();
        $validator = Validator::make($data, $this->rules);
        $this->validatorMessages = $validator->errors()->all();

        return $validator->fails();
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function getValidatorMessages()
    {
        return $this->validatorMessages;
    }

    /**
     * @param $name
     * @return |null
     */
    public function __get($name)
    {
        return $this->{$name} ?? null;
    }

    protected function createRules(): void
    {
        $this->rules = [
            'identification'                        => 'nullable|array',
            'maxAttemptsReservation'                => 'nullable|integer',
            'childRequiredIdentityDocumentsAge'     => 'nullable|integer',
            'maxAttemptsChild'                      => 'nullable|integer',
            'maxAttemptsDocument'                   => 'nullable|integer',
            'partialCheckin'                        => 'nullable|boolean',
            'roomTypeSelection'                     => 'nullable|boolean',
            'telephone'                             => 'nullable|boolean',
            'telephoneNotifications'                => 'nullable|boolean',
            'comments'                              => 'nullable|boolean',
            'showCommentsOnlyOnHolder'              => 'nullable|boolean',
            'signedDocuments'                       => 'nullable|boolean',
            'optional_scan'                         => 'nullable|boolean',
            'advanced_scan'                         => 'nullable|boolean',
            'custom_scan_text'                      => 'nullable|boolean',
            'custom_comments_text'                  => 'nullable|boolean',
            'sendIdentityDocuments'                 => 'nullable|boolean',
            'time_limit_checkin'                    => 'nullable|integer',
            'close_time_limit_checkin'              => 'nullable|integer',
            'scan_children_like_adults'             => 'nullable|boolean',
            'children_process_on_reception'         => 'nullable|boolean',
            'custom_confirmation_text'              => 'nullable|boolean',
            'send_identity_documents_to_reception'  => 'nullable|boolean',
            'send_signed_documents_to_reception'    => 'nullable|boolean',
            'show_holder'                           => 'nullable|boolean',
            'allow_expired_documents'               => 'nullable|boolean',
            'not_allow_passports_from_country_brand' => 'nullable|boolean',
            'redirect_link'                         => 'nullable|boolean',
            'reservation_holder_not_modifiable'     => 'nullable|boolean',
            'custom_gdpr_text'                      => 'nullable|boolean',
            'show_qr_code'                          => 'nullable|boolean',
            'reception_signature'                   => 'nullable|boolean',
            'show_modal_in_confirmation_page'       => 'nullable|boolean',
            'activate_time_limit'                   => 'nullable|boolean',
            'send_email_checkin_available'          => 'nullable|boolean',
            'token_key'                             => 'nullable|string',
            'disable_address_autocomplete'          => 'nullable|boolean',
            'disable_only_address_autocomplete'     => 'nullable|boolean',
            'children_sign_documents'               => 'nullable|boolean',
            'show_save_phone_in_database_checkbox'  => 'nullable|boolean',
            'show_send_newsletter_checkbox'         => 'nullable|boolean',
            'custom_phone_text'                     => 'nullable|boolean',
            'allow_driving_license'                 => 'nullable|boolean',
            'identity_document_signature_required'  => 'nullable|boolean',
            'disable_scan'                          => 'nullable|boolean',
            'child_data_with_holder'                => 'nullable|boolean',
            'disable_send_documents_page'           => 'nullable|boolean',
            'arrival_time'                          => 'nullable|boolean',
            'second_surname_required_for_spanish'   => 'nullable|boolean',
            'use_send_document_email_in_precheck'   => 'nullable|boolean',
            'region_and_subregion_with_iso_codes'   => 'nullable|boolean',
            'email_mandatory_on_holder'             => 'nullable|boolean',
            'remove_duplicated_emails'              => 'nullable|boolean',
            'email_or_phone_mandatory'              => 'nullable|boolean',
        ];
    }
}
