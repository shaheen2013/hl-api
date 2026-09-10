<?php

namespace App\Console\Commands;

use App\Services\Users\UserService;
use App\Jobs\EmailResultRevalidatorJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EmailResultRevalidatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:email_result_revalidator {brand_id} {--until_sendex=0.6} {--email_result=} {--limit=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch jobs to revalidate users emails calling lambda email validator';

    protected $userService;

    /**
     *
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->acceptedEmailResults = ["risky", "undeliverable", "deliverable", "unknown", null];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get sendex and email results optional options
        $untilSendex = (float) $this->option('until_sendex');
        $emailResult = $this->option('email_result');
        $limit = (int) $this->option('limit');

        if (!in_array($emailResult, $this->acceptedEmailResults)) {
            $this->getOutput()->error("Invalid email result {$emailResult}, the valid ones are [" . implode(",", $this->acceptedEmailResults) . "]");
            return 0;
        }

        // Get the optional argument for a brandId
        $brandId = $this->argument('brand_id');
        // Print intro
        $this->getOutput()->title("Email result revalidation for\n  brandId: {$brandId}\n  untilSendex: {$untilSendex}\n  emailResult: {$emailResult}");

        $brandUsers = $this->userService->getByBrandIdAndLowerSendexLevel($brandId, $untilSendex, $emailResult);

        $count = $brandUsers->count();
        // Limit
        if ($count > $limit) {
            $this->getOutput()->warning("The command is limited to do max of {$limit} revalidations, you are trying to do {$count}... do you want to continue?\n\nFor that you can confirm passing a limit option.");
            return 0;
        }
        // In case of none
        if ($count == 0) {
            $this->getOutput()->success("No users to be revalidated");
            return 0;
        }
        // Othercase continue process
        if ($this->confirm("Total of [${count}] validations - Do you wish to continue?")) {
            Log::info("Start dispatching jobs to perform the revalidations", [
                'brandId' => $brandId,
                'untilSendex' => $untilSendex,
                'emailResult' => $emailResult,
                'limit' => $limit,
            ]);

            $this->getOutput()->progressStart($count);

            try {
                foreach ($brandUsers->cursor() as $brandUser) {
                    // Dispatch job
                    EmailResultRevalidatorJob::dispatch($brandUser->user_id);
                    $this->getOutput()->progressAdvance();
                }
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }
            $this->getOutput()->progressFinish();
            Log::info("Finish dispatching jobs");
        }
    }
}
