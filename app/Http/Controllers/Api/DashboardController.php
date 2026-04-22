<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use App\Models\PertanyaanFromUser;
use App\Models\Topik;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalPertanyaan = Pertanyaan::count();
        $belumDijawab    = Pertanyaan::whereNull('jawaban_admin')->orWhere('jawaban_admin', '')->count();
        $topikAktif      = Topik::count();
        $inquiryPending  = PertanyaanFromUser::where('status', false)->count();
        $inquiryResolved = PertanyaanFromUser::where('status', true)->count();

        $pertanyaanPerTopik = Topik::withCount('pertanyaan')
            ->get()
            ->map(fn ($t) => [
                'topik' => $t->nama,
                'count' => $t->pertanyaan_count,
            ]);

        $inquiryPerHari = PertanyaanFromUser::selectRaw('DATE(created_at) as hari, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('hari')
            ->orderBy('hari')
            ->get()
            ->map(fn ($row) => [
                'hari'  => $row->hari,
                'count' => (int) $row->count,
            ]);

        return $this->success([
            'total_pertanyaan'         => $totalPertanyaan,
            'pertanyaan_belum_dijawab' => $belumDijawab,
            'topik_aktif'              => $topikAktif,
            'inquiry_pending'          => $inquiryPending,
            'inquiry_resolved'         => $inquiryResolved,
            'pertanyaan_per_topik'     => $pertanyaanPerTopik,
            'inquiry_per_hari'         => $inquiryPerHari,
        ]);
    }
}
