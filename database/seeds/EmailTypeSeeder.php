<?php

use Illuminate\Database\Seeder;

class EmailTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\EmailType::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'review',
                'template' => 'review_email', 
                'type' => 'client'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'satisfaction',
                'template' => 'satisfaction_email', 
                'type' => 'client'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'satisfaction_warning',
                'template' => 'satisfaction_warning_email', 
                'type' => 'staff'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'birthday',
                'template' => 'birthday_email', 
                'type' => 'client'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'stay_offer',
                'template' => 'stayoffer_email', 
                'type' => 'client'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'satisfaction_thanks',
                'template' => 'satisfaction_thanks_email', 
                'type' => 'client'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'birthday_warning',
                'template' => 'notify_birthday', 
                'type' => 'staff'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 8],
            [
                'name' => 'loyalty_warning',
                'template' => 'regular_client_email', 
                'type' => 'staff'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 9],
            [
                'name' => 'satisfaction_follow_up',
                'template' => 'satisfaction_follow_up', 
                'type' => 'client'
            ]
        );
        
        App\EmailType::updateOrCreate(
            ['id' => 10],
            [
                'name' => 'customized_satisfaction',
                'template' => 'customized_satisfaction_email', 
                'type' => 'client'
            ]
        );
    }
}
