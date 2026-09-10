<?php

namespace App\Console\Commands;

use App\User;
use App\UserGuid;
use Illuminate\Console\Command;

class HotelinkingMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates hotelinking_app and hotelinking_app_test';

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
        $this->call('migrate', ['--database' => 'mysql']);
        $this->call('migrate', ['--database' => 'test']);
    }
}
