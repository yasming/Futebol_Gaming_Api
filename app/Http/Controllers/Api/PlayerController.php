<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
class PlayerController extends Controller
{
    public function store(StorePlayerRequest $request)
    {
        $player = Player::create($request->validated());
        return response()->json(new PlayerResource($player),201);
    }

    public function update(StorePlayerRequest $request, Player $player)
    {
        $player->update($request->validated());
        return response()->json(new PlayerResource($player),200);
    }

}
