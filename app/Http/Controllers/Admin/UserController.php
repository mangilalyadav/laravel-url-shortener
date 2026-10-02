<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Spatie\Permission\Models\Role;
use DataTables;
use Exception;

use Illuminate\Http\Request;

class UserController extends Controller
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

      public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select('id', 'name', 'email')->with('roles:id,name') ->withCount('shortUrls')
    ->withSum('shortUrls', 'hits');

            $userEdit = Auth::user()->can('user-edit');
            $userDelete = Auth::user()->can('user-delete');

            return DataTables::of($users)
                ->editColumn('id', function (User $user) {
                    return '<div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="ids[]" value="'.$user->id.'" />
                </div>';
                })
                 ->addColumn('role', function (User $user) {

                    return $user->roles
                        ->pluck('name')
                        ->map(function ($role) {
                            return '<span class="badge badge-light-primary me-1">'
                                . e($role)
                                . '</span>';
                        })
                        ->implode(' ');
                })
                 ->addColumn('total_urls', function (User $user) {

                return '<span class="badge badge-light-primary">
                    ' . number_format($user->short_urls_count ?? 0) . '
                </span>';
            })
             ->addColumn('total_hits', function (User $user) {

                return '<span class="badge badge-light-success">
                    ' . number_format($user->short_urls_sum_hits ?? 0) . '
                </span>';
            })

                ->addColumn('actions', function (User $user) use ($userEdit, $userDelete) {
                    // $editButton = $userEdit ? '<a href="'.route('admin.users.edit', $user->id).'" class="btn btn-sm btn-light-primary btn-icon me-4" title="Edit country"><i class="la la-edit fs-2"></i></a>' : '';
                    // $deleteButton = $userDelete ? '<a href="javascript:;" data-url="'.route('admin.users.destroy', $user->id).'" class="btn btn-sm btn-light-danger btn-icon" title="Delete country" data-kt-country-table-filter="delete_row"><i class="la la-trash fs-2"></i></a>' : '';

                    return '';
                })
                ->rawColumns([
                                'id',
                                'role',
                                'total_urls',
                                'total_hits',
                                
                            ])                ->toJson();
            }

        return view('admin.user.index');
    }   
    


    public function created()
    {
        return view('admin.user.create');
    }


    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'member'])
            ->select('id', 'name', 'display_name')
            ->get();

        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        try {

             $authUser = auth()->user();
            $clientId = null;

            $authUser = auth()->user();

            if ($authUser->hasRole('admin')) {
                $client = $authUser->client;

                if (!$client) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'client' => 'You are not assigned to any client.',
                        ]);
                }
                $clientId = $client->id;
            }


            $role = Role::findOrFail($request->role_id);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'client_id' =>  $clientId,
                'password' => Hash::make('12345678'),
            ]);

            $user->assignRole($role);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage(),
                ]);
        }
    }


    public function show(User $user)
    {
        return to_route('admin.users.edit', $user->id);
    }

  public function edit(User $user)
    {
        $roles = Role::whereIn('name', ['admin', 'member'])
            ->select('id', 'name', 'display_name')
            ->get();

        $user->load('roles');

        return view('admin.user.edit', compact('user', 'roles'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email,' . $user->id,
            ],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        try {

            DB::transaction(function () use ($request, $user) {

                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                $role = Role::findOrFail($request->role_id);

                $user->syncRoles([$role]);
            });

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Team member updated successfully.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage(),
                ]);
        }
    }

    public function destroy(Client $client)
    {
        try {
            DB::transaction(function () use ($client) {
                $client->delete();
            });

            return response()->json([
                'message' => 'User deleted successfully.',
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
                return response()->json(['message' => 'No User selected for deletion.'], 400);
            }

            DB::transaction(function () use ($ids) {
                Client::whereIn('id', $ids)->get()->each->delete();
            });

            return response()->json([
                'message' => 'User(s) deleted successfully.',
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


