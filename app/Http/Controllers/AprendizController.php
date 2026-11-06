<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateAprendizRequest;
use App\Models\Aprendiz;
use Illuminate\Http\Request;

class AprendizController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $aprendices = Aprendiz::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subquery) use ($q) {
                    $subquery
                        ->where('nombre', 'like', "%{$q}%")
                        ->orWhere('documento', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('aprendices.index', compact('aprendices', 'q'));
    }

    public function create()
    {
        $aprendiz = new Aprendiz;

        return view('aprendices.create', compact('aprendiz'));
    }

    public function store(StoreUpdateAprendizRequest $request)
    {
        Aprendiz::create($request->validated());

        return redirect()
            ->route('aprendices.index')
            ->with('ok', 'Aprendiz creado correctamente.');
    }

    public function edit(Aprendiz $aprendiz)
    {
        return view('aprendices.edit', compact('aprendiz'));
    }

    public function update(
        StoreUpdateAprendizRequest $request,
        Aprendiz $aprendiz
    ) {
        $aprendiz->update($request->validated());

        return redirect()
            ->route('aprendices.index')
            ->with('ok', 'Aprendiz actualizado correctamente.');
    }

    public function destroy(Aprendiz $aprendiz)
    {
        $aprendiz->delete();

        return redirect()
            ->route('aprendices.index')
            ->with('ok', 'Aprendiz eliminado correctamente.');
    }
}
