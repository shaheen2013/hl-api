<?php

use App\UserFacebook;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateBirthdayFormatFacebook extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
    */
    public function run()
    {
          $query = UserFacebook::where('birthday', 'regexp', '/');

          $this->command->getOutput()->progressStart($query->count());

          $query->chunk(1000, function($current_facebook_birthdays) {
               foreach($current_facebook_birthdays as $current_birthday_object) {

                    $new_birthday = Carbon::createFromFormat('m/d/Y', $current_birthday_object->birthday)->toDateString();
                    
                    $current_birthday_object->birthday = $new_birthday;
                    $current_birthday_object->save();  
               }
          });
          
     }

}
