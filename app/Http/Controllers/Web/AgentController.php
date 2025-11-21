<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::with('user')->get();
        $users= User::get();
        return view('agents.index', compact('agents', 'users'));
    }

    public function create()
    {
        $users = User::role('agent')->get();
        return view('agents.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'agency_name' => 'required|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'docs.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $docs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $f) {
                $path = $f->store('agents', 'public');
                $docs[] = Storage::url($path);
            }
        }

        Agent::create([
            'user_id' => $data['user_id'],
            'agency_name' => $data['agency_name'],
            'commission_rate' => $data['commission_rate'] ?? 0,
            'docs' => $docs ? json_encode($docs) : null
        ]);

        return redirect()->route('agents.index')->with('success', 'Agent registered.');
    }

    public function edit(Agent $agent)
    {
        return view('agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $data = $request->validate([
            'agency_name' => 'required|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $agent->update($data);
        return redirect()->route('agents.index')->with('success', 'Agent updated.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('agents.index')->with('success', 'Agent removed.');
    }

    public function verify(Agent $agent)
    {
        $agent->verified_at = now();
        $agent->save();
        return redirect()->route('agents.index')->with('success', 'Agent verified.');
    }
}
