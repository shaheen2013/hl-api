---
title: Autocheckin
layout: layout.html
eleventyNavigation:
  key: Autocheckin
  parent: Products
  order: 1
---

# Autocheckin Product

The _Autocheckin_ is a Hotelinking product that allows people to check-in online. Most of the fields defined in the product config seeder belong to Autocheckin.

## Endpoints

### GET

`{baseUrl}/brands/:brand_id/products/:product_id/configuration`

To get the current configuration for the product.

- brand_id: The brand identifier, an integer.
- product_id: The product identifier, which is **21** for Autocheckin.

### PUT

`{baseUrl}/brands/:brand_id/products/:product_id/configuration`

To update the configuration for the product.

It has the same endpoint as GET, but with a payload that contains the updated info. As the payload is too big to paste an example, check the fields below that will be included.

\*_Add the remaining fields of autocheckin config._

### Fields

| Field                                 | Description                                                                                        | Type    | Default |
| ------------------------------------- | -------------------------------------------------------------------------------------------------- | ------- | ------- |
| identification                        | Contains all the needed data to identify an user trying to check-in                                | json    | \*      |
| child_required_identity_documents_age | Age in which documentation is required for a child                                                 | integer | 18      |
| max_attempts_reservation              | Limit of allowed attempts to search a reservation                                                  | integer | 3       |
| max_attempts_child                    | Limit of allowed attempts while filling child form to check-in                                     | integer | 3       |
| max_attempts_document                 | Limit of allowed attempts to scan the identity document                                            | integer | 10      |
| max_attempts_telephone                | Limit of allowed attempts to validate the phone number                                             | integer | 3       |
| partial_checkin                       | Determines if is mandatory to do checkin for all guests at once                                    | boolean | false   |
| room_type_selection                   | If active, a selector for room type will be switched on                                            | boolean | false   |
| telephone                             | If active, the user will be asked for his phone number in a form                                   | boolean | false   |
| telephone_notifications               | If active, it will offer the user to receive SMS notifications                                     | boolean | false   |
| comments                              | If active, it will allow the user to leave a comment while check-in                                | boolean | false   |
| signed_documents                      | If active, it will be possible to create documents that users have to sign during check-in         | boolean | false   |
| send_identity_documents               | If active, submitted identity documents will be sent to PMS                                        | boolean | false   |
| send_identity_documents_to_reception  | If active, submitted identity documents will be sent to reception by email                         | boolean | false   |
| send_signed_documents_to_reception    | If active, submitted signed documents will be sent to reception by email                           | boolean | false   |
| time_limit_checkin                    | Determines the max of days allowed to do check-in before the date of check-in in reservation       | integer | 0       |
| show_holder                           | If active, returns holder info in status page                                                      | boolean | false   |
| optional_scan                         | If active, it will show a button in scan page to allow the user to complete data manually          | boolean | false   |
| reservation_holder_not_modifiable     | Determines if reservation holder can be modified while filling validate-data form                  | boolean | false   |
| custom_scan_text                      | Determines if a custom scan text will be displayed in scan page                                    | boolean | false   |
| custom_confirmation_text              | Determines if a custom confirmation text will be displayed in confirmation page                    | boolean | false   |
| custom_gdpr_text                      | Determines if a custom gdpr text will be displayed in gdpr page                                    | boolean | false   |
| show_qr_code                          | If active, a qr code will be shown in confirmation page                                            | boolean | true    |
| show_modal_in_confirmation_page       | If active, a modal pops in conf. page reminding the user to finish check-in for all guests         | boolean | false   |
| reception_signature                   | If active, allow reception autocheckin                                                             | boolean | false   |
| activate_time_limit                   | If active, the value specified in time_limit_checkin will be applied to know if guest can check-in | boolean | false   |
| send_email_checkin_available          | If active, a mail will be sent to guest when checkin becomes available                             | boolean | false   |
| token_key                             | The secret key to encrypt and decrypt the token GET param to search a reservation                  | text    |         |
| close_time_limit_checkin              | Number of days before the check-in date to close it (negative values after check-in date)          | integer |         |
| disable_address_autocomplete          | Disable AWS GEO search on address and make it as free input text                                   | boolean | false   |
| disable_only_address_autocomplete     | Disable AWS GEO search only for the address field (other location fields remain unaffected)        | boolean | false   |

