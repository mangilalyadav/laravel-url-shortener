<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ShortUrl;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function dashboard()
    {
        $authUser = auth()->user();

        // for Superadmin Dashboard
        
        if ($authUser->hasRole('superadmin')) {

            $clients = Client::select('id', 'name', 'email')
                ->latest()
                ->paginate(3);

            $urls = ShortUrl::query()
                ->with(['creator', 'client'])
                ->latest()
                ->paginate(4, ['*'], 'urls_page');

            return view('admin.dashboard', compact(
                'clients',
                'urls'
            ));

        }

       
       // for Admin Dashboard
        
        if ($authUser->hasRole('admin')) {

            $clientId = $authUser->client_id;

            $urls = collect();
            $teamMembers = collect();

            if ($clientId) {

                $urls = ShortUrl::query()
                    ->where('client_id', $clientId)
                    ->with(['creator', 'client'])
                    ->latest()
                    ->paginate(2, ['*'], 'urls_page');

                $teamMembers = User::query()
                    ->where('client_id', $clientId)
                    ->with('roles')
                    ->withCount([
                        'shortUrls as total_generated_urls'
                    ])
                    ->withSum([
                        'shortUrls as total_url_hits'
                    ], 'hits')
                    ->latest()
                    ->paginate(3, ['*'], 'members_page');
            }

            return view('admin.dashboard', compact(
                'urls',
                'teamMembers'
            ));
        }

        
        //for Member Dashboard
        
        if ($authUser->hasRole('member')) {

            $clientId = $authUser->client_id;

            $urls = collect();

            if ($clientId) {
                $urls = ShortUrl::query()
                    ->where('client_id', $clientId)
                    ->with(['creator', 'client'])
                    ->latest()
                    ->paginate(3, ['*'], 'urls_page');
            }

            return view('admin.dashboard', compact('urls'));
        }

        return view('admin.dashboard');
    }
}
