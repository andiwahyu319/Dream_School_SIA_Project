<?php

namespace Database\Seeders;

use App\Models\ClassMember;
use App\Models\ClassRoom;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $class_room = new ClassRoom;
        $class_room->name = $faker->sentence(2);
        $class_room->description = $faker->sentence();
        $class_room->invitation_code = substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 15);
        $class_room->teacher = 2;
        $class_room->save();

        for ($i=1; $i <= 20; $i++) { 
            $class_member = new ClassMember;
            $class_member->user_id = $i;
            $class_member->class_id = 1;
            $class_member->save();
        }

    }
}
