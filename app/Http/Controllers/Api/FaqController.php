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
            $query->whereNotNull('jawaban_admin')
                  ->where('jawaban_admin', '!=', '');
            
            // Jika pencarian tidak kosong, kita filter pertanyaan
            // TAPI jika nama topiknya sudah cocok, kita biarkan semua pertanyaan di dalamnya muncul
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pertanyaan', 'LIKE', "%{$search}%")
                      ->orWhere('jawaban_admin', 'LIKE', "%{$search}%")
                      ->orWhereHas('topik', function($tQ) use ($search) {
                          $tQ->where('nama', 'LIKE', "%{$search}%");
                      });
                });
            }
            
            $query->orderBy('id');
        }])
        ->withCount(['pertanyaan' => function ($query) {
            $query->whereNotNull('jawaban_admin')->where('jawaban_admin', '!=', '');
        }])
        // Urutkan berdasarkan jumlah pertanyaan terbanyak (Topik Populer)
        ->orderByDesc('pertanyaan_count')
        ->orderBy('nama')
        ->get();

        $data = $topics
            ->filter(fn($t) => $t->pertanyaan->isNotEmpty())
            ->values()
            ->map(fn($t) => [
                'id'    => $t->id,
                'topic' => $t->nama,
                'count' => $t->pertanyaan_count,
                'items' => $t->pertanyaan->map(fn($p) => [
                    'id' => $p->id,
                    'q'  => $p->pertanyaan,
                    'a'  => $p->jawaban_admin,
                ])->values(),
            ]);

        return $this->success($data);
    }
}
