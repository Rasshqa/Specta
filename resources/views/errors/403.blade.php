@extends('layouts.app')

@section('title', __('Forbidden'))

@section('content')
<div class="min-h-screen bg-slate-950 flex items-center justify-center px-4 py-12 relative overflow-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-700/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="relative z-10 max-w-lg text-center p-8 bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 rounded-3xl shadow-2xl shadow-purple-900/20">
        <h1 class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-500 mb-6">403</h1>
        <p class="text-slate-300 text-lg mb-4">{{ __('Sorry, you don\'t have permission to access this page.') }}</p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-purple-900/30 hover:shadow-purple-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Back to Home') }}
        </a>
    </div>
</div>
@endsection
