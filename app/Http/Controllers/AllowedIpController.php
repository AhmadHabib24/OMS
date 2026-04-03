<?php

namespace App\Http\Controllers;

use App\Models\AllowedIp;
use App\Models\User;
use Illuminate\Http\Request;

class AllowedIpController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $query = AllowedIp::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('label', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $allowedIps = $query->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('allowed-ips.index', compact('allowedIps', 'users'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $users = User::orderBy('name')->get();

        return view('allowed-ips.create', compact('users'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'ip_address' => ['required', 'ip'],
            'label' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        AllowedIp::create([
            'user_id' => $validated['user_id'] ?? null,
            'ip_address' => $validated['ip_address'],
            'label' => $validated['label'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('allowed-ips.index')
            ->with('success', 'Allowed IP created successfully.');
    }

    public function edit(AllowedIp $allowedIp)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $users = User::orderBy('name')->get();

        return view('allowed-ips.edit', compact('allowedIp', 'users'));
    }

    public function update(Request $request, AllowedIp $allowedIp)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'ip_address' => ['required', 'ip'],
            'label' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $allowedIp->update([
            'user_id' => $validated['user_id'] ?? null,
            'ip_address' => $validated['ip_address'],
            'label' => $validated['label'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('allowed-ips.index')
            ->with('success', 'Allowed IP updated successfully.');
    }

    public function destroy(AllowedIp $allowedIp)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $allowedIp->delete();

        return redirect()
            ->route('allowed-ips.index')
            ->with('success', 'Allowed IP deleted successfully.');
    }
}