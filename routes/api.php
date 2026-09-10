<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users/{id}', 'UserController@show');
Route::get('users/{email}/{brand_id}', 'UserController@getUserByEmailAndBrandId')->where(['brand_id' => '[0-9]+']);
Route::post('users', 'UserController@store'); // Create users from Widget
Route::post('brands/{brand_id}/users', 'UserController@updateOrCreate')->where(['brand_id' => '[0-9]+']); // Update or create users and userBrand from APP
Route::get('brands/{brand_id}/users/{user_id}', 'UserController@getBrandUser')->where(['brand_id' => '[0-9]+', 'user_id' => '[0-9]+']);
Route::put('brands/{brand_id}/users/{user_id}/unsubscribe', 'UserController@unsubscribe')->where(['brand_id' => '[0-9]+', 'user_id' => '[0-9]+']);
Route::put('brands/{brand_id}/bulk-unsubscribe', 'UserController@bulkUnsubscribe')->where(['brand_id' => '[0-9]+']);
Route::get('brands/{brand_id}/users/{user_id}/subscriptions', 'UserController@getBrandUserSubscriptions')->where(['brand_id' => '[0-9]+', 'user_id' => '[0-9]+']);

Route::get('brands/{brand_id}/users', 'UserController@index')->where(['brand_id' => '[0-9]+']);

Route::get('brands/{brand_id}/clients/', 'ClientController@getBrandClients');
Route::get('brands/{brand_id}/clients/{user_id}', 'ClientController@getClientDetail')
    ->where([
        'brand_id'  => '[0-9]+',
        'user_id'   => '[0-9]+'
    ]);
Route::post('brands/{brand_id}/clients/report', 'ClientController@queueReport')->where(['brand_id' => '[0-9]+']);
Route::delete('brands/{brand_id}/clients/{client_id}', 'ClientController@deleteClient')
    ->where([
        'brand_id'  => '[0-9]+',
        'client_id' => '[0-9]+'
    ]);

Route::get('products', 'BrandProductController@getProducts');
Route::put('brands/{brand_id}/products/{product_id}/activate/{active}', 'BrandProductController@activate');

Route::get('brands/{brand_id}/products/{product_id}/configuration', 'BrandProductController@getConfiguration');
Route::put('brands/{brand_id}/products/{product_id}/configuration', 'BrandProductController@setConfiguration');

Route::get('brands/{brand_id}/products/{product_id}/config', 'BrandProductController@getJsonConfig');
Route::put('brands/{brand_id}/products/{product_id}/config', 'BrandProductController@setJsonConfig');
Route::get('brands/{brand_id}/products', 'BrandProductController@getProductsStatus');
Route::get('brands/{brand_id}/products/{product_id}', 'BrandProductController@getProductStatus');



Route::get('brands/{brand_id}/surveys/satisfactions/{satisfaction_id?}', 'SatisfactionController@getSatisfactions')->where(['id' => '[0-9]+', 'satisfaction_id' => '[0-9]+']);
Route::post('brands/{brand_id}/surveys/report', 'SatisfactionController@queueReport')->where(['brand_id' => '[0-9]+']);

Route::post('users/facebook', 'UserFacebookController@store');

Route::get('offers/{id}/{lang}', 'OfferController@show');

Route::get('brands/{id}', 'BrandController@show');
Route::get('brands/{id}/childs/{type}', 'BrandController@getBrandChilds')->where(['id' => '[0-9]+']);
Route::get('brands/{id}/parent', 'BrandController@getBrandParent')->where(['id' => '[0-9]+']);
Route::get('brands/{id}/protocols/{service?}', 'BrandController@getProtocolsByBrand')->where(['id' => '[0-9]+']);
Route::get('brands/{id}/info', 'BrandController@showInfo')->where(['id' => '[0-9]+']);
Route::put('brands/{id}', 'BrandController@update');
Route::put('brands/{id}/info', 'BrandController@updateInfo')->where(['id' => '[0-9]+']);
Route::put('accounts/{id}/info', 'AccountController@updateInfo')->where(['id' => '[0-9]+']);

Route::get('brands/{brand_id}/survey-questions/', 'SurveyQuestionController@show')->where(['brand_id' => '[0-9]+']);
Route::post('brands/{brand_id}/survey-questions/', 'SurveyQuestionController@store')->where(['brand_id' => '[0-9]+']);
Route::post('brands/{brand_id}/survey-questions/spread/', 'SurveyQuestionController@spread')->where(['brand_id' => '[0-9]+']);
Route::put('brands/{brand_id}/survey-questions/{question_id}', 'SurveyQuestionController@update')->where(['brand_id' => '[0-9]+', 'survey_question_id' => '[0-9]+']);
Route::delete('brands/{brand_id}/survey-questions/{survey_question_id}', 'SurveyQuestionController@delete')->where(['brand_id' => '[0-9]+', 'survey_question_id' => '[0-9]+']);

Route::get('satisfactions/{brand_id}/{lang}/{score}/{days}/{exclusiveFavorite?}', 'SatisfactionController@showByBrandIdAndScore');
Route::get('satisfactions/{brand_id}/{lang}//{days}/{exclusiveFavorite?}', 'SatisfactionController@showByBrandIdAndScore');
Route::get('satisfactions/{id}/', 'SatisfactionController@show');

