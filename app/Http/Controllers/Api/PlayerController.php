<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\PlayerResourceCollection;
use App\Models\Player;
class PlayerController extends Controller
{
    public function index()
    {
        return response()->json(
            [
                'players' => new PlayerResourceCollection(Player::all()->load('team'))
            ],200);
    }

    public function store(StorePlayerRequest $request)
    {
        $player = Player::create($request->validated());
        return response()->json(new PlayerResource($player),201);
    }

    public function update(StorePlayerRequest $request, Player $player)
    {
        $player->update($request->validated());
        return response()->json(new PlayerResource($player->load('team')),200);
    }

}
