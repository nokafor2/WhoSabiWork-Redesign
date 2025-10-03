<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotographReply>
 */
class PhotographReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $comment = \App\Models\PhotographComment::factory();
        $replyUser = \App\Models\User::factory();
        
        return [
            'photograph_id' => function (array $attributes) {
                // Get the photograph ID from the comment
                return \App\Models\PhotographComment::find($attributes['comment_id'])->photograph_id;
            },
            'comment_id' => $comment,
            'user_id_comment' => function (array $attributes) {
                // Get the comment owner's user_id
                return \App\Models\PhotographComment::find($attributes['comment_id'])->user_id_comment;
            },
            'user_id_reply' => $replyUser, // User replying to the comment
            'reply' => $this->faker->sentence(8),
        ];
    }
}
