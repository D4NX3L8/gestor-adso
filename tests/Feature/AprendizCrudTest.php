<?php

namespace Tests\Feature;

use App\Models\Aprendiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AprendizCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_guest_is_redirected_to_login_when_opening_aprendices(): void
    {
        auth()->logout();

        $this->get(route('aprendices.index'))
            ->assertRedirect(route('login'));
    }

    public function test_aprendiz_can_be_created(): void
    {
        $data = [
            'nombre' => 'Ana Pérez',
            'documento' => '1234567890',
            'correo' => 'ana@example.com',
            'ficha_id' => 123456,
        ];

        $this->post(route('aprendices.store'), $data)
            ->assertRedirect(route('aprendices.index'))
            ->assertSessionHas('ok', 'Aprendiz creado correctamente.');

        $this->assertDatabaseHas('aprendices', $data);
    }

    public function test_invalid_aprendiz_data_returns_spanish_validation_messages(): void
    {
        $response = $this->from(route('aprendices.create'))
            ->post(route('aprendices.store'), [
                'nombre' => '',
                'documento' => '',
                'correo' => 'correo-invalido',
                'ficha_id' => -1,
            ]);

        $response
            ->assertRedirect(route('aprendices.create'))
            ->assertSessionHasErrors(['nombre', 'documento', 'correo', 'ficha_id'])
            ->assertSessionHas('errors', function ($errors) {
                return $errors->first('nombre') === 'El nombre es obligatorio.'
                    && $errors->first('documento') === 'El documento es obligatorio.'
                    && $errors->first('correo') === 'El correo no es válido.'
                    && $errors->first('ficha_id') === 'La ficha no puede ser negativa.';
            })
            ->assertSessionHas('_old_input.correo', 'correo-invalido');
    }

    public function test_duplicate_documento_and_correo_are_rejected_in_spanish(): void
    {
        $aprendiz = Aprendiz::factory()->create();

        $documentoResponse = $this->from(route('aprendices.create'))
            ->post(route('aprendices.store'), [
                'nombre' => 'Otro aprendiz',
                'documento' => $aprendiz->documento,
                'correo' => 'otro@example.com',
            ]);

        $documentoResponse
            ->assertRedirect(route('aprendices.create'))
            ->assertSessionHasErrors('documento')
            ->assertSessionHas('errors', function ($errors) {
                return $errors->first('documento') === 'El documento ya existe.';
            });

        $correoResponse = $this->from(route('aprendices.create'))
            ->post(route('aprendices.store'), [
                'nombre' => 'Otro aprendiz',
                'documento' => '9876543210',
                'correo' => $aprendiz->correo,
            ]);

        $correoResponse
            ->assertRedirect(route('aprendices.create'))
            ->assertSessionHasErrors('correo')
            ->assertSessionHas('errors', function ($errors) {
                return $errors->first('correo') === 'El correo ya existe.';
            });
    }

    public function test_aprendiz_can_be_edited_and_updated_without_changing_unique_fields(): void
    {
        $aprendiz = Aprendiz::factory()->create();

        $this->get(route('aprendices.edit', $aprendiz))
            ->assertOk()
            ->assertViewHas('aprendiz', $aprendiz);

        $this->put(route('aprendices.update', $aprendiz), [
            'nombre' => 'Nombre actualizado',
            'documento' => $aprendiz->documento,
            'correo' => $aprendiz->correo,
            'ficha_id' => $aprendiz->ficha_id,
        ])
            ->assertRedirect(route('aprendices.index'))
            ->assertSessionHas('ok', 'Aprendiz actualizado correctamente.');

        $this->assertDatabaseHas('aprendices', [
            'id' => $aprendiz->id,
            'nombre' => 'Nombre actualizado',
            'documento' => $aprendiz->documento,
            'correo' => $aprendiz->correo,
        ]);
    }

    public function test_update_rejects_unique_values_owned_by_a_different_aprendiz(): void
    {
        $aprendiz = Aprendiz::factory()->create();
        $otherAprendiz = Aprendiz::factory()->create();

        $this->from(route('aprendices.edit', $aprendiz))
            ->put(route('aprendices.update', $aprendiz), [
                'nombre' => 'Nombre actualizado',
                'documento' => $otherAprendiz->documento,
                'correo' => $otherAprendiz->correo,
                'ficha_id' => null,
            ])
            ->assertRedirect(route('aprendices.edit', $aprendiz))
            ->assertSessionHasErrors(['documento', 'correo']);
    }

    public function test_aprendiz_can_be_deleted(): void
    {
        $aprendiz = Aprendiz::factory()->create();

        $this->delete(route('aprendices.destroy', $aprendiz))
            ->assertRedirect(route('aprendices.index'))
            ->assertSessionHas('ok', 'Aprendiz eliminado correctamente.');

        $this->assertDatabaseMissing('aprendices', ['id' => $aprendiz->id]);
    }

    public function test_aprendices_can_be_searched_by_name_or_document_and_filter_is_kept_when_paginating(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            Aprendiz::factory()->create([
                'nombre' => 'Camila Rivera',
                'documento' => '55555'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
            ]);
        }

        Aprendiz::factory()->create([
            'nombre' => 'Andrés Gómez',
            'documento' => '9999999999',
        ]);

        $response = $this->get(route('aprendices.index', ['q' => 'Camila']));

        $response
            ->assertOk()
            ->assertViewHas('aprendices', function ($aprendices) {
                return $aprendices->count() === 10
                    && $aprendices->hasMorePages()
                    && str_contains($aprendices->url(2), 'q=Camila');
            })
            ->assertSee('Camila Rivera')
            ->assertSee('Limpiar')
            ->assertDontSee('Andrés Gómez');

        $this->get(route('aprendices.index', ['q' => '5555500001']))
            ->assertViewHas('aprendices', fn ($aprendices) => $aprendices->total() === 1
                && $aprendices->first()->documento === '5555500001');

        $this->get(route('aprendices.index', ['q' => 'Camila', 'page' => 2]))
            ->assertViewHas('aprendices', fn ($aprendices) => $aprendices->count() === 1
                && $aprendices->first()->nombre === 'Camila Rivera');

        $this->get(route('aprendices.index'))
            ->assertViewHas('aprendices', fn ($aprendices) => $aprendices->total() === 12);
    }

    public function test_instructor_can_create_and_edit_but_cannot_delete(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $aprendiz = Aprendiz::factory()->create();

        $this->actingAs($instructor);

        $this->get(route('aprendices.create'))->assertOk();
        $this->get(route('aprendices.edit', $aprendiz))->assertOk();
        $this->delete(route('aprendices.destroy', $aprendiz))->assertForbidden();
    }

    public function test_aprendiz_can_list_but_cannot_create(): void
    {
        $aprendizUser = User::factory()->create(['role' => 'aprendiz']);

        $this->actingAs($aprendizUser);

        $this->get(route('aprendices.index'))->assertOk();
        $this->get(route('aprendices.create'))->assertForbidden();
    }

    public function test_admin_can_perform_all_aprendiz_actions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $aprendiz = Aprendiz::factory()->create();

        $this->actingAs($admin);

        $this->get(route('aprendices.create'))->assertOk();
        $this->get(route('aprendices.edit', $aprendiz))->assertOk();
        $this->delete(route('aprendices.destroy', $aprendiz))->assertRedirect(route('aprendices.index'));
    }
}
