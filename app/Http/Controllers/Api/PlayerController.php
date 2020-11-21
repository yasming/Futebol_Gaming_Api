<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayer;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
class PlayerController extends Controller
{
    public function store(StorePlayer $request)
    {
        $player = Player::create($request->validated());
        return response()->json(new PlayerResource($player),201);
    }

}
