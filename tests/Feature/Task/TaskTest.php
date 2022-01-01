<?php

namespace Tests\Feature\Task;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Assignment\Models\Assignment;
use Modules\Auth\Models\User;
use Modules\Balance\Models\Balance;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_post_task_status()
    {
        $user = User::create([
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'identification_number' => 329590355,
            'mobile_number' => 254700545727,
            'email' => $this->faker->email(),
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $assignment = Assignment::create([
            'question' => $this->faker()->text(),
            'category' => $this->faker()->word()
        ]);

        $response = $this->post('api/v1/tasks',[
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'task_completed' => $this->faker()->boolean(),
            'task_completed_at' => $this->faker()->time(),
            'assignment_category' => $this->faker()->word(),
            'assignment_rating' => $this->faker()->numberBetween(0,10),
            'assignment_earning' => $this->faker()->numberBetween(50,100),
         ]);

         $response->assertCreated();

        //  Test if task is complete, balance is updated by 10
        $response->getData()->task_completed ?
        assertEquals(Balance::where('user_id', $user->id)->pluck('balance')[0], 10) :
        assertEquals(Balance::where('user_id', $user->id)->pluck('balance')[0], 0);
    }

    public function test_user_can_get_complete_task_results()
    {
        $user = User::create([
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'identification_number' => 329590355,
            'mobile_number' => 254700545727,
            'email' => $this->faker->email(),
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $assignment = Assignment::create([
            'question' => $this->faker()->text(),
            'category' => $this->faker()->word()
        ]);

        $this->post('api/v1/tasks',[
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'task_completed' => $this->faker()->boolean(),
            'task_completed_at' => $this->faker()->time(),
            'assignment_category' => $this->faker()->word(),
            'assignment_rating' => $this->faker()->numberBetween(0,10),
            'assignment_earning' => $this->faker()->numberBetween(50,100),
        ]);

        $response = $this->get("api/v1/tasks?user={$user->id}");

        $response->assertOk();
    }
}
