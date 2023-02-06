<?php

namespace App\Http\Controllers;

use App\Api\GetPokemon;
use App\Models\Pokemon;

class HomepageController extends Controller
{
    public function index()
    {
//        $pokemon = Pokemon::all();
//
//        if ($pokemon->isEmpty()) {
//            $getPokemon = new GetPokemon();
//
//            Pokemon::insert($getPokemon->getGenerationOne());
//
//            $pokemon = Pokemon::all();
//        }

        return view('pokemon');
    }
}
