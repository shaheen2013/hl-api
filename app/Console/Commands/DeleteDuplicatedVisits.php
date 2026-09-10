<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DeleteDuplicatedVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:duplicates {from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->validateArguments()) {
            $from = Carbon::createFromFormat('Y-m-d', $this->argument('from'));

            // Select count of user brand_id that are duplicated, as we'll update this
            DB::table('new_visit')
                ->selectRaw("user_brand_id")
                ->whereRaw("check_in = check_out")
                ->where('check_in', ">", $from)
                ->whereNull('reservation')
                ->groupBy("user_brand_id")
                ->pluck('user_brand_id')
                ->each(
                    function ($userBrandId) use ($from) {
                        // We need the stayTime in order of update the checkout date, and get the chunks to generate the visits
                        $stayTime = $this->getStayTime($userBrandId);

                        // Generate the visit chunks
                        $visits = $this->getVisits($userBrandId, $from, $stayTime);

                        // Create a range of days from checkin/checkout to calculate stayTimeDays
                        $range = CarbonPeriod::create(
                            $visits->min('check_in'),
                            $visits->max('check_out')
                        );


                        // If the range calculated is bigger than the stayTime means that we'll need to create more than 1 visit
                        if ($range->count() > $stayTime) {
                            // We make chunks of the range, based in days of stayTime
                            collect($range)
                                ->chunk($stayTime + 1)
                                ->map(function ($chunk) use ($visits) {
                                    // Get the visits that met the conditions on checkin/checkout
                                    return $visits
                                        ->where('check_in', '>=', $chunk->first()->toDateTimeString())
                                        ->where('check_out', '<=', $chunk->last()->endOfDay()->toDateTimeString());
                                })->each(function ($visits) use ($stayTime) {
                                    // Deploy for each set of reservations
                                    $this->dispatchUpdate($visits, $stayTime);
                                });
                        } else {
                            $this->dispatchUpdate($visits, $stayTime);
                        }
                    }
                );
        } else {
            return;
        }
    }


    /**
     * Return visits for that specific userBrandId
     *
     * @param int $userBrandId
     * @param Carbon $from
     *
     * @return Collection
     *
     */
    private function getVisits(int $userBrandId, Carbon $from): Collection
    {
        Log::info('DeleteDuplicatedVisits', [
            'message' => "Getting visits",
            "userBrandId" => $userBrandId,
            "from" => $from
        ]);

        // We part in chunks the visits, because if a visit expands over two weeks, we need to create two visits.
        return DB::table('new_visit')
            ->select('id', 'check_in', 'check_out')
            ->where('user_brand_id', $userBrandId)
            ->where('check_in', '>=', $from)
            ->whereRaw("check_in = check_out")
            ->get();
    }


    /**
     * Get the stay time for an hotel using the user_brand_id
     *
     * @param int $userBrandId
     *
     * @return int
     */
    private function getStayTime(int $userBrandId): int
    {
        Log::info('DeleteDuplicatedVisits', [
            'message' => "Getting stayTime",
            "userBrandId" => $userBrandId,
        ]);

        return DB::table('new_visit')
            ->select('hoteles.stay_time')
            ->join('new_user_brand', 'new_visit.user_brand_id', 'new_user_brand.id')
            ->join('brands', 'new_user_brand.brand_id', 'brands.id')
            ->join('hoteles', 'brands.hotel_id', 'hoteles.id')
            ->where('new_visit.user_brand_id', $userBrandId)
            ->get()
            ->first()
            ->stay_time;
    }

    /**
     * Update connections duplicated with the new visit id
     *
     * @param int $visitId
     * @param int $visitToKeppId
     *
     */
    private function updateConnection(int $visitId, int $visitToKeepId)
    {

        Log::info('DeleteDuplicatedVisits', [
            'message' => "Updating connection visits",
            "visitId" => $visitId,
            "visitToKeepId" => $visitToKeepId
        ]);

        return DB::table('new_connection')
            ->where('visit_id', $visitId)
            ->update([
                'visit_id' => $visitToKeepId
            ]);
    }


    /**
     * Delete the specified records in new_visit table
     *
     * @param int $visitId
     *
     * @return bool
     */
    private function deleteDuplicateVisits(int $visitId): bool
    {
        Log::info('DeleteDuplicatedVisits', [
            'message' => "Deleting duplicated visits",
            "visitId" => $visitId,
        ]);

        return DB::table('new_visit')
            ->where('id', $visitId)
            ->delete();
    }

    /**
     * Update the check_out date based in stayTime and check_inm dates
     *
     * @param int $visitId
     * @param int stayTime
     *
     * @return bool
     */
    private function updateVisit(int $visitId, int $stayTime): bool
    {
        Log::info('DeleteDuplicatedVisits', [
            'message' => "Updating visits",
            "visitId" => $visitId,
            "stayTime" => $stayTime
        ]);

        return DB::update("UPDATE new_visit 
                    SET check_out = DATE_ADD(check_out, INTERVAL {$stayTime} DAY) 
                    WHERE id = {$visitId} 
                    AND reservation IS NULL;");
    }


    /**
     * Function to validate the rguments provided to the command
     */
    private function validateArguments()
    {
        $validator = Validator::make($this->arguments(), [
            'from' => ['required', 'date', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            $this->output->error("There was an error in arguments, see info below");

            foreach ($validator->errors()->all() as $error) {
                $this->output->note($error);
            }
            return false;
        }

        return true;
    }

    private function dispatchUpdate($visits, $stayTime)
    {

        // We keep the first visit,as the others we'll remove it
        $visitToKeep = $visits->shift();

        // For each visit we'll update the connection id to the first visit and then delete it
        $visits->each(function ($visit) use ($visitToKeep) {
            $this->updateConnection($visit->id, $visitToKeep->id);

            $this->deleteDuplicateVisits($visit->id);
        });

        // finally update the check_in/check_out date
        $this->updateVisit($visitToKeep->id, $stayTime);
    }
}
