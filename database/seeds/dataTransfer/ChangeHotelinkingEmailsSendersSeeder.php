<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Hotel;
use App\Cadena;
use Carbon\Carbon;

class ChangeHotelinkingEmailsOnSendingEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hotel::where('sending_email', 'LIKE', '%@hotelinking.com%')->update(['sending_email' => 'noreply@hotelinking.com']);
        Cadena::where('email_envio', 'LIKE', '%@hotelinking.com%')->update(['email_envio' => 'noreply@hotelinking.com']);
    }
}