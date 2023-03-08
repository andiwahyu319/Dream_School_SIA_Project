<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=AttendanceSeeder
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $attendance = new Attendance;
        $attendance->name = $faker->sentence(2);
        $attendance->teacher = 2;
        $attendance->class_id = 1;
        $attendance->key = substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 10);
        $attendance->method = 1;
        $attendance->start = date("Y-m-d H:i:s");
        $attendance->end = date("Y-m-d H:i:s", time() + 36000);
        $attendance->save();
    }
}
