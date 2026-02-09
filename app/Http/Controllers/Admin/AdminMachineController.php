<?php

namespace App\Http\Controllers\Admin;

use App\Models\Machine;
use App\Models\PaperSize;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminMachineController extends Controller
{
    /**
     * LIST MACHINES
     */
    public function index()
    {
        $machines = Machine::with('paperSize')->latest()->get();

        return view('admin.machines.index', compact('machines'));
    }

    /**
     * CREATE FORM
     */
    public function create()
    {
        $paperSizes = PaperSize::all();

        return view('admin.machines.create', compact('paperSizes'));
    }

    /**
     * STORE MACHINE
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'paper_size_id' => 'required|exists:paper_sizes,id',
            'price' => 'required|integer|min:1000',
            'is_active' => 'required|boolean',
        ]);

        Machine::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'paper_size_id' => $request->paper_size_id,
            'price' => $request->price,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Machine successfully added');
    }

    /**
     * EDIT FORM
     */
    public function edit(Machine $machine)
    {
        $paperSizes = PaperSize::all();

        return view('admin.machines.edit', compact('machine', 'paperSizes'));
    }

    /**
     * UPDATE MACHINE
     */
    public function update(Request $request, Machine $machine)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'paper_size_id' => 'required|exists:paper_sizes,id',
            'price' => 'required|integer|min:1000',
            'additional_print_cost' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
            'payment_required' => 'boolean',
        ]);

        $machine->update([
            'name' => $request->name,
            'paper_size_id' => $request->paper_size_id,
            'price' => $request->price,
            'additional_print_cost' => $request->additional_print_cost ?? 0,
            'is_active' => $request->is_active,
            'payment_required' => $request->payment_required ?? true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Machine successfully updated');
    }

    /**
     * DELETE MACHINE
     */
    public function destroy(Machine $machine)
    {
        $machine->delete();

        return redirect()
            ->route('machines.index')
            ->with('success', 'Machine successfully deleted');
    }
}
