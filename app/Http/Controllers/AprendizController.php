<?php

namespace App\Http\Controllers;

use App\Models\Aprendiz;
use Illuminate\Http\Request;

class AprendizController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $aprendices = Aprendiz::query()
            ->when($q !== '', fn ($query) => $query->where('nombre', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('aprendices.index', compact('aprendices', 'q'));
    }

    public function create()
    {
        return view('aprendices.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'documento' => ['required', 'string', 'max:40', 'unique:aprendices,documento'],
            'correo' => ['required', 'email', 'max:120', 'unique:aprendices,correo'],
            'ficha_id' => ['nullable', 'integer'],
        ]);

        Aprendiz::create($data);

        return redirect()->route('aprendices.index')->with('ok', 'Aprendiz creado');
    }

    public function show(Aprendiz $aprendiz)
    {
        abort(404);
    }

    public function edit(Aprendiz $aprendiz)
    {
        abort(404);
    }

    public function update(Request $r, Aprendiz $aprendiz)
    {
        abort(404);
    }

    public function destroy(Aprendiz $aprendiz)
    {
        abort(404);
    }
}
