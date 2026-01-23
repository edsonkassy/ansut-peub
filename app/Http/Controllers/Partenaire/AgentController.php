<?php

namespace App\Http\Controllers\Partenaire;

use App\Http\Controllers\Controller;
use App\Services\AgentIAService;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    private AgentIAService $agentService;

    public function __construct(AgentIAService $agentService)
    {
        $this->agentService = $agentService;
    }

    /**
     * Chat avec l'agent IA pour partenaire
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $user = auth()->user();
        $message = $request->message;

        $response = $this->agentService->chat($message, $user, 'partenaire');

        return response()->json($response);
    }
} 