Route::post('satisfactions/{user_survey_id}/incidents', 'SatisfactionController@setIncidentsToSatisfaction');
Route::put('brands/{brand_id}/surveys/{user_survey_id}', 'SatisfactionController@updateUserSurvey')->where(['id' => '[0-9]+', 'survey_id' => '[0-9]+']);

Route::post('register/widget', 'WidgetController@register');

Route::get('offers/brand/{id}/{lang}', 'OfferController@getOfferByBrandId');
Route::get('offers/{id}', 'OfferController@getOfferAndLangs');
Route::get('brand/{brand_id}/loyalty-offers', 'OfferController@getLoyaltyOffers')->where(['brand_id' => '[0-9]+']);
Route::put('brand/{brand_id}/offer/{offer_id?}', 'OfferController@saveLoyaltyOffers')->where(['brand_id' => '[0-9]+']);

Route::delete('brand/{brand_id}/offer/{offer_id}', 'OfferController@deleteLoyaltyOffers')->where(['brand_id' => '[0-9]+']);
Route::delete('brand/{brand_id}/reward-offer/{offer_id}', 'OfferController@delete')->where(['brand_id' => '[0-9]+']);

Route::get('brands/{brand_id}/staffs', 'StaffController@get')->where(['brand_id' => '[0-9]+']);
Route::delete('brands/{brand_id}/staffs/{staff_id}', 'StaffController@delete')->where(['brand_id' => '[0-9]+', 'staff_id' => '[0-9]+']);

Route::get('langs', 'LangController@index');
Route::get('languages', 'LangController@getAllLanguages');

Route::get('protocols/services', 'ProtocolController@getServicesAvailable');
Route::resource('protocols', 'ProtocolController');

Route::get('brands/{brand_id}/surveys/satisfactions/answers', 'SurveyAnswerController@index')->where(['brand_id' => '[0-9]+']);
Route::post('brands/{brand_id}/surveys/{survey_id}/answers', 'SurveyAnswerController@saveAnswer')->where(['brand_id' => '[0-9]+', 'survey_id' => '[0-9]+']);

Route::get('brands/{brand_id}/surveys/satisfactions/categories/', 'SurveyCategoryController@show')->where(['brand_id' => '[0-9]+']);
Route::post('brands/{brand_id}/surveys/satisfactions/categories', 'SurveyCategoryController@store')->where(['brand_id' => '[0-9]+']);
Route::put('brands/{brand_id}/surveys/satisfactions/categories/{category_id}', 'SurveyCategoryController@update')->where(['brand_id' => '[0-9]+', 'category_id' => '[0-9]+']);
Route::delete('brands/{brand_id}/surveys/satisfactions/categories/{category_id}', 'SurveyCategoryController@delete')->where(['brand_id' => '[0-9]+', 'category_id' => '[0-9]+']);

Route::post('brands/{brand_id}/bookings/funnel', 'BookingController@store')->where(['brand_id' => '[0-9]+']);

Route::get('brands/{brand_id}/access', 'BrandAccessController@get')->where(['brand_id' => '[0-9]+']);
Route::get('brands/{brand_id}/access_types', 'BrandAccessController@getAccessTypes')->where(['brand_id' => '[0-9]+']);
Route::put('brands/{brand_id}/access_types/{access_type_id}', 'BrandAccessController@putAccessTypes')->where(['brand_id' => '[0-9]+', 'access_type_id' => '[0-9]+']);
Route::put('brands/{brand_id}/access', 'BrandAccessController@store')->where(['brand_id' => '[0-9]+']);

Route::get('brands/url/all', 'BrandController@getAllBrandUrlLanguages');

Route::get('brands/{id}/url', 'BrandController@getUrlsByBrandId');
Route::get('brands/url/languages/{brand_id}', 'BrandController@getBrandUrlLanguages');
Route::put('brands/{id}/languages/{name}/url', 'BrandController@updateBrandUrlLanguage');

Route::get('brands/{id}/languages/{name}/url', 'BrandController@getUrlsByBrandIdAndLanguage');
Route::delete('brands/{id}/languages/{name}/url', 'BrandController@deleteBrandUrlLanguageByBrandIdAndLanguageName');
Route::post('brands/{id}/languages/{name}/url', 'BrandController@setBrandUrlLanguage');
Route::put('brands/languages/url', 'BrandController@updateUrlsLanguageList');

Route::get('device/blacklist', 'DeviceBlacklistController@index');

Route::get('brands/{id}/offers-wifi/{lang?}', 'OfferWifiController@show')->where(['id' => '[0-9]+']);
Route::post('brands/{id}/offers-wifi', 'OfferWifiController@createOrUpdate')->where(['id' => '[0-9]+']);
Route::delete('offers-wifi/{id}', 'OfferWifiController@delete')->where(['id' => '[0-9]+']);

Route::get('brands/{id}/gdpr', 'GdprController@get')->where(['id' => '[0-9]+']);

Route::post("brands/{id}/gdpr", 'GdprController@store')->where(['id' => '[0-9]+']);

Route::post("brands/{id}/gdpr/events", 'GdprController@insertGdprEvents')->where(['id' => '[0-9]+']);

Route::get("user_brands/{userEmail}", 'UserController@getVisitedBrandsByEmail');

Route::post('brands/{brand_id}/archive', 'BrandController@archiveBrandInfo')->where(['id' => '[0-9]+']);

Route::post('brands/{brand_id}/surveys/{user_survey_id}/delete', 'SatisfactionController@deleteUserSurvey')->where(['id' => '[0-9]+', 'survey_id' => '[0-9]+']);
