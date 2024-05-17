<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionResponseHelper;
use App\Http\Requests\NoteCreateRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class NoteController extends Controller
{
    private function getNote(int $noteId): Note
    {
        $note = Note::whereId($noteId)->first();
        if(!$note) {
            ExceptionResponseHelper::throwNotFoundError("Catatan tidak ditemukan.");
        }
        return $note;
    }

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

    public function get(int $noteId): NoteResource
    {
        $note = $this->getNote($noteId);

        return new NoteResource($note);
    }

    public function update(int $noteId, NoteUpdateRequest $request): NoteResource
    {
        $data = $request->validated();
        $note = $this->getNote($noteId);

        $note->fill($data);
        $note->save();

        return new NoteResource($note);
    }

    public function delete(int $noteId): JsonResponse
    {
        $note = $this->getNote($noteId);

        $note->delete();

        return response()->json([
            "message" => "Catatan berhasil dihapus."
        ])->setStatusCode(200);
    }
}
