<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DB;
use DataTables;
use Exception;

class GenerateUrlController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user-list|user-create|user-edit|user-delete', only: ['index', 'show']),
            new Middleware('permission:user-create', only: ['create', 'store']),
            new Middleware('permission:user-edit', only: ['edit', 'update']),
            new Middleware('permission:user-delete', only: ['destroy', 'bulkDestroy']),
        ];
    }

    public function indexd(Request $request)
    {
        if ($request->ajax()) {
            $urls = ShortUrl::select('id', 'client_id', 'created_by', 'long_url', 'code', 'hits');

            $urlEdit = Auth::user()->can('user-edit');
            $urlDelete = Auth::user()->can('user-delete');

            return DataTables::of($urls)
                ->editColumn('id', function (ShortUrl $url) {
                    return '<div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="ids[]" value="' . $url->id . '" />
                </div>';
                })
                ->addColumn('actions', function (ShortUrl $url) use ($urlEdit, $urlDelete) {
                    $editButton = $urlEdit ? '<a href="' . route('admin.generated_urls.edit', $url->id) . '" class="btn btn-sm btn-light-primary btn-icon me-4" title="Edit country"><i class="la la-edit fs-2"></i></a>' : '';
                    $deleteButton = $urlDelete ? '<a href="javascript:;" data-url="' . route('admin.generated_urls.destroy', $url->id) . '" class="btn btn-sm btn-light-danger btn-icon" title="Delete country" data-kt-country-table-filter="delete_row"><i class="la la-trash fs-2"></i></a>' : '';

                    return $editButton . ' ' . $deleteButton;
                })
                ->rawColumns(['id', 'actions'])
                ->toJson();
        }

        return view('admin.generate_url.index');
    }


    public function index()
    {
        $authUser = auth()->user();

        $clientId = $authUser->client_id;

        // if (!$clientId) {
        //     abort(403, 'You are not assigned to any company.');
        // }

        $urls = ShortUrl::query()
            ->where('client_id', $clientId)
            ->with(['client', 'creator'])
            ->latest()
            ->paginate(10);

        return view('admin.generate_url.index', compact('urls'));
    }



    public function create()
    {
        return view('admin.generate_url.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'long_url' => ['required', 'url', 'max:2048'],
        ]);

        try {

            $authUser = auth()->user();
            $clientId = $authUser->client_id;

            if (!$clientId) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'company' => 'You are not assigned to any company.',
                    ]);
            }
            do {
                $code = Str::random(8);
            } while (ShortUrl::where('code', $code)->exists());

            $shortUrl = ShortUrl::create([
                'client_id' => $clientId,
                'created_by' => $authUser->id,
                'long_url' => $request->long_url,
                'code' => $code,
            ]);

            return response()->json(['shortUrl' => $shortUrl]);


        } catch (Exception $e) {
            $code = 500;
            if (method_exists($e, 'getStatusCode')) {
                $code = $e->getStatusCode();
            }

            return response()->json([
                'message' => $e->getMessage(),
            ], $code);
        }
    }

    public function show(ShortUrl $generated_url)
    {
        return to_route('admin.generated_urls.edit', $generated_url->id);
    }

    public function edit(ShortUrl $generated_url)
    {
        return view('admin.generate_url.edit', ['shortUrl' => $generated_url]);
    }

    public function update(Request $request, ShortUrl $shortUrl)
    {
        $request->validate([
            'long_url' => ['required', 'url', 'max:2048'],
        ]);

        try {

            $authUser = auth()->user();
            $clientId = $authUser->client_id;

            if (!$clientId) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'client' => 'You are not assigned to any company.',
                    ]);
            }

            if ($shortUrl->client_id != $clientId) {
                abort(403, 'You are not authorized to update this URL.');
            }

            $shortUrl->update([
                'long_url' => $request->long_url,
            ]);

            return response()->json([
                'shortUrl' => $shortUrl,
            ]);

        } catch (Exception $e) {

            $code = 500;

            if (method_exists($e, 'getStatusCode')) {
                $code = $e->getStatusCode();
            }

            return response()->json([
                'message' => $e->getMessage(),
            ], $code);
        }
    }

    public function resolve($code)
    {
        $shortUrl = ShortUrl::where('code', $code)->firstOrFail();

        $shortUrl->increment('hits');

        return redirect()->away($shortUrl->long_url);
    }


    public function downloadPdf(Request $request)
    {
        $filter = $request->get('filter', 'today');

        $query = ShortUrl::query()
            ->with(['creator', 'client'])
            ->latest();

        switch ($filter) {

            case 'today':

                $query->whereDate('created_at', today());

                $filterName = 'Today';

                break;

            case 'last_week':

                $query->whereBetween('created_at', [
                    now()->subWeek()->startOfWeek(),
                    now()->subWeek()->endOfWeek(),
                ]);

                $filterName = 'Last Week';

                break;

            case 'this_month':

                $query->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ]);

                $filterName = 'This Month';

                break;

            case 'last_month':

                $query->whereBetween('created_at', [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth(),
                ]);

                $filterName = 'Last Month';

                break;

            default:
                $query->whereDate('created_at', today());

                $filterName = 'Today';

                break;
        }

        $urls = $query->get();

        $pdf = Pdf::loadView('admin.generate_url.pdf', [
            'urls' => $urls,
            'filterName' => $filterName,
        ])
            ->setPaper('a4', 'landscape');

        return $pdf->download(
            'generated-short-urls-' . strtolower(str_replace(' ', '-', $filterName)) . '.pdf'
        );
    }






}
