<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Pertanyaan::with('topik:id,nama');

        if ($request->filled('topik_id')) {
            $query->where('topik_id', $request->integer('topik_id'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('pertanyaan', 'LIKE', "%{$search}%")
                  ->orWhere('jawaban_admin', 'LIKE', "%{$search}%");
            });
        }

        $sort      = in_array($request->query('sort'), ['created_at']) ? $request->query('sort') : 'created_at';
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sort, $direction);

        $paginator = $query->paginate(min($request->integer('per_page', 15), 100));

        $data = collect($paginator->items())->map(fn(Pertanyaan $p) => [
            'id'            => $p->id,
            'topik_id'      => $p->topik_id,
            'topik_nama'    => $p->topik?->nama,
            'user_id'       => $p->user_id,
            'pertanyaan'    => $p->pertanyaan,
            'jawaban_admin' => $p->jawaban_admin,
            'created_at'    => $p->created_at,
            'updated_at'    => $p->updated_at,
        ]);

        return $this->success($data, '', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topik_id'      => 'required|integer|exists:topik,id',
            'user_id'       => 'required|integer|exists:users,id',
            'pertanyaan'    => 'required|string|min:5',
            'jawaban_admin' => 'nullable|string',
        ]);

        $question = Pertanyaan::create($validated);

        return $this->success($question, 'Pertanyaan berhasil ditambahkan', 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $question = Pertanyaan::findOrFail($id);

        $validated = $request->validate([
            'topik_id'      => 'sometimes|integer|exists:topik,id',
            'pertanyaan'    => 'sometimes|string|min:5',
            'jawaban_admin' => 'nullable|string',
        ]);

        $question->update($validated);

        return $this->success($question->fresh(), 'Pertanyaan berhasil diperbarui');
    }

    public function destroy(int $id): JsonResponse
    {
        $question = Pertanyaan::findOrFail($id);
        $question->delete();

        return $this->success(null, 'Pertanyaan berhasil dihapus');
    }
}
