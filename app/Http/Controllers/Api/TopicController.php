<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topik;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TopicController extends Controller
{
    public function index(): JsonResponse
    {
        $topics = Topik::withCount('pertanyaan')
            ->orderBy('nama')
            ->get(['id', 'nama'])
            ->map(fn($t) => [
                'id'                => $t->id,
                'nama'              => $t->nama,
                'jumlah_pertanyaan' => $t->pertanyaan_count,
            ]);

        return $this->success($topics);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:topik,nama',
        ]);

        $topic = Topik::create($validated);

        return $this->success([
            'id'                => $topic->id,
            'nama'              => $topic->nama,
            'jumlah_pertanyaan' => 0
        ], 'Topik berhasil dibuat', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $topic = Topik::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('topik', 'nama')->ignore($id)],
        ]);

        $topic->update($validated);

        return $this->success([
            'id'   => $topic->id,
            'nama' => $topic->nama
        ], 'Topik berhasil diperbarui');
    }

    public function destroy(int $id): JsonResponse
    {
        $topic  = Topik::findOrFail($id);
        $jumlah = $topic->pertanyaan()->count();
        $topic->delete();

        return $this->success(null, "Topik dan {$jumlah} pertanyaan terkait berhasil dihapus");
    }
}
