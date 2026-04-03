<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadNote;
use Illuminate\Http\Request;

class LeadNoteController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        abort_unless(auth()->user()->can('add lead notes'), 403);
        $request->validate([
            'note' => 'required|string',
        ]);

        LeadNote::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'note' => $request->note,
        ]);

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead note added successfully.');
    }
}