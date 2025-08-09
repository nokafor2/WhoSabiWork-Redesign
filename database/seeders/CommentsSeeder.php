<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        // if ($users->count() === 0) {
        //     $this->command->info('There are no users, so no comments will be added');
        //     return;
        // }
        
        $commentCount = (int)$this->command->ask('How many comments would you like?', 1000);        

        Comment::factory($commentCount)->make()->each(function($comment) use($users) {
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
    }
}
