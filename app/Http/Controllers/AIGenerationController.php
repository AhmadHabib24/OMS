<?php

namespace App\Http\Controllers;

use App\Models\AIGeneration;

class AIGenerationController extends Controller
{
    public function index()
    {
        
        $generations = AIGeneration::with(['user', 'lead', 'campaign'])
            ->latest()
            ->paginate(20);
            // dd($generations);

        return view('ai-generations.index', compact('generations'));
    }
}