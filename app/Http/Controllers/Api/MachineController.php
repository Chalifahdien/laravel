<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\BannerPromo;
use Illuminate\Support\Facades\DB;

class MachineController extends Controller
{
    public function detail($id)
    {
        $machine = Machine::with('paperSize')->find($id);

        if (!$machine || !$machine->is_active) {
            return response()->json([
                'status' => 'INACTIVE'
            ], 403);
        }

        return response()->json([
            'status' => 'SUCCESS',
            'data' => [
                'id' => $machine->id,
                'name' => $machine->name,
                'price' => $machine->price,
                'additional_print_cost' => $machine->additional_print_cost,
                'payment_required' => $machine->payment_required,
                'is_free' => !$machine->payment_required,
                'paper_size' => [
                    'id' => $machine->paperSize->id,
                    'name' => $machine->paperSize->name,
                    'width_mm' => $machine->paperSize->width_mm,
                    'height_mm' => $machine->paperSize->height_mm
                ]
            ]
        ]);
    }

    /**
     * Daftar banner promo untuk mesin: ambil dari banner_promo_machine sesuai id mesin,
     * lalu ambil data banner yang masih aktif (is_active) atau yang periodenya belum selesai
     * (start_at sudah lewat / null, end_at belum lewat / null).
     */
    public function banners($id)
    {
        $machine = Machine::find($id);
        if (!$machine || !$machine->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mesin tidak ditemukan atau tidak aktif.',
            ], 404);
        }

        $machineId = (int) $id;
        $now = now();

        // 1. Ambil banner_promo_id dari banner_promo_machine yang sesuai dengan id mesin
        $bannerIds = DB::table('banner_promo_machine')
            ->where('machine_id', $machineId)
            ->pluck('banner_promo_id')
            ->unique()
            ->values();
        if ($bannerIds->isEmpty()) {
            return response()->json([
                'status' => 'SUCCESS',
                'data' => [
                    'machine_id' => $machine->id,
                    'banners' => [],
                ],
            ]);
        }

        // 2. Ambil semua data banner promo yang masih aktif dan periodenya belum selesai
        //    - is_active = 1
        //    - start_at: null atau tanggal mulai sudah lewat (pakai tanggal saja agar seharian dihitung)
        //    - end_at: null atau tanggal selesai belum lewat (pakai tanggal saja agar seharian dihitung)
        $banners = BannerPromo::whereIn('id', $bannerIds)
            ->where(function ($q) {
                $q->where('is_active', 1)->orWhere('is_active', true);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('start_at')
                    ->orWhereDate('start_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_at')
                    ->orWhereDate('end_at', '>=', $now);
            })
            ->orderBy('sort_order')
            ->get(['id', 'title', 'image', 'link', 'sort_order']);

        return response()->json([
            'status' => 'SUCCESS',
            'data' => [
                'machine_id' => $machine->id,
                'banners' => $banners->map(fn($b) => [
                    'id' => $b->id,
                    'title' => $b->title,
                    'image' => $b->image ? asset('storage/' . $b->image) : null,
                    'link' => $b->link,
                    'sort_order' => $b->sort_order,
                ]),
            ],
        ]);
    }
}
