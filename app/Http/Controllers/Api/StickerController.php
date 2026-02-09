<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sticker;
use Illuminate\Http\Request;

class StickerController extends Controller
{
    /**
     * Get list of active stickers
     */
    public function index()
    {
        $stickers = Sticker::where('is_active', true)
            ->oldest() // Assuming ordered by creation time
            ->get()
            ->map(function ($sticker) {
                return [
                    'id' => $sticker->id,
                    'name' => $sticker->name,
                    'image_url' => asset('storage/' . $sticker->image_path),
                    'category' => $sticker->category,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $stickers
        ]);
    }
}
