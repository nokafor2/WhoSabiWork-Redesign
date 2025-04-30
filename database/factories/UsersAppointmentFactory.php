<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersAppointment>
 */
class UsersAppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get the number of users
        $totalUsers = User::all()->count();
        $availableHours = ['eight_AM', 'eight_Thirty_AM', 
        'nine_AM', 'nine_Thirty_AM', 'ten_AM', 'ten_Thirty_AM', 'eleven_AM', 'eleven_Thirty_AM', 
        'twelve_PM', 'twelve_Thirty_PM', 'one_PM', 'one_Thirty_PM', 'two_PM', 'two_Thirty_PM', 
        'three_PM', 'three_Thirty_PM', 'four_PM', 'four_Thirty_PM', 'five_PM', 'five_Thirty_PM', 
        'six_PM', 'six_Thirty_PM', 'seven_PM', 'seven_Thirty_PM'];
        // $randNum = $this->faker->numberBetween(1, 5);
        $selectedHours = array_rand(array_flip($availableHours), 5);
        $hours = implode(",", $selectedHours);
        $decisionOptions = ['neutral', 'accepted', 'declined', 'canceled'];
        $decision = $this->faker->randomElement($decisionOptions);

        $user = $this->faker->randomElement($array = array('scheduler', 'entrepreneur'));
        $message = $this->faker->sentence();
        $userDeclineMessage = $this->decisionMessage($decision, $user, $message)[0];
        $schedulerCancelMessage = $this->decisionMessage($decision, $user, $message)[1];
        $userCancelMessage = $this->decisionMessage($decision, $user, $message)[2];
        
        return [
            'user_id' => $this->faker->numberBetween(1, $totalUsers),
            'scheduler_id' => $this->faker->numberBetween(1, $totalUsers),
            'appointment_date' => $this->faker->dateTimeBetween('now', '1 month'),
            'hours' => $hours,
            'appointment_message' => $this->faker->sentence(),
            'user_decision' => $decision,
            'user_decline_message' => $userDeclineMessage,
            'scheduler_cancel_message' => $schedulerCancelMessage,
            'user_cancel_message' => $userCancelMessage,
        ];
    }
    
    public function decisionMessage($decision, $user, $message) {
        if (($decision === 'neutral') || ($decision === 'accepted')) {
            $userDeclineMessage = '';
            $schedulerCancelMessage = '';
            $userCancelMessage = '';
        } elseif ($decision === 'declined') {
            $userDeclineMessage = "I'm fully booked.";
            $schedulerCancelMessage = '';
            $userCancelMessage = '';
        } elseif ($decision === 'canceled') {
            $userDeclineMessage = '';
            if ($user === 'scheduler') {
                $schedulerCancelMessage = $message;
                $userCancelMessage = '';
            } else {
                $schedulerCancelMessage = '';
                $userCancelMessage = $message;
            }
        }      

        return [$userDeclineMessage, $schedulerCancelMessage, $userCancelMessage];
    }
}
