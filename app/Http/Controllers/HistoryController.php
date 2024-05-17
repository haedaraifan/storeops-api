<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddProductHistoryResource;
use App\Models\AddProductHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function listAddedProduct(Request $request): JsonResponse
    {
        $histories = AddProductHistory::get();

        return (AddProductHistoryResource::collection($histories))->response()->setStatusCode(200);
    }
}
