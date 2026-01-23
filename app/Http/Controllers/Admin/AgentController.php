<?php

namespace App\Http\Controllers\Admin;

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
     * Chat avec l'agent IA pour admin
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:50000' // Limite plus élevée pour les admins
        ]);

        $user = auth()->user();
        $message = $request->message;

        $response = $this->agentService->chat($message, $user, 'admin');

        return response()->json($response);
    }
} 