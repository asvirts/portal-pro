<?php

namespace App\Http\Controllers;

use App\Models\Portal;
use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        $portals = Portal::where('user_id', $user->id)
            ->withCount(['clients', 'projects', 'invoices'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalClients = Client::whereHas('portal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        $totalProjects = Project::whereHas('portal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        $totalRevenue = Invoice::whereHas('portal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'paid')->sum('total_amount');
        
        $recentProjects = Project::whereHas('portal', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['client', 'portal'])
        ->orderBy('updated_at', 'desc')
        ->limit(5)
        ->get();
        
        return view('dashboard', compact(
            'portals',
            'totalClients',
            'totalProjects', 
            'totalRevenue',
            'recentProjects'
        ));
    }
}
