<?php

namespace App\Services;

use App\Api\GetPokemon;
use App\Models\Pokemon;

class PokemonService
{
    public function getPokemon($paginate = 5)
    {
        if (Pokemon::all()->isEmpty())
        {
            $getPokemon = new GetPokemon();

            Pokemon::insert($getPokemon->getGenerationOne());
        }

        return Pokemon::paginate($paginate);
    }
}
