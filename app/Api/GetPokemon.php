<?php

namespace App\Api;

use Illuminate\Support\Facades\Http;

final class GetPokemon
{
    //would consider storing baseUrl in .env in production. In addition, this should probably be in a queue.
    private string $baseUrl = 'https://pokeapi.co/api/v2/';

    private array $pokemon = [];

    public function getGenerationOne(): array
    {
        $response = Http::get($this->baseUrl . 'pokemon?limit=151&offset=0');

        $this->getPokemon(json_decode($response->body())->results);

        return $this->pokemon;
    }

    private function getPokemon($urls)
    {
        foreach ($urls as $url) {
            $response = Http::get($url->url);

            $response = json_decode($response->body());

            $this->pokemon[] = [
                'name' => $response->name,
                'weight' => $response->weight,
                'height' => $response->height,
                'image' => $response->sprites->front_default,
            ];
        }
    }
}
