<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AprendizController extends Controller
{
    public function index()
    {
        // Semana 2: sin BD; solo devolvemos la vista
        return view('aprendices.index');
    }

    public function create()
    {
        return view('aprendices.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'documento' => ['required', 'string', 'max:40'],
            'correo' => ['required', 'email', 'max:120'],
        ]);

        // NO guardamos en BD (Semana 4 con Eloquent)
        return redirect()->route('aprendices.index')
            ->with('ok', 'Aprendiz recibido (simulado)');
    }

    public function show($id) { abort(404); }
    public function edit($id) { abort(404); }
    public function update(Request $request, $id) { abort(404); }
    public function destroy($id) { abort(404); }
}