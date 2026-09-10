<?php

namespace App\Console\Commands;

use App\Services\Connections\ConnectionHistoryService;
use App\Services\Users\UserHotelService;
use App\Services\Users\UserSatisfactionService;
use App\Services\Users\UserVisitService;
use App\Services\Users\UserVoucherService;
use App\Services\Users\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:cleanUndeliverableUsersCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean undeliverable users from database';

    protected $userService;
    protected $userVisitService;
    protected $userHotelService;
    protected $userVoucherService;
    protected $userSatisfactionService;
    protected $connectionHistoryService;

    /**
     * CleanUndeliverableUsersCommand constructor.
     * @param UserService $userService
     * @param UserVisitService $userVisitService
     * @param UserHotelService $userHotelService
     * @param UserSatisfactionService $userSatisfactionService
     * @param UserVoucherService $userVoucherService
     * @param ConnectionHistoryService $connectionHistoryService
     */
    public function __construct(
        UserService $userService,
        UserVisitService $userVisitService,
        UserHotelService $userHotelService,
        UserSatisfactionService $userSatisfactionService,
        UserVoucherService $userVoucherService,
        ConnectionHistoryService $connectionHistoryService
    ) {

        parent::__construct();
        $this->userService = $userService;
        $this->userVoucherService = $userVoucherService;
        $this->userVisitService = $userVisitService;
        $this->userHotelService = $userHotelService;
        $this->userSatisfactionService = $userSatisfactionService;
        $this->userVoucherService = $userVoucherService;
        $this->connectionHistoryService = $connectionHistoryService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Do you wish to continue?')) {
            Log::debug("Start deleting undeliverable users");

            $users = $this->userService->getByLowerSendexLevel(0.40);
            $this->getOutput()->progressStart($users->count());

            try {
                foreach ($users->cursor() as $user) {
                    // TODO: Mover a un servicio
                    $this->connectionHistoryService->deleteConnectionHistoryByUserId($user->id);
                    $this->userHotelService->deleteUserHotelByUserId($user->id);
                    $this->userVisitService->deleteUserVisitByUserId($user->id);
                    $this->userSatisfactionService->deleteUserSatisfactionByUserId($user->id);
                    $this->userVoucherService->deleteUserVoucherByUserId($user->id);
                    $this->userService->deleteUser($user->id);

                    $this->getOutput()->progressAdvance();
                }
            } catch (\Exception $ex) {
                Log::error("Error deleting undeliverable users", [$ex->getMessage()]);
            }
            $this->getOutput()->progressFinish();
            Log::debug("Finish deleting undeliverable users");
        }
    }
}
