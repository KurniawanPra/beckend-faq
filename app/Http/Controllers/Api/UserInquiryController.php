<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PertanyaanFromUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserInquiryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PertanyaanFromUser::query();

        $status = $request->query('status');
        $isPendingFilter = $request->has('status') && filter_var($status, FILTER_VALIDATE_BOOLEAN) === false;

        if (!$isPendingFilter) {
            if ($request->filled('month')) {
                $parts = explode('-', $request->query('month'));
                if (count($parts) === 2) {
                    $query->whereYear('created_at', $parts[0])
                          ->whereMonth('created_at', $parts[1]);
                }
            } else {
                $query->where('created_at', '>=', now()->subDays(7));
            }
        }

        if ($request->has('status')) {
            $query->where('status', filter_var($status, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('jawaban_melalui')) {
            $query->where('jawaban_melalui', $request->query('jawaban_melalui'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_user', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhere('bagian', 'LIKE', "%{$search}%")
                  ->orWhere('pertanyaan', 'LIKE', "%{$search}%");
            });
        }

        $sort      = in_array($request->query('sort'), ['created_at', 'nama_user', 'status']) ? $request->query('sort') : 'created_at';
        $direction = $request->query('direction', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        $paginator = $query->paginate(min($request->integer('per_page', 15), 100));

        return $this->success($paginator->items(), '', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_user'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'jabatan'        => 'required|string|max:255',
            'bagian'         => 'nullable|string|max:255',
            'pertanyaan'     => 'required|string|min:10',
            'jawaban_melalui'=> 'required|in:email,whatsapp,telepon',
            'nomor_kontak'   => 'nullable|string|max:50',
        ]);

        $inquiry = PertanyaanFromUser::create($validated);

        return $this->success($inquiry, 'Pertanyaan berhasil dikirim.', 201);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $inquiry = PertanyaanFromUser::findOrFail($id);

        $validated = $request->validate([
            'status'        => 'required|boolean',
            'catatan_admin' => 'nullable|string',
        ]);

        $inquiry->update($validated);

        return $this->success([
            'id'     => $inquiry->id,
            'status' => $inquiry->status,
        ], 'Status berhasil diperbarui.');
    }

    public function destroy(int $id): JsonResponse
    {
        $inquiry = PertanyaanFromUser::findOrFail($id);
        $inquiry->delete();

        return $this->success(null, 'Inquiry berhasil dihapus.');
    }
}
