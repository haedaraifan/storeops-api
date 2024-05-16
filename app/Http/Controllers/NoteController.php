<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteCreateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class NoteController extends Controller
{
    public function create(NoteCreateRequest $requst): JsonResponse
    {
        $user = Auth::user();
        $data = $requst->validated();

        $note = new Note($data);
        $note->user_id = $user->id;
        $note->save();

        return (new NoteResource($note))->response()->setStatusCode(201);
    }
}
