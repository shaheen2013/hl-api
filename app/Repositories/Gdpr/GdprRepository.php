<?php

namespace App\Repositories\Gdpr;

use App\Brand;
use App\BrandCustomContent;
use App\BrandCustomVar;
use App\BrandEprivacy;
use App\BrandProduct;
use App\BrandProtocol;
use App\CustomVar;
use App\Exceptions\gdpr\ChainGdprUnavailableException;
use App\Language;
use App\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GdprRepository implements GdprRepositoryInterface
{
    private $brand;
    private $language;
    private $product;
    private $customVar;
    private $brandCustomVar;
    private $brandProduct;
    private $brandProtocol;
    private $brandEprivacy;

    public function __construct(
        Brand $brand,
        Language $language,
        Product $product,
        CustomVar $customVar,
        BrandCustomVar $brandCustomVar,
        BrandProduct $brandProduct,
        BrandProtocol $brandProtocol,
        BrandEprivacy $brandEprivacy
    ) {
        $this->brand = $brand;
        $this->language = $language;
        $this->product = $product;
        $this->customVar = $customVar;
        $this->brandCustomVar = $brandCustomVar;
        $this->brandProduct = $brandProduct;
        $this->brandProtocol = $brandProtocol;
        $this->brandEprivacy = $brandEprivacy;
    }

    public function get(int $brandId, string $userLanguage)
    {

        $cacheName = 'brand_gdpr_' . $brandId . '_' . $userLanguage;
        $cacheTags = ['brand_gdpr_' . $brandId];

        $gdprData =  Cache::tags($cacheTags)->get($cacheName);

        if (!$gdprData) {
            $brand = $this->brand->findOrFail($brandId);
            $hotelId = $brand->hotel_id;
            $chainId = $brand->chain_id;
            $parentId = $brand->parent_id;
            $brandEprivacyInfoAttributes = null;
            $parentBrandEprivacyInfoAttributes = null;
            $legalData = [];
            $gdprData = [];
            $pageModulenames = [
                'first_eprivacy_page' => 'eprivacy_text',
                'second_eprivacy_page' => 'second_eprivacy_text',
                'legal_text' => 'legal_text',
            ];

            $eprivacyResponsibleProductId = $this->product->where([
                'producto' => 'eprivacy_responsible'
            ])->pluck('id')->first();

            $isEprivacyResponsible = $this->brandProduct->where([
                'brand_id' => $brandId,
                'product_id' => $eprivacyResponsibleProductId
            ])->pluck('active')->first();

            if ($hotelId) {
                $isNotHotel = false;

                if ($isEprivacyResponsible) {
                    $brandEprivacyInfo = $this->brandEprivacy->where('hotel_id', $hotelId)->first();
                    $hasRestrictedPortal = $brandEprivacyInfo->restricted_portal ?? false;

                    if ($brandEprivacyInfo) {
                        $brandEprivacyInfoAttributes = $brandEprivacyInfo->getAttributes();
                    }

                    if ($parentId) {
                        $parentBrand = $this->brand->findOrFail($parentId);
                        $parentChainId = $parentBrand->chain_id;
                        $parentBrandEprivacyInfo = $this->brandEprivacy->where('chain_id', $parentChainId)->first();

                        if ($parentBrandEprivacyInfo) {
                            $parentBrandEprivacyInfoAttributes = $parentBrandEprivacyInfo->getAttributes();
                        }
                    }

                    if ($brandEprivacyInfoAttributes && $parentBrandEprivacyInfoAttributes) {
                        $legalData = collect([
                            'brandLegalName' => 'company_name',
                            'brandLegalAddress' => 'company_address',
                            'brandNIF' => 'company_nif',
                            'brandLegalEmail' => 'company_email',
                            'restrictedPortal' => 'restricted_portal',
                        ])->mapWithKeys(function (
                            $value,
                            $key
                        ) use (
                            $brandEprivacyInfoAttributes,
                            $parentBrandEprivacyInfoAttributes
                        ) {
                            return [
                                $key =>
                                $brandEprivacyInfoAttributes[$value] ?
                                    $brandEprivacyInfoAttributes[$value] :
                                    $parentBrandEprivacyInfoAttributes[$value]
                            ];
                        })->toArray();
                    } elseif ($brandEprivacyInfoAttributes xor $parentBrandEprivacyInfoAttributes) {
                        $brandEprivacyInfoAttributes = $brandEprivacyInfoAttributes ?? $parentBrandEprivacyInfoAttributes;

                        $legalData['brandLegalName'] = $brandEprivacyInfoAttributes['company_name'];
                        $legalData['brandLegalAddress'] = $brandEprivacyInfoAttributes['company_address'];
                        $legalData['brandNIF'] = $brandEprivacyInfoAttributes['company_nif'];
                        $legalData['brandLegalEmail'] = $brandEprivacyInfoAttributes['company_email'];
                        $legalData['restrictedPortal'] = $brandEprivacyInfoAttributes['restricted_portal'];
                    }

                    $gdprData['legalData'] = $legalData;

                    $brandProtocol = $this->brandProtocol
                        ->where('brand_id', $brandId)
                        ->where('service', 'portal')
                        ->pluck('treatment')
                        ->first();
                    $brandProtocolSuffix = $brandProtocol == 'informal' ? '_' . $brandProtocol : '';

                    foreach ($pageModulenames as $pageNameKey => $moduleNameValue) {
                        $pageName = $pageNameKey;
                        $moduleName = $moduleNameValue;

                        if ($moduleNameValue !== "legal_text") {
                            if ($moduleNameValue == "second_eprivacy_text") {
                                $moduleName = $moduleNameValue . $brandProtocolSuffix;
                            } else {
                                $moduleName = $isNotHotel ? 'not_hotel_' . $moduleNameValue : ($hasRestrictedPortal ? 'restrictive_' . $moduleNameValue . $brandProtocolSuffix : $moduleNameValue . $brandProtocolSuffix);
                            }
                        }


                        $userLanguageFirstPageEprivacyText = Arr::get(
                            $this->getBrandCustomContent($hotelId, $chainId, $pageName, $moduleName),
                            $userLanguage
                        );
                        $gdprIndex = Str::camel($pageNameKey);
                        $gdprData[$gdprIndex] = $userLanguageFirstPageEprivacyText;
                    }
                } else {
                    $hotelinkingEprivacyInfo = $this->getHotelinkingEprivacyInfo();

                    $legalData['brandLegalName'] = $hotelinkingEprivacyInfo[0]->value;
                    $legalData['brandLegalAddress'] = $hotelinkingEprivacyInfo[1]->value;
                    $legalData['brandNIF'] = $hotelinkingEprivacyInfo[2]->value;
                    $legalData['brandLegalEmail'] = $hotelinkingEprivacyInfo[3]->value;

                    $gdprData['legalData'] = $legalData;

                    foreach ($pageModulenames as $pageNameKey => $moduleNameValue) {
                        $userLanguageFirstPageEprivacyText = Arr::get(
                            $this->getBrandCustomContent($hotelId, $chainId, $pageNameKey, $moduleNameValue),
                            $userLanguage
                        );
                        $gdprIndex = Str::camel($pageNameKey);
                        $gdprData[$gdprIndex] = $userLanguageFirstPageEprivacyText;
                    }
                }
            } else {
                throw new ChainGdprUnavailableException();
            }

            $placeholders = $this->customVar->all()->pluck('name')->toArray();
            $gdprData['placeholders'] = $placeholders;

            $gdprData['restrictedPortal'] = (bool) Arr::pull(
                $gdprData['legalData'],
                'restrictedPortal'
            );
        }
        Cache::tags($cacheTags)->put($cacheName, $gdprData);
        return $gdprData;
    }

    private function getHotelinkingEprivacyInfo()
    {
        $customVars = $this->brandCustomVar
            ->where('hotel_id', null)
            ->where('chain_id', null)
            ->get();

        return $customVars;
    }

    // HOTELINKING APP LEGACY CODE

    private function getBrandCustomContent($hotel_id, $chain_id, $pageName, $moduleName, $active = 1)
    {
        $languages = $this->language->all();
        $brandContent = $this->getParsedHotelCustomContentId($hotel_id, $chain_id, $pageName, $moduleName, $active);
        $brandVars = [];
        $brandTexts = [];
        if (Arr::get($brandContent, 'module_id')) {
            $brandVars = $this->getBrandCustomVarsValues(Arr::get($brandContent, 'module_id'), $hotel_id, $chain_id);
            $brandTexts = $this->getParsedBrandCustomTextsValues(Arr::get($brandContent, 'module_id'), $hotel_id, $chain_id);
        }
        if ($brandContent) {
            $brandConfiguration = Arr::get($brandContent, 'configuration');

            if ($brandConfiguration == 'default') {
                $contentIndex = 'default.';
                $secondaryAltContentIndex = '';
                $altContentIndex = '';
                $varsIndex = 'default_value';
                $secondaryVars = '';
                $altVarsIndex = '';
            } elseif ($brandConfiguration == 'own_vars') {
                $contentIndex = 'default.';
                $secondaryAltContentIndex = '';
                $altContentIndex = '';
                $varsIndex = 'value';
                $altVarsIndex = 'chain_value';
                $secondaryVars  = 'default_value';
            } else {
                $contentIndex = '';
                $altContentIndex = 'chain_info.';
                $secondaryAltContentIndex = 'default.';
                $varsIndex = 'value';
                $altVarsIndex = 'chain_value';
                $secondaryVars  = 'default_value';
            }

            foreach ($languages as $language) {
                $languageName = $language->name;
                $brandText = Arr::get($brandTexts, $languageName);
                $brandContent[$languageName] = $content = Arr::get($brandContent, $contentIndex . $languageName) ? Arr::get($brandContent, $contentIndex . $languageName) : (Arr::get($brandContent, $altContentIndex . $languageName) ? Arr::get($brandContent, $altContentIndex . $languageName) : Arr::get($brandContent, $secondaryAltContentIndex . $languageName));
                if ($brandVars) {
                    foreach ($brandVars as $brandVar) {
                        $strToReplace = Arr::get($brandVar, 'name');
                        $strReplace = Arr::get($brandVar, $varsIndex, null) ? Arr::get($brandVar, $varsIndex) : (Arr::get($brandVar, $altVarsIndex) ? Arr::get($brandVar, $altVarsIndex) : Arr::get($brandVar, $secondaryVars));
                        $content = str_replace($strToReplace, $strReplace, $content);
                        $brandContent[$languageName] = $content;
                    }
                }
                if ($brandText) {
                    foreach ($brandText as $text) {
                        $strToReplace = Arr::get($text, 'name');
                        $strReplace = Arr::get($text, 'value', null) ? Arr::get($text, 'value') : (Arr::get($text, 'chain_value') ? Arr::get($text, 'chain_value') : Arr::get($text, 'default_value'));
                        $content = str_replace($strToReplace, $strReplace, $content);
                        $brandContent[$languageName] = $content;
                    }
                }
                $brandContent[$languageName] = stripslashes($brandContent[$languageName]);
            }
            return $brandContent;
        }
    }

    private function getParsedHotelCustomContentId($hotel_id, $chain_id, $pageName, $moduleName, $active = 0)
    {
        $hotelModules = $this->getHotelCustomContentId($hotel_id, $chain_id, $pageName, $moduleName, $active);

        $parsedHotelModule = null;
        $arrayIntroAndReturn = array("\n", "\r");

        foreach ($hotelModules as $hotelModule) {
            $hotelContentEscaped = addslashes($hotelModule->content);
            $parsedHotelModule[$hotelModule->lang] = str_replace($arrayIntroAndReturn, '', $hotelContentEscaped);
            $chainContentEscaped = addslashes($hotelModule->chain_content);
            $parsedHotelModule['chain_info'][$hotelModule->lang] = str_replace($arrayIntroAndReturn, '', $chainContentEscaped);
            $parsedHotelModule['default'][$hotelModule->lang] = $hotelModule->default_content;
        }

        $hotelModules = array_pop($hotelModules);

        $parsedHotelModule['active'] = $hotelModules->module_active;
        $parsedHotelModule['state'] = $hotelModules->state;
        $parsedHotelModule['configuration'] = $hotelModules->configuration;
        $parsedHotelModule['module_id'] = $hotelModules->custom_module_id;
        $parsedHotelModule['chain_active'] = $hotelModules->chain_module_active;

        return $parsedHotelModule;
    }

    private function getHotelCustomContentId($hotel_id, $chain_id, $pageName, $moduleName, $active = false)
    {
        $activeWhere = '';
        $chainActiveWhere = '';

        if ($active) {
            $activeWhere = ' AND brand_custom_module_content.active=1';
            $chainActiveWhere = 'AND chain_content.active=1';
        }

        $chainSearch = 'in (0)';
        if ($chain_id) {
            $chainSearch = " = $chain_id";
        }

        $sql = "
            SELECT
                brand_custom_module_content.content,
                chain_content.content as chain_content,
                chain_content.active as chain_module_active,
                defaults.content as default_content,
                brand_custom_content.configuration as configuration,
                languages.name as lang,
                custom_content.name as custom_content,
                custom_module.id as custom_module_id,
                custom_module.name as custom_module,
                custom_content_state.name as state,
                brand_custom_module_content.active as module_active,
                brand_custom_content.active as content_active 
            FROM
                brand_custom_module_content as defaults  
            LEFT JOIN
                languages
            ON
                defaults.language_id = languages.id
            LEFT JOIN
                custom_content
            ON
                custom_content.name = '$pageName'
            RIGHT JOIN
                custom_module
            ON
                custom_module.id = defaults.custom_module_id  
            LEFT JOIN
                brand_custom_content
            ON
                brand_custom_content.custom_content_id = custom_content.id AND
                brand_custom_content.hotel_id = $hotel_id
            LEFT JOIN
                brand_custom_content as chain_custom_content
            ON
                chain_custom_content.custom_content_id = custom_content.id AND
                chain_custom_content.chain_id $chainSearch 
            LEFT JOIN
                custom_content_state
            ON
                brand_custom_content.custom_content_state_id = custom_content_state.id 
            LEFT JOIN
                brand_custom_module_content
            ON
                brand_custom_module_content.id != defaults.id AND
                brand_custom_module_content.custom_module_id = defaults.custom_module_id AND
                brand_custom_module_content.language_id = defaults.language_id AND
                brand_custom_module_content.brand_custom_content_id=brand_custom_content.id $activeWhere
            LEFT JOIN
                brand_custom_module_content as chain_content
            ON
                chain_content.id != defaults.id AND
                chain_content.custom_module_id = defaults.custom_module_id AND
                chain_content.language_id = defaults.language_id AND
                chain_content.brand_custom_content_id=chain_custom_content.id  $chainActiveWhere 
            WHERE
                defaults.brand_custom_content_id is null AND
                custom_module.name = '$moduleName'
        ";
        return DB::select(DB::raw($sql));
    }

    private function getBrandCustomVarsValues($module_id, $hotel_id, $chain_id)
    {
        $chainLJcondition = "is null";
        if ($chain_id) {
            $chainLJcondition = " = $chain_id ";
        }
        $sql = "
            SELECT custom_vars.name,brand_custom_vars.value, defaults.value as default_value, chain_vars.value as chain_value FROM custom_vars
            LEFT JOIN brand_custom_vars ON custom_vars.id = brand_custom_vars.custom_vars_id AND brand_custom_vars.hotel_id = $hotel_id
            LEFT JOIN brand_custom_vars as defaults ON custom_vars.id = defaults.custom_vars_id AND defaults.hotel_id is null AND defaults.chain_id is null
            LEFT JOIN brand_custom_vars as chain_vars ON custom_vars.id = chain_vars.custom_vars_id AND chain_vars.hotel_id is null AND chain_vars.chain_id $chainLJcondition
            RIGHT JOIN custom_module_vars ON custom_module_vars.custom_vars_id = custom_vars.id
            WHERE custom_module_vars.custom_module_id = $module_id
        ";

        return DB::select(DB::raw($sql));
    }

    private function getParsedBrandCustomTextsValues($module_id, $hotel_id, $chain_id)
    {
        $brandCustomTexts = $this->getBrandCustomTextsValues($module_id, $hotel_id, $chain_id);
        $languages = $this->language->all();
        $parsedBrandCustomTexts = [];

        foreach ($languages as $language) {
            foreach ($brandCustomTexts as $brandCustomText) {
                if (Arr::get($brandCustomText, 'language') == Arr::get($language, 'name')) {
                    $parsedBrandCustomTexts[Arr::get($language, 'name')][] = $brandCustomText;
                }
            }
        }

        return $parsedBrandCustomTexts;
    }

    private function getBrandCustomTextsValues($module_id, $hotel_id, $chain_id)
    {
        $brand_id = $hotel_id ? $hotel_id : $chain_id;
        $brand_type = $hotel_id ? 'hotel_id' : 'chain_id';

        $sql = "
            SELECT custom_texts.name, defaults.value as default_value, brand_values.value, languages.name as language  FROM brand_custom_texts as defaults
            LEFT JOIN languages ON languages.id = defaults.language_id
            LEFT JOIN custom_texts ON defaults.custom_text_id =custom_texts.id
            RIGHT JOIN custom_module_texts ON defaults.custom_text_id = custom_module_texts.custom_text_id
            LEFT JOIN brand_custom_texts as brand_values ON languages.id = brand_values.language_id AND brand_values.$brand_type=$brand_id
            WHERE custom_module_texts.custom_module_id = $module_id  AND defaults.hotel_id is null and  defaults.chain_id is null
        ";

        return DB::select(DB::raw($sql));
    }

    // END HOTELINKING APP LEGACY CODE

    public function updateOrCreate($hotelId, $data)
    {
        try {
            if (isset($data->info['companyData'])) {
                $this->setBrandEprivacy($data->hotelId, $data->chainId, $data->info['companyData']);
            }
            if (isset($data->info['customVars'])) {
                $this->setCustomVars($data->hotelId, $data->chainId, $data->info['customVars']);
            }

            if (isset($data->info['customContent'])) {
                $this->setBrandCustomContent($data->hotelId, $data->chainId, $data->info['customContent']);
            }
            if (isset($data->info['customTexts'])) {
                $this->setBrandCustomModuleContent($data->hotelId, $data->chainId, $data->info);
            }

            $brand = Brand::where('hotel_id', $hotelId)->first();
            $cacheTags = ['brand_gdpr_' . $brand->id];

            Cache::tags($cacheTags)->flush();
        } catch (BadRequestHttpException $e) {
            throw $e;
        }
    }

    protected function setCustomVars($hotelId, $chainId, $customVars)
    {
        foreach ($customVars as $key => $value) {
            $brand_id = $hotelId ? $hotelId : $chainId;
            $brand_type = $hotelId ? 'hotel_id' : 'chain_id';
            $customVarName = '{{' . $key . '}}';
            $sql = "INSERT INTO brand_custom_vars ($brand_type, value, custom_vars_id) VALUES ($brand_id, '$value', (SELECT id FROM custom_vars WHERE name = '$customVarName')) ON DUPLICATE KEY UPDATE value = '$value'";
            DB::insert(DB::raw($sql));
        }
    }
    protected function setBrandEprivacy($hotelId, $chainId, $companyData)
    {
        $brand_id = $hotelId ? $hotelId : $chainId;
        $brand_type = $hotelId ? 'hotel_id' : 'chain_id';
        $sql = "INSERT INTO brand_eprivacy ($brand_type ,company_name,company_address,company_nif,company_email, restricted_portal) VALUES ($brand_id, '{$companyData['companyName']}', '{$companyData['companyAddress']}', '{$companyData['companyNif']}', '{$companyData['companyEmail']}', {$companyData['restrictedPortal']}) ON DUPLICATE KEY UPDATE company_name='{$companyData['companyName']}',company_address='{$companyData['companyAddress']}',company_nif='{$companyData['companyNif']}',company_email='{$companyData['companyEmail']}',restricted_portal={$companyData['restrictedPortal']}
    ";
        DB::insert(DB::raw($sql));
    }
    protected function setBrandCustomContent($hotelId, $chainId, $customContent)
    {
        $brand_id = $hotelId ? $hotelId : $chainId;
        $brand_type = $hotelId ? 'hotel_id' : 'chain_id';
        foreach ($customContent as $customItem) {
            $sql = "
        INSERT INTO
            brand_custom_content
            ($brand_type, custom_content_id, custom_content_state_id, active, configuration) 
        VALUES
            (
                $brand_id,
                (
                    SELECT
                        id
                    FROM
                         custom_content
                    WHERE
                          name = '{$customItem['pageName']}'
                ),
                (
                    SELECT
                       id
                    FROM
                         custom_content_state
                    WHERE
                        name = '{$customItem['restrictedPortal']}' AND
                        custom_content_id =
                        (
                            SELECT
                                id
                            FROM
                                custom_content
                            WHERE name = '{$customItem['pageName']}'
                        )
                ),
                1,
                '{$customItem['configuration']}'
            ) 
            ON DUPLICATE KEY
            UPDATE
                active = VALUES(active),
                configuration = VALUES(configuration),
                custom_content_state_id = VALUES(custom_content_state_id)";
            DB::insert(DB::raw($sql));
        }
    }
    protected function setBrandCustomModuleContent($hotelId, $chainId, $info)
    {
        $languages = $this->language->all();

        foreach ($info['customTexts'] as $customText) {
            foreach ($customText['content'] as $language => $text) {
                if (sizeof($customText['content']) == sizeof($languages)) {
                    $brand_id = $hotelId ?? $chainId;
                    $brand_type = $hotelId ? 'hotel_id' : 'chain_id';

                    $sql = "INSERT INTO brand_custom_module_content (brand_custom_content_id, custom_module_id, language_id, content, active)
                    VALUES ((SELECT id FROM brand_custom_content WHERE $brand_type=$brand_id AND custom_content_id = (SELECT id FROM custom_content WHERE name = '{$customText['pageName']}'))
                    , (SELECT id FROM custom_module WHERE name = '{$customText['moduleName']}'), (SELECT id FROM languages WHERE name='$language'), '$text', '{$customText['active']}') ON DUPLICATE KEY UPDATE active = '{$customText['active']}', content='$text', custom_module_id = (SELECT id FROM custom_module WHERE name = '{$customText['moduleName']}')";

                    DB::insert(DB::raw($sql));
                } else {
                    throw new BadRequestHttpException('SetBrandCustomModuleContentError');
                }
            }
        }
    }

    public function insertGdprEvents($events)
    {
        $sql = "INSERT INTO gdpr_history(event, created_at, user_id, hotel_id) VALUES " . join(',', $events);
        DB::insert($sql);
    }
}
