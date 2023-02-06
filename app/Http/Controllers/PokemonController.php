<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPokemonRequest;
use App\Models\Pokemon;
use Illuminate\Http\JsonResponse;
use App\Services\PokemonService;

class PokemonController extends Controller
{
    protected $pokemonService;

    public function __construct(PokemonService $pokemonService)
    {
        $this->pokemonService = $pokemonService;
    }

    public function getPokemon(): JsonResponse
    {
        //I would not normally do this.
        //I would normally put the api in a queue, and since it only has to run once make a console command.
        //I did it this way I thought you would want it to run in page this way.
        ini_set('max_execution_time', 300);

        $pokemon = $this->pokemonService->getPokemon();

        return response()->json($pokemon);
    }

    public function update(EditPokemonRequest $request, Pokemon $pokemon): JsonResponse
    {
        $pokemon->update($request->validated());

        return response()->json('success');
    }

    public function delete(Pokemon $pokemon)
    {
        $pokemon->delete();
    }
}
