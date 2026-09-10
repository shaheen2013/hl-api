<?php

use Illuminate\Database\Seeder;
use App\Services\Users\UserService;
use App\User;

class UpdateUsersNullGenerations extends Seeder
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /** 
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $query = User::where([
            ['generation', null],
            ['fecha_nacimiento', '!=', null],
            ['fecha_nacimiento', '!=', ''],
            ['fecha_nacimiento', '>=', '1927-01-01']
        ]);

        $count = $query->count();
        $this->command->info("----------------------------");
        $this->command->info("Found $count users to update");
        $this->command->info("----------------------------");

        $this->command->getOutput()->progressStart($count);

        $query->chunkById(1000, function ($users) {
            foreach ($users as $user) {
                $user->generation = $this->userService->getGeneration($user->birthday);
                $user->save();
                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();
    }
}
