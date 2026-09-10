<?php

namespace App\Console\Commands;

use App\User;
use App\UserGuid;
use App\UserVisits;
use Illuminate\Console\Command;

class GenerateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:user {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a user in our DB ';

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

        $email = $this->argument('email');

        factory(User::class, 1)->create($email ? ['email' => $email] : [])->each(function ($user) {
            $user->userGuid()->save(factory(UserGuid::class)->make());
            $user->visits()->save(factory(UserVisits::class)->states('chain')->create());
        });
    }
}
