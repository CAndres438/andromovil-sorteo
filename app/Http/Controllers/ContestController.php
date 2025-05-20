<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientResource;


class ContestController extends Controller
{
    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        return response()->json([
            'message' => 'Registro exitoso',
            'client' => $client,
        ], 201);
    }

    public function chooseWinner(): JsonResponse
    {
        if (Client::where('is_winner', true)->exists()) {
            return response()->json([
                'message' => 'Ya existe un ganador seleccionado.'
            ], 409);
        }

        if (Client::count() < 5) {
            return response()->json([
                'message' => 'Se requieren al menos 5 participantes para elegir un ganador.'
            ], 400);
        }

        $winner = Client::where('is_winner', false)->inRandomOrder()->first();
        $winner->update(['is_winner' => true]);

        return response()->json([
            'message' => 'Ganador seleccionado exitosamente 🎉',
            'winner' => $winner,
        ]);
    }

    public function getClients(): JsonResponse
    {
        $clients = Client::latest()->get();
        return response()->json([
            'clients' => ClientResource::collection($clients)
        ]);
    }


    public function count(): JsonResponse
    {
        return response()->json([
            'count' => Client::count()
        ]);
    }

    public function getWinner(): JsonResponse
    {
        $winner = Client::where('is_winner', true)->first();

        if (!$winner) {
            return response()->json(['message' => 'Aún no hay un ganador'], 404);
        }

        return response()->json(['winner' => $winner]);
    }
}
