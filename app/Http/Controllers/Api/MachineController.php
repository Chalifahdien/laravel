<?php

namespace App\Http\Controllers\Api;

use App\Models\Machine;
use Illuminate\Http\Request;

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
                'paper_size' => [
                    'id' => $machine->paperSize->id,
                    'name' => $machine->paperSize->name,
                    'width_mm' => $machine->paperSize->width_mm,
                    'height_mm' => $machine->paperSize->height_mm
                ]
            ]
        ]);
    }
}
