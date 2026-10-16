@extends('layouts.app')
@section('title','Aprendices')
@section('content')
<h1>Aprendices</h1>
<p>Listado vacío temporal (Semana 2). En la Semana 4 se conectará a BD con Eloquent.</p>
<p><a href="{{ route('aprendices.create') }}">+ Nuevo</a></p>
@endsection
