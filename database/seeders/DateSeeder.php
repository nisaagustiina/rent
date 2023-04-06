<?php

namespace Database\Seeders;

use App\Models\Date;
use Illuminate\Database\Seeder;

class DateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dates = [[
            'nama' => 'offline',
            'service' => '["test", "test2"]'
        ],[
            'nama' => 'online',
            'service' => '["test", "test2"]'
        ]];

        foreach($dates as $date){
            Date::insert($date);
        }
    }
}
