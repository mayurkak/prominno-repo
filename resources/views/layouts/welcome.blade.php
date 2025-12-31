@extends('layouts.app')
@section('content')
    <h2>Welcome,
        {{ auth()->user()->name }}
         👋</h2>
    <p>This is your dashboard.</p>
@endsection
