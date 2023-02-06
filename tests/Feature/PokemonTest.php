<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_homepage()
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_get_pokemon_function_returns_first_5_pokemon_results()
    {
        $pokemon = Pokemon::factory()->count(100)->create();

        $response = $this->get(route('api.pokemon.get'));

        $response->assertOk()
            ->assertSee([$pokemon[0]->name, $pokemon[4]->name])
            ->assertDontSee($pokemon[5]->name);
    }

    public function test_update_pokemon_updates_the_database()
    {
        $oldPokemon = Pokemon::factory()->create();
        $newPokemon = Pokemon::factory()->raw();
        unset($newPokemon['image']);

        $this->patch(route('api.pokemon.update',[...$newPokemon, 'pokemon' => $oldPokemon->id]))
            ->assertOk();

        $this->assertDatabaseHas('pokemon', $newPokemon);

    }

    public function test_pokemon_gets_deleted()
    {
        $pokemon = Pokemon::factory()->create();

        $this->delete(route('api.pokemon.delete',['pokemon' => $pokemon->id]))
            ->assertOk();

        $this->assertDatabaseMissing('pokemon', $pokemon->toArray());
    }


    /**
    * @dataProvider updatePokemon
    */
    public function test_update_pokemon_validation($invalidData, $invalidFields)
    {
        $oldPokemon = Pokemon::factory()->create();

        $this->patch(route('api.pokemon.update',[...$invalidData, 'pokemon' => $oldPokemon->id]))
            ->assertJsonValidationErrors($invalidFields)
            ->assertStatus(422);

        $this->assertDatabaseMissing('pokemon', $invalidData);
    }

    public function updatePokemon(): array
    {
        $attributes = [
            'name' => fake()->name,
            'weight' => fake()->numberBetween(10, 20),
            'height' => fake()->numberBetween(10, 50),
        ];

        return [
            [
                [...$attributes, 'name' => null],
                ['name']
            ],
            [
                [...$attributes, 'weight' => null],
                ['weight']
            ],
            [
                [...$attributes, 'weight' => 'non integer'],
                ['weight']
            ],
            [
                [...$attributes, 'height' => null],
                ['height']
            ],
            [
                [...$attributes, 'height' => 'non integer'],
                ['height']
            ],
        ];
    }
}
