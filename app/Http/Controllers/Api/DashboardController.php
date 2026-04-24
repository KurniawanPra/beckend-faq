<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use App\Models\PertanyaanFromUser;
use App\Models\Topik;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request): JsonResponse
    {
        $month = $request->query('month');
        
        $pQuery = Pertanyaan::query();
        $tQuery = Topik::query();
        $iQuery = PertanyaanFromUser::query();

        if ($month) {
            $parts = explode('-', $month);
            if (count($parts) === 2) {
                $pQuery->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
                $tQuery->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
                $iQuery->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
            }
        } else {
            // Default: 7 hari terakhir
            $pQuery->where('created_at', '>=', now()->subDays(7));
            $tQuery->where('created_at', '>=', now()->subDays(7));
            $iQuery->where('created_at', '>=', now()->subDays(7));
        }

        $totalPertanyaan = $pQuery->count();
        $topikAktif      = $tQuery->count();
        $inquiryPending  = (clone $iQuery)->where('status', false)->count();
        $inquiryResolved = (clone $iQuery)->where('status', true)->count();

        // Pertanyaan per topik (juga terfilter date)
        $pertanyaanPerTopik = Topik::withCount(['pertanyaan' => function($q) use ($month) {
                if ($month) {
                    $parts = explode('-', $month);
                    if (count($parts) === 2) {
                        $q->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
                    }
                } else {
                    $q->where('created_at', '>=', now()->subDays(7));
                }
            }])
            ->get()
            ->map(fn ($t) => [
                'topik' => $t->nama,
                'count' => $t->pertanyaan_count,
            ]);

        $inquiryPerHari = (clone $iQuery)
            ->selectRaw('DATE(created_at) as hari, COUNT(*) as count')
            ->groupBy('hari')
            ->orderBy('hari')
            ->get()
            ->map(fn ($row) => [
                'hari'  => $row->hari,
                'count' => (int) $row->count,
            ]);

        return $this->success([
            'total_pertanyaan'     => $totalPertanyaan,
            'topik_aktif'          => $topikAktif,
            'inquiry_pending'      => $inquiryPending,
            'inquiry_resolved'     => $inquiryResolved,
            'pertanyaan_per_topik' => $pertanyaanPerTopik,
            'inquiry_per_hari'     => $inquiryPerHari,
        ]);
    }
}
