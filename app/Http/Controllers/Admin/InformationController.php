<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        $informations = Information::query()->latest()->paginate(10);
        return view('admin.informations.index', compact('informations'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'category'  => ['required', 'string', 'max:50'],
            'content'   => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->is_active : true;

        Information::query()->create($validated);

        return redirect()->route('admin.informations.index')
            ->with('success', 'Informasi berhasil ditambahkan!');
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Information $information)
    {
        $validated = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'category'  => ['required', 'string', 'max:50'],
            'content'   => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $information->update($validated);

        return redirect()->route('admin.informations.index')
            ->with('success', 'Informasi berhasil diperbarui!');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Information $information)
    {
        $information->delete();

        return redirect()->route('admin.informations.index')
            ->with('success', 'Informasi berhasil dihapus!');
    }
}
