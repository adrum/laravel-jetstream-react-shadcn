<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class PasskeysTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_shares_passkey_state(): void
    {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $this->get('/user/profile')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Profile/Show')
                ->where('canManagePasskeys', true)
                ->has('passkeys', 0)
            );
    }

    public function test_profile_page_lists_the_users_passkeys(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $user->passkeys()->create([
            'name' => 'MacBook Pro',
            'credential_id' => 'credential-id',
            'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
        ]);

        $this->actingAs($user);

        $this->get('/user/profile')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('passkeys', 1)
                ->where('passkeys.0.name', 'MacBook Pro')
            );
    }

    public function test_passkeys_can_be_deleted(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $passkey = $user->passkeys()->create([
            'name' => 'MacBook Pro',
            'credential_id' => 'credential-id',
            'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
        ]);

        $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->delete(route('passkey.destroy', $passkey))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('passkeys', ['id' => $passkey->id]);
    }

    public function test_passkey_management_requires_a_confirmed_password(): void
    {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $this->get(route('passkey.registration-options'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_passkeys_are_deleted_with_the_user(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $passkey = $user->passkeys()->create([
            'name' => 'MacBook Pro',
            'credential_id' => 'credential-id',
            'credential' => ['aaguid' => '00000000-0000-0000-0000-000000000000'],
        ]);

        $user->delete();

        $this->assertDatabaseMissing('passkeys', ['id' => $passkey->id]);
    }
}
