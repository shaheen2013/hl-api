<?php

namespace App\Console\Commands;

use App\User;
use App\UserBrand;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\Log;

class PopulateUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:populate-user-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate user data to new_user_brand table to make user data unique by account';

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
     * @return int
     */
    public function handle()
    {
        $output = new ConsoleOutput();
        $progress = new ProgressBar($output, User::count());
        $progress->start();

        User::chunk(10000, function ($users) use ($progress) {
            foreach ($users as $user) {
                $userData = [
                    "name" => $user->name,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $user->email,
                    "gender" => $user->gender,
                    "birthdate" => $user->birthday,
                    "phone_number" => data_get($user->data, 'phone_number'),
                    "generation" => $user->generation,
                    "lang" =>  $user->lang,
                    "country" => $user->country,
                ];

                UserBrand::whereNull('user_data')->where(['user_id' => $user->id])->update([
                    "user_data" => $userData
                ]);
                
                $progress->advance();
            }
        });
        
        $progress->finish();
        $output->write(PHP_EOL);
    }
}
