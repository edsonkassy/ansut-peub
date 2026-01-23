<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect to role-specific dashboard
        switch ($user->role) {
            case 'bachelier':
                return redirect()->route('bachelier.dashboard');
            
            case 'partenaire':
                return redirect()->route('partenaire.dashboard');
            
            case 'admin':
                return redirect()->route('admin.dashboard');
            
            default:
                return redirect()->route('landing')->with('error', 'Rôle non reconnu.');
        }
    }
} 