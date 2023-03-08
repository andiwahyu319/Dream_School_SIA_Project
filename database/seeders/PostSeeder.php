<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i=0; $i < 10; $i++) { 
            $post = new Post;
            $post->body = $faker->realText();
            $post->user_id = rand(1, 20);
            $post->class_id = 1;
            $post->save();
        }

        for ($i=0; $i < 20; $i++) { 
            $comment = new Comment;
            $comment->body = $faker->sentence();
            $comment->user_id = rand(1, 20);
            $comment->post_id = rand(1, 10);
            $comment->save();
        }
    }
}
