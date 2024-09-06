<?php

namespace App\Http\Controllers;

use App\Helpers\DateFormatHelper;
use App\Http\Resources\AddProductHistoryResource;
use App\Http\Resources\RestockProductHistoryResource;
use App\Models\AddProductHistory;
use App\Models\RestockProductHistory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function listAddedProduct(Request $request): JsonResponse
    {
        $search = $request->query("search");
        $range = $request->query("range", "all");
        $from = $request->query("from");
        $to = $request->query("to");
        $isPaginate = $request->query("paginate", "true");
        $query = AddProductHistory::orderBy("date", "DESC");

        if($from && $to) {
            $fromDate = Carbon::parse($from)->startOfDay();
            $toDate = Carbon::parse($to)->endOfDay();
            $query->whereBetween("date", [$fromDate, $toDate]);
        }

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

        if($search) {
            $query->where(function ($q) use ($search) {
                $formattedDate = DateFormatHelper::toEnglishDate($search);
                $q->where("name", "like", "%{$search}%")
                    ->orWhereRaw("DATE_FORMAT(date, '%W, %e %M %Y') LIKE ?", ["%{$formattedDate}%"]);
            });
        }

        $histories = $isPaginate === "false" ? $query->get() : $query->paginate(10);

        return (AddProductHistoryResource::collection($histories))->response()->setStatusCode(200);
    }

    public function listRestockedProduct(Request $request): JsonResponse
    {
        $search = $request->query("search");
        $range = $request->query("range", "all");
        $from = $request->query("from");
        $to = $request->query("to");
        $isPaginate = $request->query("paginate", "true");
        $query = RestockProductHistory::orderBy("date", "DESC");

        if($from && $to) {
            $startDate = Carbon::parse($from)->startOfDay();
            $endDate = Carbon::parse($to)->endOfDay();
            $query->whereBetween("date", [$startDate, $endDate]);
        }

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

        if($search) {
            $query->where(function ($q) use ($search) {
                $formattedDate = DateFormatHelper::toEnglishDate($search);
                $q->where("name", "like", "%{$search}%")
                    ->orWhereRaw("DATE_FORMAT(date, '%W, %e %M %Y') LIKE ?", ["%{$formattedDate}%"]);
            });
        }

        $hisotries = $isPaginate === "false" ? $query->get() : $query->paginate(10);

        return (RestockProductHistoryResource::collection($hisotries))->response()->setStatusCode(200);
    }
}
