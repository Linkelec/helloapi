<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_profile_when_authenticated()
    {
        $admin = Admin::factory()->create();
        $token = $admin->createToken('auth-token')->plainTextToken;

        $response = $this->postJson('/api/profiles', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'status' => 'pending',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'first_name' => 'John',
                     'last_name' => 'Doe',
                     'status' => 'pending',
                 ]);
    }

    /** @test */
    public function it_cannot_create_a_profile_when_unauthenticated()
    {
        $response = $this->postJson('/api/profiles', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'status' => 'pending',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    public function it_can_get_active_profiles()
    {
        Profile::factory()->create(['status' => 'active']);
        Profile::factory()->create(['status' => 'inactive']);

        $response = $this->getJson('/api/profiles');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /** @test */
    public function it_can_get_profiles_with_status_when_authenticated()
    {
        $admin = Admin::factory()->create();
        $token = $admin->createToken('auth-token')->plainTextToken;

        Profile::factory()->create(['status' => 'active']);
        Profile::factory()->create(['status' => 'inactive']);

        $response = $this->getJson('/api/profiles', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['status' => 'active']);
    }

    /** @test */
    public function it_can_update_a_profile_when_authenticated()
    {
        $admin = Admin::factory()->create();
        $token = $admin->createToken('auth-token')->plainTextToken;

        $profile = Profile::factory()->create();

        $response = $this->putJson('/api/profiles/' . $profile->id, [
            'first_name' => 'Jane',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['first_name' => 'Jane']);
    }

    /** @test */
    public function it_can_delete_a_profile_when_authenticated()
    {
        $admin = Admin::factory()->create();
        $token = $admin->createToken('auth-token')->plainTextToken;

        $profile = Profile::factory()->create();

        $response = $this->deleteJson('/api/profiles/' . $profile->id, [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(204);
    }
}
