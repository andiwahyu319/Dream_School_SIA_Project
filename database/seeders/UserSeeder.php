<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        User::factory()->create([
            "name" => "Sample Student",
            "email" => "student@college.com",
            "password" => Hash::make("1234567890"),
            "gender" => "male",
            "birthdate" => "1999-11-03",
            "address" => $faker->streetAddress(),
            "role" => "student",
        ]);
        User::factory()->create([
            "name" => "Sample Teacher",
            "email" => "teacher@college.com",
            "password" => Hash::make("1234567890"),
            "gender" => "male",
            "birthdate" => "1999-11-03",
            "address" => $faker->streetAddress(),
            "role" => "teacher",
        ]);

        for ($i=0; $i < 18; $i++) {
            $gender = ["male", "female"];
            $gender = $gender[array_rand($gender)];
            User::factory()->create([
                "name" => $faker->name($gender),
                "email" => $faker->email(),
                "password" => Hash::make($faker->word()),
                "gender" => $gender,
                "birthdate" => rand(1980, 2005). "-" . rand(1, 12) ."-" . rand(1,28),
                "address" => $faker->streetAddress(),
                "role" => "student",
            ]);
        }
    }
}
