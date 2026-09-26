<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use DataTables;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array  
    {
        return [
            new Middleware('permission:role-list|role-create|role-edit|role-delete', only: ['index', 'show']),
            new Middleware('permission:role-create', only: ['create', 'store']),
            new Middleware('permission:role-edit', only: ['edit', 'update']),
            new Middleware('permission:role-delete', only: ['destroy', 'bulkDestroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::select('id', 'name', 'display_name');

            $modelEdit = 'edit';
            $modelDelete = 'delete';

            return DataTables::of($roles)
                ->editColumn('id', function ($role) {
                    return '<div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="ids[]" value="'.$role->id.'" />
                </div>';
                })
                ->addColumn('actions', function ($role) use ($modelEdit, $modelDelete) {
                    $html = '';

                    if ($modelEdit) {
                        $html .= '<a href="'.route('admin.roles.edit', $role->id).'" class="btn btn-sm btn-light-primary btn-icon me-4" title="Edit role"><i class="la la-edit fs-2"></i></a>';
                    }
                    if ($modelDelete) {
                        $html .= '<a href="javascript:;" data-url="'.route('admin.roles.destroy', $role->id).'" class="btn btn-sm btn-light-danger btn-icon" title="Delete role" data-kt-roles-table-filter="delete_row"><i class="la la-trash fs-2"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['id', 'actions'])
                ->toJson();
        }

        // return view('admin.rolePermission.index');
        return redirect(route('admin.rolePermission'));
    }

    public function create()
    {
        $permissionGroups = PermissionGroup::get();

        return view('admin.rolePermission.role.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permission' => 'required',
        ]);

        try {
            $role = Role::create([
                'name' => $request->input('name'),
                'display_name' => $request->input('display_name'),
            ]);

            $permissions = Permission::find($request->input('permission'));
            if ($permissions->count() != count($request->input('permission'))) {
                return response()->json([
                    'message' => 'Some permissions are invalid.',
                ], 400);
            }

            $role->syncPermissions($permissions);

            return response()->json($role);
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

    public function show($id)
    {
        return to_route('admin.roles.index');
        $role = Role::findOrFail($id);
        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_has_permissions.role_id', $id)
            ->get();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissionGroups = PermissionGroup::get();
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('admin.rolePermission.role.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($id)],
            'permission' => 'required',
        ]);

        try {
            $role->name = $request->input('name');
            $role->display_name = $request->input('display_name');
            $role->save();

            // $role->syncPermissions($request->input('permission'));
            $permissions = Permission::find($request->input('permission'));
            if ($permissions->count() != count($request->input('permission'))) {
                return response()->json([
                    'message' => 'Some permissions are invalid.',
                ], 400);
            }
            // Sync permissions
            $role->syncPermissions($permissions);

            return response()->json($role);
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

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            DB::transaction(function () use ($role) {
                $role->delete();
            });

            return response()->json([
                'message' => 'Role deleted successfully.',
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
            if (! empty($ids)) {
                DB::transaction(function () use ($ids) {
                    Role::whereIn('id', $ids)->get()->each->delete();
                });

                return response()->json([
                    'message' => 'Role(s) deleted successfully.',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Role(s) selected for deletion.',
                ], 500);
            }
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

    public function rolePermission()
    {
        $permissionGroups = PermissionGroup::select('id', 'name')->get();

        return view('admin.rolePermission.index', compact('permissionGroups'));
    }

    
}
