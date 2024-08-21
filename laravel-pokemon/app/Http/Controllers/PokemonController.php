<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller {
    public function index()
    {
        $data = [];

        $response = Http::get('http://golang:8080/mypokemon');
        if ($response->successful()) {
            $response = $response->json();
            $data = $response['data'];
        }

        return view('index', ['data' => $data]);
    }

    public function store(int $id)
    {
        $response = Http::post('http://golang:8080/pokemon/' . $id);
        return response()->json($response->json(), $response->getStatusCode());
    }

    public function list()
    {
        $response = Http::get('http://golang:8080/pokemon');
        return response()->json($response->json(), $response->getStatusCode());
    }

    public function delete(int $id, Request $request)
    {
        validator($request->all(), [
            'release' => 'required|number|gt:0'
        ]);
        $response = Http::delete('http://golang:8080/mypokemon/' . $id . '?release=' . $request->release);
        return response()->json($response->json(), $response->getStatusCode());
    }

    public function update(int $id, Request $request)
    {
        validator($request->all(), [
            'nickname' => 'required|string'
        ]);
        $response = Http::put('http://golang:8080/mypokemon/' . $id . '/' . $request->nickname);
        return response()->json($response->json(), $response->getStatusCode());
    }

    public function detail(int $id)
    {
        $response = Http::get('http://golang:8080/pokemon/' . $id);
        return response()->json($response->json(), $response->getStatusCode());
    }
}
