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
     * LIST MACHINE
     */
    public function index()
    {
        $machines = Machine::with('paperSize')->latest()->get();

        return view('admin.machines.index', compact('machines'));
    }

    /**
     * FORM CREATE
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
            ->route('machines.index')
            ->with('success', 'Machine berhasil ditambahkan');
    }

    /**
     * FORM EDIT
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
            'is_active' => 'required|boolean',
        ]);

        $machine->update([
            'name' => $request->name,
            'paper_size_id' => $request->paper_size_id,
            'price' => $request->price,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('machines.index')
            ->with('success', 'Machine berhasil diperbarui');
    }

    /**
     * DELETE MACHINE
     */
    public function destroy(Machine $machine)
    {
        $machine->delete();

        return redirect()
            ->route('machines.index')
            ->with('success', 'Machine berhasil dihapus');
    }

}
