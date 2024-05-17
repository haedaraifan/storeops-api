<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteCreateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class NoteController extends Controller
{
    public function create(NoteCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $note = new Note($data);
        $note->save();

        return (new NoteResource($note))->response()->setStatusCode(201);
    }

    public function list(Request $request): JsonResponse
    {
        $notes = Note::get();

        return (NoteResource::collection($notes))->response()->setStatusCode(200);
    }
}
