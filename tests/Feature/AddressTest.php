<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    // Lets refresh the database during testing
    use RefreshDatabase;
    
    // Create a private method to return an instance of created address
    private function createDummyAddress(): Address {
        // $address = new Address();
        // $address->user_id = 1;
        // $address->address_line_1 = 'No.5 Ayetoro Street';
        // $address->address_line_2 = 'After Roundabout';
        // $address->town = 'Epe';
        // $address->state = 'Lagos';
        // $address->save();
        $userId = 1;

        // State factory creation
        // Creating a factory address object with specified values, 
        // the values generated will be overwritten
        $address = Address::factory()->defaultAddress()->create(
            // Let a user id be provided for the created blogPost
            [
                // If the $userId is available, use it, else get a user id from the user object in the TestCase class
                'user_id' => $userId ?? $this->user()->id,
            ]
        );

        return $address;
    }

    private function createDummyUser(): User {
        $user = new User();
        $user->first_name = 'John';
        $user->last_name = 'Felix';
        $user->gender = 'male';
        $user->email = 'johnfelix@gmail.com';
        $user->username = 'johnFelix';
        $user->password = 'password';
        $user->phone_number = '08057368560';
        $user->account_status = 'active';
        $user->account_type = 'business';
        $user->save();

        return $user;
    }
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSeeThereIsNoAddress() {
        // Act
        $response = $this->get('/mechanics');

        // Assert
        $response->assertSeeText('No address found!');
    }

    public function testSee1AddressWhenThereIs1()
    {
        // Arrange
        $address = $this->createDummyAddress();

        // Act
        $response = $this->get('/mechanics');

        // Assert
        $response->assertSeeText('After Roundabout');

        // Test on database table that a record is container within a column
        $this->assertDatabaseHas('addresses', [
            'address_line_1' => 'No.5 Ayetoro Street'
        ]);
    }

    public function testSeeListOfUsers() {
        // Act
        $response = $this->get('/artisans');

        // Assert
        $response->assertSeeText('No user found!');
    }

    public function testSee1UserWithComments() {
        $user = $this->createDummyUser();
        // Arange 
        Comment::factory(4)->create([
            // Use the id of the dummy user just created
            'business_id' => $user->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('/artisans');

        $response->assertSeeText('4 comments');
    }

    public function testStoreValid() {
        $params = [
            'address_line_1' => 'No.5 Ayetoro Street',
            'address_line_2' => 'After Roundabout',
            'town' => 'Epe',
            'state' => 'Lagos'
        ];

        // $user = $this->user();
        // $this->actingAs($user);

        // 302 is the code for a successful redirect
        $this->actingAs($this->user())
            ->post('/artisans', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'The address was created!');
    }

    public function testStoreFail() {
        $params = [
            'address_line_1' => '',
            'town' => '',
        ];

        $this->actingAs($this->user())
            ->post('/artisans', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();
        $this->assertEquals($messages['address_line_1'][0], 'The address line 1 field is required.');
        $this->assertEquals($messages['town'][0], 'The town field is required.');
        $this->assertEquals($messages['state'][0], 'The state field is required.');
    }

    public function testUpdateValid() {
        // Arrange
        $address = $this->createDummyAddress();

        // Assert
        $this->assertDatabaseHas('addresses', [
            'address_line_1' => 'No.5 Ayetoro Street',
            'address_line_2' => 'After Roundabout',
            'town' => 'Epe',
            'state' => 'Lagos'
        ]);

        // Convert the address object to an array so all the columns can be tested
        // $this->assertDatabaseHas('addresses', $address->toArray());

        $params = [
            'address_line_1' => 'No.10 Ayetoro Street',
            'address_line_2' => 'Before Roundabout',
            'town' => 'Epe',
            'state' => 'Lagos'
        ];

        // Use the put method to perform an update
        $this->actingAs($this->user())
            ->put("/artisans/{$address->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        // Check for the updated content
        // Check for the session status message
        $this->assertEquals(session('status'), 'The address was updated!');
        // Check for changed content after updating
        $this->assertDatabaseMissing('addresses', [
            'address_line_1' => 'No.5 Ayetoro Street',
            'address_line_2' => 'After Roundabout',
            'town' => 'Epe',
            'state' => 'Lagos'
        ]);
        // Check database with new title
        $this->assertDatabaseHas('addresses', [
            'address_line_1' => 'No.10 Ayetoro Street',
            'address_line_2' => 'Before Roundabout',
            'town' => 'Epe',
            'state' => 'Lagos'
        ]);
    }

    public function testDelete() {
        // Arrange
        $address = $this->createDummyAddress();

        // First check that the address was actually stored
        $this->assertDatabaseHas('addresses', [
            'address_line_1' => 'No.5 Ayetoro Street',
            'address_line_2' => 'After Roundabout',
            'town' => 'Epe',
            'state' => 'Lagos'
        ]);

        $this->actingAs($this->user())
            ->delete("/artisans/{$address->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');
            
        // Check for the session status message
        $this->assertEquals(session('status'), 'Address was deleted!');
        // Check that the address deleted is not more there
        $this->assertDatabaseMissing('addresses', [
            'address_line_1' => 'No.5 Ayetoro Street',
            'address_line_2' => 'After Roundabout',
            'town' => 'Epe',
            'state' => 'Lagos'
        ]);
    }

    
}
