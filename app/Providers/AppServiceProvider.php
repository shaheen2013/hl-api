<?php

namespace App\Providers;

use App\Repositories\Bookings\BookingFunnelRepository;
use App\Repositories\Bookings\BookingFunnelRepositoryInterface;
use App\Repositories\ConnectionHistoryRepository;
use App\Repositories\ConnectionHistoryRepositoryInterface;
use App\Repositories\Gdpr\GdprRepository;
use App\Repositories\Gdpr\GdprRepositoryInterface;
use App\Repositories\Products\BrandProductRepositoryInterface;
use App\Repositories\Device\DeviceBlacklistRepositoryInterface;
use App\Repositories\Satisfactions\SatisfactionRepository;
use App\Repositories\Satisfactions\SatisfactionRepositoryInterface;
use App\Repositories\Satisfactions\UserSurveyQuestionAnswerRepository;
use App\Repositories\Satisfactions\SurveyRepository;
use App\Repositories\Satisfactions\UserSurveyRepository;
use App\Repositories\Satisfactions\SurveyQuestionRepository;
use App\Repositories\Satisfactions\QuestionBrandRepository;
use App\Repositories\Satisfactions\QuestionResponseRepository;
use App\Repositories\Satisfactions\QuestionResponseRepositoryInterface;
use App\Repositories\Satisfactions\QuestionResponseTextRepository;
use App\Repositories\Satisfactions\QuestionResponseTextRepositoryInterface;
use App\Repositories\SocialMedia\SocialMediaRepository;
use App\Repositories\SocialMedia\SocialMediaRepositoryInterface;
use App\Repositories\SocialMedia\SocialMediaShareRepository;
use App\Repositories\SocialMedia\SocialMediaShareRepositoryInterface;
use App\Repositories\Visits\AccessTypeRepository;
use App\Repositories\Visits\DeviceRepository;
use App\Repositories\Visits\UserHotelRepository;
use App\Repositories\Visits\UserHotelRepositoryInterface;
use App\Repositories\Visits\UserBrandRepository;
use App\Repositories\Visits\UserBrandRepositoryInterface;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Users\UserSatisfactionRepository;
use App\Repositories\Users\UserSatisfactionRepositoryInterface;
use App\Repositories\Users\UserVisitRepository;
use App\Repositories\Users\UserVisitRepositoryInterface;
use App\Repositories\Users\UserVoucherRepository;
use App\Repositories\Users\UserVoucherRepositoryInterface;
use App\Repositories\Clients\ClientRepository;
use App\Repositories\Clients\ClientRepositoryInterface;
use App\Repositories\Staff\StaffRepository;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\Satisfactions\IncidentRepository;
use App\Repositories\Satisfactions\IncidentRepositoryInterface;
use App\Repositories\Products\ProductRepositoryInterface;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Products\ProductConfigRepositoryInterface;
use App\Repositories\Products\ProductConfigRepository;
use App\Repositories\Products\BrandProductConfigRepositoryInterface;
use App\Repositories\Products\BrandProductConfigRepository;
use App\Repositories\Products\BrandProductRepository;
use App\Repositories\Satisfactions\QuestionRepositoryInterface;
use App\Repositories\Satisfactions\QuestionRepository;
use App\Repositories\Satisfactions\QuestionTextRepositoryInterface;
use App\Repositories\Satisfactions\QuestionTextRepository;
use App\Services\Products\AutocheckinProductService;
use App\Services\Gdpr\GdprService;
use App\Services\Gdpr\GdprServiceInterface;
use App\Services\Products\ProductConfigService;
use App\Services\Products\BrandProductConfigService;
use App\Services\BrandProductService;
use App\Services\BrandService;
use App\Services\AccountService;
use App\Services\Connections\ConnectionHistoryService;
use App\Services\Products\ProductService;
use App\Services\Products\BirthdayEmailsProductService;
use App\Services\Products\RequireRoomNumberProductService;
use App\Services\Products\WidgetProductService;
use App\Services\Products\CustomizedSurveyProductService;
use App\Services\Products\LoyaltyProductService;
use App\Services\Products\NotHotelProductService;
use App\Services\Products\PortalProProductService;
use App\Services\Products\ReviewProductService;
use App\Services\Products\SatisfactionSurveyProductService;
use App\Services\SatisfactionService;
use App\Services\Stats\LoyaltyStatsService;
use App\Services\Users\UserHotelService;
use App\Services\Users\UserSatisfactionService;
use App\Services\Users\UserVisitService;
use App\Services\Users\UserVoucherService;
use App\Services\Users\UserService;
use App\Services\Visits\VisitService;
use App\Services\Device\DeviceBlacklistService;
use App\Services\Bouncer\BouncerService;
use App\Repositories\Device\DeviceBlacklistRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Account\AccountRepository;
use App\Repositories\OfferWifi\OfferWifiRepositoryInterface;
use App\Repositories\OfferWifi\OfferWifiRepository;
use App\Services\OfferWifi\OfferWifiService;
use App\BrandProduct;
use App\Repositories\Offers\OfferRepository;
use App\Repositories\Offers\OfferRepositoryInterface;
use App\Repositories\Satisfactions\CustomizedSatisfactionAnswerRepository;
use App\Services\Offers\OfferService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Aws\EventBridge\EventBridgeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EventBridgeClient::class, function () {
            return new EventBridgeClient(config('services.aws.event-bridge'));
        });

        $this->app->singleton('App\Services\BrandService', function ($app) {
            return new BrandService(
                $app->make(BrandRepositoryInterface::class),
                $app->make(BrandProductRepositoryInterface::class)
            );
        });

        $this->app->singleton('App\Services\AccountService', function ($app) {
            return new AccountService(
                $app->make(AccountRepositoryInterface::class),
                $app->make(BrandRepositoryInterface::class)
            );
        });
        
        $this->app->singleton('App\Services\Products\PortalProProductService', function ($app) {
            return new PortalProProductService(
                $app->make(ProductService::class),
                $app->make(ProductConfigService::class),
                $app->make(BrandProductConfigService::class),
                $app->make(BrandService::class),
                $app->make(BrandProduct::class)
            );
        });
        $this->app->singleton('App\Services\Products\ReviewProductService', function ($app) {
            return new ReviewProductService(
                $app->make(BrandProductService::class),
                $app->make(ProductService::class),
                $app->make(BrandProductRepositoryInterface::class)
            );
        });
        $this->app->singleton('App\Services\Products\CustomizedSurveyProductService', function () {
            return new CustomizedSurveyProductService();
        });
        $this->app->singleton('App\Services\Products\BirthdayEmailsProductService', function ($app) {
            return new BirthdayEmailsProductService(
                $app->make(ProductConfigService::class),
                $app->make(BrandProductConfigService::class),
                $app->make(BrandProductService::class)
            );
        });
        $this->app->singleton('App\Services\Products\RequireRoomNumberProductService', function ($app) {
            return new RequireRoomNumberProductService(
                $app->make(ProductConfigService::class),
                $app->make(BrandProductConfigService::class),
                $app->make(BrandProductService::class)
            );
        });
        $this->app->singleton('App\Services\Products\WidgetProductService', function ($app) {
            return new WidgetProductService(
                $app->make(ProductConfigService::class),
                $app->make(BrandProductConfigService::class)
            );
        });
        $this->app->singleton('App\Services\Products\LoyaltyProductService', function () {
            return new LoyaltyProductService();
        });
        $this->app->singleton('App\Services\Products\NotHotelProductService', function () {
            return new NotHotelProductService();
        });
        $this->app->singleton('App\Services\SatisfactionService', function ($app) {
            return new SatisfactionService(
                $app->make(SurveyRepository::class),
                $app->make(SatisfactionRepository::class),
                $app->make(UserSurveyQuestionAnswerRepository::class),
                $app->make(UserSurveyRepository::class),
                $app->make(SurveyQuestionRepository::class),
                $app->make(QuestionBrandRepository::class),
                $app->make(BrandService::class),
                $app->make(UserSatisfactionRepository::class),
                $app->make(CustomizedSatisfactionAnswerRepository::class)
            );
        });
        $this->app->singleton('App\Services\Products\SatisfactionSurveyProductService', function ($app) {
            return new SatisfactionSurveyProductService(
                $app->make(SatisfactionService::class),
                $app->make(SurveyRepository::class),
                $app->make(IncidentRepositoryInterface::class),
                $app->make(UserSurveyRepository::class),
                $app->make(VisitService::class),
                $app->make(UserBrandRepository::class)
            );
        });
        $this->app->singleton('App\Services\Stats\LoyaltyStatsService', function () {
            return new LoyaltyStatsService();
        });

        /**
         * Dependency injection of the repository into service
         */
        $this->app->singleton('App\Services\Visits\VisitService', function ($app) {
            return new VisitService(
                $app->make(DeviceRepository::class),
                $app->make(AccessTypeRepository::class)
            );
        });
        $this->app->singleton('App\Services\Users\UserService', function ($app) {
            return new UserService(
                $app->make(UserRepository::class),
                $app->make(UserBrandRepository::class),
                $app->make(UserHotelRepository::class),
                $app->make(BrandRepositoryInterface::class)
            );
        });
        $this->app->singleton('App\Services\Users\UserHotelService', function ($app) {
            return new UserHotelService(
                $app->make(UserHotelRepositoryInterface::class)
            );
        });
        $this->app->singleton('App\Services\Users\UserSatisfactionService', function ($app) {
            return new UserSatisfactionService(
                $app->make(UserSatisfactionRepositoryInterface::class)
            );
        });
        $this->app->singleton('App\Services\Users\UserVisitService', function ($app) {
            return new UserVisitService(
                $app->make(UserVisitRepositoryInterface::class)
            );
        });
        $this->app->singleton('App\Services\Users\UserVoucherService', function ($app) {
            return new UserVoucherService(
                $app->make(UserVoucherRepositoryInterface::class)
            );
        });
        $this->app->singleton('App\Services\Users\ConnectionHistoryService', function ($app) {
            return new ConnectionHistoryService(
                $app->make(ConnectionHistoryRepositoryInterface::class)
            );
        });
        $this->app->singleton('App\Services\BrandProductService', function ($app) {
            return new BrandProductService(
                $app->make(BrandProductRepositoryInterface::class),
                $app->make(BrandService::class),
                $app->make(BrandRepository::class),
                $app->make(ProductRepository::class)
            );
        });
        $this->app->singleton('App\Services\BrandService', function ($app) {
            return new BrandService(
                $app->make(BrandRepositoryInterface::class),
                $app->make(BrandProductRepository::class)
            );
        });
        $this->app->singleton(DeviceBlacklistService::class, function ($app) {
            return new DeviceBlacklistService($app->make(DeviceBlacklistRepositoryInterface::class));
        });
        $this->app->singleton(OfferWifiService::class, function ($app) {
            return new OfferWifiService($app->make(OfferWifiRepositoryInterface::class), $app->make(BrandService::class));
        });
        $this->app->singleton(BouncerService::class, function ($app) {
            return new BouncerService();
        });
        $this->app->singleton(OfferService::class, function ($app) {
            return new OfferService(
                $app->make(OfferRepositoryInterface::class),
                $app->make(BrandRepositoryInterface::class)
            );
        });

        $this->app->singleton(AutocheckinProductService::class, function ($app) {
            return new AutocheckinProductService(
                $app->make(ProductService::class),
                $app->make(BrandProductConfigService::class),
                $app->make(ProductConfigService::class),
                $app->make(BrandProductService::class)
            );
        });

        /**
         * TODO: move to a AppRepositoryInterfaceProvider
         * We need to bind the interface to its repository in order to inject dependency using the interface.
         * https://laravel.com/docs/5.8/container#binding-interfaces-to-implementations
         */
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(QuestionResponseRepositoryInterface::class, QuestionResponseRepository::class);
        $this->app->bind(QuestionResponseTextRepositoryInterface::class, QuestionResponseTextRepository::class);
        $this->app->bind(IncidentRepositoryInterface::class, IncidentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserBrandRepositoryInterface::class, UserBrandRepository::class);
        $this->app->bind(UserHotelRepositoryInterface::class, UserHotelRepository::class);
        $this->app->bind(UserSatisfactionRepositoryInterface::class, UserSatisfactionRepository::class);
        $this->app->bind(UserVisitRepositoryInterface::class, UserVisitRepository::class);
        $this->app->bind(UserVoucherRepositoryInterface::class, UserVoucherRepository::class);
        $this->app->bind(BookingFunnelRepositoryInterface::class, BookingFunnelRepository::class);
        $this->app->bind(ConnectionHistoryRepositoryInterface::class, ConnectionHistoryRepository::class);
        $this->app->bind(SatisfactionRepositoryInterface::class, SatisfactionRepository::class);
        $this->app->bind(SocialMediaRepositoryInterface::class, SocialMediaRepository::class);
        $this->app->bind(SocialMediaShareRepositoryInterface::class, SocialMediaShareRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(DeviceBlacklistRepositoryInterface::class, DeviceBlacklistRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductConfigRepositoryInterface::class, ProductConfigRepository::class);
        $this->app->bind(BrandProductConfigRepositoryInterface::class, BrandProductConfigRepository::class);
        $this->app->bind(BrandProductRepositoryInterface::class, BrandProductRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(OfferWifiRepositoryInterface::class, OfferWifiRepository::class);
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(GdprServiceInterface::class, GdprService::class);
        $this->app->bind(GdprRepositoryInterface::class, GdprRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(QuestionTextRepositoryInterface::class, QuestionTextRepository::class);
    }
}
