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
        $isPaginate = $request->query("paginate", "true");
        $histories = AddProductHistory::orderBy("date", "DESC");
        $histories = $isPaginate === "false" ? $histories->get() : $histories->paginate(10);

        return (AddProductHistoryResource::collection($histories))->response()->setStatusCode(200);
    }

    public function listRestockedProduct(Request $request): JsonResponse
    {
        $isPaginate = $request->query("paginate", "true");
        $hisotries = RestockProductHistory::orderBy("created_at", "DESC");
        $hisotries = $isPaginate === "false" ? $hisotries->get() : $hisotries->paginate(10);

        return (RestockProductHistoryResource::collection($hisotries))->response()->setStatusCode(200);
    }
}
