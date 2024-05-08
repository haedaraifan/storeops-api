<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddProductHistoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function listAddedProduct(Request $request): JsonResponse
    {
        $user = Auth::user();
        $histories = $user->addProductHistories;

        return (AddProductHistoryResource::collection($histories))->response()->setStatusCode(200);
    }
}
