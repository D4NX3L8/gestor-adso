<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_edit_assign_role_and_delete_users(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post(route('admin.users.store'), [
            'name' => 'María User',
            'email' => 'maria@example.com',
            'role' => 'instructor',
            'password' => 'initial-pass',
            'password_confirmation' => 'initial-pass',
        ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('ok', 'Usuario creado correctamente.');

        $user = User::where('email', 'maria@example.com')->firstOrFail();
        $this->assertSame('instructor', $user->role);
        $this->assertTrue(Hash::check('initial-pass', $user->password));

        $this->put(route('admin.users.update', $user), [
            'name' => 'María Actualizada',
            'email' => 'maria@example.com',
            'role' => 'aprendiz',
            'password' => '',
            'password_confirmation' => '',
        ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('ok', 'Usuario actualizado correctamente.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'María Actualizada',
            'role' => 'aprendiz',
        ]);
        $this->assertTrue(Hash::check('initial-pass', $user->refresh()->password));

        $this->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('ok', 'Usuario eliminado correctamente.');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_user_listing_searches_name_and_email_filters_role_and_keeps_filters_on_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        User::factory()->count(11)->create([
            'name' => 'Persona Encontrada',
            'role' => 'instructor',
        ]);
        User::factory()->create([
            'name' => 'Otra Persona',
            'email' => 'target-email@example.com',
            'role' => 'aprendiz',
        ]);

        $this->get(route('admin.users.index', ['q' => 'Persona Encontrada', 'role' => 'instructor']))
            ->assertOk()
            ->assertViewHas('users', function ($users) {
                return $users->count() === 10
                    && $users->total() === 11
                    && $users->hasMorePages()
                    && str_contains($users->url(2), 'role=instructor');
            })
            ->assertSee('Persona Encontrada')
            ->assertDontSee('target-email@example.com');

        $this->get(route('admin.users.index', ['q' => 'target-email@example.com']))
            ->assertViewHas('users', fn ($users) => $users->total() === 1
                && $users->first()->email === 'target-email@example.com');
    }

    public function test_admin_cannot_delete_the_active_account_and_gets_a_clear_message(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error', 'No puedes eliminar tu propia cuenta de administrador activa.');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_only_admins_see_the_users_menu_and_non_admins_cannot_open_admin_routes(): void
    {
        $target = User::factory()->create(['role' => 'aprendiz']);

        foreach (['instructor', 'aprendiz'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->actingAs($user)
                ->get(route('aprendices.index'))
                ->assertOk()
                ->assertDontSee('Usuarios');
            $this->get(route('profile.edit'))
                ->assertOk()
                ->assertDontSee('Usuarios');

            $this->get(route('admin.users.index'))->assertForbidden();
            $this->get(route('admin.users.create'))->assertForbidden();
            $this->get(route('admin.users.edit', $target))->assertForbidden();
            $this->post(route('admin.users.store'), [])->assertForbidden();
            $this->put(route('admin.users.update', $target), [])->assertForbidden();
            $this->delete(route('admin.users.destroy', $target))->assertForbidden();
        }

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('aprendices.index'))
            ->assertOk()
            ->assertSee('Usuarios');
        $this->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Usuarios');
    }

    public function test_guests_are_redirected_before_entering_admin_routes(): void
    {
        $this->get(route('admin.users.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_role_helpers_match_each_supported_role(): void
    {
        $admin = new User(['role' => 'admin']);
        $instructor = new User(['role' => 'instructor']);
        $aprendiz = new User(['role' => 'aprendiz']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isInstructor());
        $this->assertTrue($instructor->isInstructor());
        $this->assertFalse($instructor->isAdmin());
        $this->assertTrue($aprendiz->isAprendiz());
        $this->assertFalse($aprendiz->isInstructor());
    }

    public function test_initial_admin_seeder_uses_private_configuration_and_seeds_no_shared_test_accounts(): void
    {
        config([
            'admin.name' => 'Initial Admin',
            'admin.email' => 'individual-admin@example.test',
            'admin.password' => 'private-test-password',
        ]);

        (new UserSeeder)->run();

        $this->assertDatabaseHas('users', [
            'name' => 'Initial Admin',
            'email' => 'individual-admin@example.test',
            'role' => 'admin',
        ]);
        $this->assertTrue(Hash::check(
            'private-test-password',
            User::where('email', 'individual-admin@example.test')->firstOrFail()->password
        ));
        $this->assertDatabaseMissing('users', ['email' => 'instructor@example.com']);
        $this->assertDatabaseMissing('users', ['email' => 'aprendiz@example.com']);
    }

    public function test_initial_admin_seeder_rejects_a_password_shorter_than_eight_characters(): void
    {
        config([
            'admin.name' => 'Initial Admin',
            'admin.email' => 'individual-admin@example.test',
            'admin.password' => 'short',
        ]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ADMIN_PASSWORD debe tener al menos 8 caracteres.');

        (new UserSeeder)->run();
    }
}
