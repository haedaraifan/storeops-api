<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddProductHistoryResource;
use App\Http\Resources\RestockProductHistoryResource;
use App\Models\AddProductHistory;
use App\Models\RestockProductHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function listAddedProduct(Request $request): JsonResponse
    {
        $histories = AddProductHistory::get();

        return (AddProductHistoryResource::collection($histories))->response()->setStatusCode(200);
    }

    public function listRestockedProduct(Request $request): JsonResponse
    {
        $hisotries = RestockProductHistory::get();

        return (RestockProductHistoryResource::collection($hisotries))->response()->setStatusCode(200);
    }
}
