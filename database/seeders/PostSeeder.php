<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        Post::create([
            'user_id' => $user->id,
            'title' => 'First Post',
            'content' => 'This is the first post content.'
        ]);

        Post::create([
            'user_id' => $user->id,
            'title' => 'Second Post',
            'content' => 'This is the second post content.'
        ]);
        Post::create([
            'user_id' => $user->id,
            'title' => 'third Post',
            'content' => 'This is the second post content.'
        ]);
    }
}
