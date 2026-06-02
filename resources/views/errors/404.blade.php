@extends('layouts.app')

@section('title', '404 - Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-950 text-slate-100">
  <div class="text-center p-8 bg-slate-900/60 rounded-3xl shadow-2xl border border-slate-700/50">
    <h1 class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 mb-6">404</h1>
    <p class="text-xl mb-4">Oops! The page you’re looking for doesn’t exist.</p>
    <a href="{{ route('home') ?? '/' }}"
       class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-[0_0_15px_rgba(168,85,247,0.3)] hover:shadow-[0_0_20px_rgba(168,85,247,0.5)]">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Home
    </a>
  </div>
</div>
@endsection
