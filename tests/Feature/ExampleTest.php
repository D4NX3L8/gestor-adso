<?php

namespace Tests\Feature;

use App\Models\Aprendiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_redirects_to_the_apprentice_list(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/aprendices');
    }

    public function test_apprentice_list_is_paginated(): void
    {
        Aprendiz::factory()->count(11)->create();

        $response = $this->get('/aprendices');

        $response->assertOk()
            ->assertViewHas('aprendices', fn ($aprendices) => $aprendices->count() === 10
                && $aprendices->hasMorePages());
    }

    public function test_apprentice_list_can_be_searched_by_partial_name_and_keeps_the_search_when_paginating(): void
    {
        Aprendiz::factory()->count(11)->create(['nombre' => 'Camila Rivera']);
        Aprendiz::factory()->create(['nombre' => 'Andrés Gómez']);

        $response = $this->get('/aprendices?q=Camila');

        $response->assertOk()
            ->assertViewHas('aprendices', function ($aprendices) {
                return $aprendices->count() === 10
                    && $aprendices->hasMorePages()
                    && str_contains($aprendices->url(2), 'q=Camila');
            })
            ->assertSee('Camila Rivera')
            ->assertSee('Limpiar búsqueda')
            ->assertDontSee('Andrés Gómez');

        $this->get('/aprendices?q=Camila&page=2')
            ->assertViewHas('aprendices', fn ($aprendices) => $aprendices->count() === 1
                && $aprendices->first()->nombre === 'Camila Rivera');

        $this->get('/aprendices')
            ->assertViewHas('aprendices', fn ($aprendices) => $aprendices->total() === 12);
    }

    public function test_apprentice_can_be_created(): void
    {
        $data = [
            'nombre' => 'Ana Pérez',
            'documento' => '1234567890',
            'correo' => 'ana@example.com',
            'ficha_id' => 123456,
        ];

        $response = $this->post('/aprendices', $data);

        $response->assertRedirect('/aprendices')
            ->assertSessionHas('ok', 'Aprendiz creado');
        $this->assertDatabaseHas('aprendices', $data);
    }

    public function test_documento_and_correo_must_be_unique(): void
    {
        $aprendiz = Aprendiz::factory()->create();

        $this->post('/aprendices', [
            'nombre' => 'Otro aprendiz',
            'documento' => $aprendiz->documento,
            'correo' => 'otro@example.com',
        ])->assertSessionHasErrors('documento');

        $this->post('/aprendices', [
            'nombre' => 'Otro aprendiz',
            'documento' => '9876543210',
            'correo' => $aprendiz->correo,
        ])->assertSessionHasErrors('correo');
    }
}
