<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topik;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('q', '');

        $topics = Topik::with(['pertanyaan' => function ($query) use ($search) {
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pertanyaan', 'LIKE', "%{$search}%")
                      ->orWhere('jawaban_admin', 'LIKE', "%{$search}%");
                });
            }
            $query->whereNotNull('jawaban_admin')
                  ->where('jawaban_admin', '!=', '')
                  ->orderBy('id');
        }])
        ->orderBy('nama')
        ->get();

        $data = $topics
            ->filter(fn($t) => $t->pertanyaan->isNotEmpty())
            ->values()
            ->map(fn($t) => [
                'id'    => $t->id,
                'topic' => $t->nama,
                'items' => $t->pertanyaan->map(fn($p) => [
                    'q' => $p->pertanyaan,
                    'a' => $p->jawaban_admin,
                ])->values(),
            ]);

        return $this->success($data);
    }
}
