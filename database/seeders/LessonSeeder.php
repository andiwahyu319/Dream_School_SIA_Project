<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 5; $i++) { 
            $lesson = new Lesson;
            $lesson->class_id = 1;
            $lesson->teacher = 2;
            $lesson->title = $faker->sentence(2);
            $lesson->body = $faker->paragraph();
            $lesson->save();
        }
    }
}
