<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddProductHistoryResource;
use App\Http\Resources\RestockProductHistoryResource;
use App\Models\AddProductHistory;
use App\Models\RestockProductHistory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    public function listAddedProduct(Request $request): JsonResponse
    {
        $range = $request->query("range", "all");
        $isPaginate = $request->query("paginate", "true");
        $query = AddProductHistory::orderBy("date", "DESC");

        switch($range) {
            case "daily":
                $query->whereDate("date", Carbon::today());
                break;
            case "weekly":
                $query->whereBetween("date", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case "monthly":
                $query->whereMonth("date", Carbon::now()->month)->whereYear("date", Carbon::now()->year);
                break;
            default:
                break;
        }
        $histories = $isPaginate === "false" ? $query->get() : $query->paginate(10);

        return (AddProductHistoryResource::collection($histories))->response()->setStatusCode(200);
    }

    public function listRestockedProduct(Request $request): JsonResponse
    {
        $range = $request->query("range", "all");
        $isPaginate = $request->query("paginate", "true");
        $query = RestockProductHistory::orderBy("created_at", "DESC");

        switch($range) {
            case "daily":
                $query->whereDate("date", Carbon::today());
                break;
            case "weekly":
                $query->whereBetween("date", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case "monthly":
                $query->whereMonth("date", Carbon::now()->month)->whereYear("date", Carbon::now()->year);
                break;
            default:
                break;
        }
        $hisotries = $isPaginate === "false" ? $query->get() : $query->paginate(10);

        return (RestockProductHistoryResource::collection($hisotries))->response()->setStatusCode(200);
    }
}
