<?php

declare(strict_types=1);

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_token_page_is_displayed()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('tokens.edit'));

        $response->assertStatus(200);
    }

    public function test_tokens_are_displayed_on_page()
    {
        $user = User::factory()->create();
        $user->createToken('Test Token 1');
        $user->createToken('Test Token 2');

        $response = $this
            ->actingAs($user)
            ->get(route('tokens.edit'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('settings/Tokens')
            ->has('tokens', 2)
            ->where('tokens.0.name', 'Test Token 1')
            ->where('tokens.1.name', 'Test Token 2')
        );
    }

    public function test_token_can_be_created()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('tokens.store'), [
                'name' => 'My API Token',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $response->assertSessionHas('token');

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'My API Token',
        ]);
    }

    public function test_token_name_is_required()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('tokens.store'), [
                'name' => '',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_token_name_must_be_string()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('tokens.store'), [
                'name' => 123,
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_token_name_must_not_exceed_max_length()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('tokens.store'), [
                'name' => str_repeat('a', 256),
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_token_can_be_deleted()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token');

        $response = $this
            ->actingAs($user)
            ->delete(route('tokens.destroy', $token->accessToken->id));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    public function test_user_cannot_delete_another_users_token()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherUserToken = $otherUser->createToken('Other User Token');

        $response = $this
            ->actingAs($user)
            ->delete(route('tokens.destroy', $otherUserToken->accessToken->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $otherUserToken->accessToken->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_token_page()
    {
        $response = $this->get(route('tokens.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_create_token()
    {
        $response = $this->post(route('tokens.store'), [
            'name' => 'Test Token',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_delete_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token');

        $response = $this->delete(route('tokens.destroy', $token->accessToken->id));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    public function test_created_token_can_be_used_for_authentication()
    {
        $user = User::factory()->create();
        $tokenResult = $user->createToken('API Token');
        $plainTextToken = $tokenResult->plainTextToken;

        // Use the token to make an authenticated request
        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $plainTextToken)
            ->get('/api/user');

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_deleted_token_cannot_be_used_for_authentication()
    {
        $user = User::factory()->create();
        $tokenResult = $user->createToken('API Token');
        $plainTextToken = $tokenResult->plainTextToken;

        // Delete the token
        $user->tokens()->delete();

        // Try to use the deleted token
        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $plainTextToken)
            ->withHeader('Accept', 'application/json')
            ->get('/api/user');

        $response->assertStatus(401);
    }
}
