<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\ClientRequest;
use App\Models\Client;

use App\Models\ShortUrl;
use App\Models\User;
use Auth;
use DB;
use DataTables;
use Exception;

use Illuminate\Http\Request;

class ClientController extends Controller implements HasMiddleware
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

    public function index()
    {
        $clients = Client::query()->latest()->paginate(4);

        return view('admin.client.index', compact('clients'));
    }

    public function indexd(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::select('id', 'name', 'email');

            $clientEdit = Auth::user()->can('user-edit');
            $clientDelete = Auth::user()->can('user-delete');

            return DataTables::of($clients)
                ->editColumn('id', function (Client $client) {
                    return '<div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="ids[]" value="'.$client->id.'" />
                </div>';
                })
                ->addColumn('actions', function (Client $client) use ($clientEdit, $clientDelete) {
                    $editButton = $clientEdit ? '<a href="'.route('admin.clients.edit', $client->id).'" class="btn btn-sm btn-light-primary btn-icon me-4" title="Edit country"><i class="la la-edit fs-2"></i></a>' : '';
                    $deleteButton = $clientDelete ? '<a href="javascript:;" data-url="'.route('admin.clients.destroy', $client->id).'" class="btn btn-sm btn-light-danger btn-icon" title="Delete country" data-kt-country-table-filter="delete_row"><i class="la la-trash fs-2"></i></a>' : '';

                    return $editButton.' '.$deleteButton;
                })
                ->rawColumns(['id', 'actions'])
                ->toJson();
        }

        return view('admin.client.index');
    }

    public function create()
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })
        ->select('id', 'name', 'email')
        ->get();

        return view('admin.client.create', compact('admins'));
    }

    public function store(ClientRequest $request)
    {
        //  dd($request)->all();
        try {
            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return response()->json(['country' => $client]);

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

    public function show(Client $client)
    {
        return to_route('admin.clients.edit', $client->id);
    }

    public function edit(Client $client)
    {
        return view('admin.client.edit', ['client' => $client]);
    }

    public function update(Client $client, ClientRequest $request)
    {
        try {
            $client->update([
                'name'       => $request->name,
                'email'      => $request->email,
                'updated_at' => now(),
            ]);

            return response()->json(['client' => $client]);

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

    public function destroy(Client $client)
    {
        try {
            DB::transaction(function () use ($client) {
                $client->delete();
            });

            return response()->json([
                'message' => 'Client deleted successfully.',
            ], 200);
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

    public function bulkDestroy(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if (! is_array($ids) || empty($ids)) {
                return response()->json(['message' => 'No client selected for deletion.'], 400);
            }

            DB::transaction(function () use ($ids) {
                Client::whereIn('id', $ids)->get()->each->delete();
            });

            return response()->json([
                'message' => 'Client(s) deleted successfully.',
            ], 200);
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
}
