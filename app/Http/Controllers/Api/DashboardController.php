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
            $pQuery->where('created_at', '>=', now()->subDays(30));
            $tQuery->where('created_at', '>=', now()->subDays(30));
            $iQuery->where('created_at', '>=', now()->subDays(30));
        }

        $totalPertanyaan = $pQuery->count();
        $topikAktif      = $tQuery->count();
        $inquiryPending  = (clone $iQuery)->where('status', false)->count();
        $inquiryResolved = (clone $iQuery)->where('status', true)->count();

        $pertanyaanPerTopik = Topik::withCount(['pertanyaan' => function($q) use ($month) {
                if ($month) {
                    $parts = explode('-', $month);
                    if (count($parts) === 2) {
                        $q->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
                    }
                } else {
                    $q->where('created_at', '>=', now()->subDays(30));
                }
            }])
            ->get()
            ->map(fn ($t) => [
                'topik' => $t->nama,
                'count' => $t->pertanyaan_count,
            ]);

        $inquiryPerMinggu = (clone $iQuery)
            ->selectRaw('YEARWEEK(created_at, 1) as minggu_key, COUNT(*) as count')
            ->groupBy('minggu_key')
            ->orderBy('minggu_key')
            ->get()
            ->map(function ($row) {
                $year = substr($row->minggu_key, 0, 4);
                $week = substr($row->minggu_key, 4);
                
                $dto = new \DateTime();
                $dto->setISODate($year, $week);
                $start = $dto->format('d M');
                $dto->modify('+6 days');
                $end = $dto->format('d M');
                
                return [
                    'minggu' => "$start - $end",
                    'count'  => (int) $row->count,
                ];
            });

        return $this->success([
            'total_pertanyaan'      => $totalPertanyaan,
            'topik_aktif'           => $topikAktif,
            'inquiry_pending'       => $inquiryPending,
            'inquiry_resolved'      => $inquiryResolved,
            'pertanyaan_per_topik'  => $pertanyaanPerTopik,
            'inquiry_per_minggu'    => $inquiryPerMinggu,
        ]);
    }
}
