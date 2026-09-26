<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use DataTables;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:permission-list|permission-create|permission-edit|permission-delete', only: ['index', 'show']),
            new Middleware('permission:permission-create', only: ['create', 'store']),
            new Middleware('permission:permission-edit', only: ['edit', 'update']),
            new Middleware('permission:permission-delete', only: ['destroy', 'bulkDestroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::with('group');

            $modelEdit = 'edit';
            $modelDelete = 'delete';

            return DataTables::of($permissions)
                ->editColumn('id', function ($permission) {
                    return '<div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="ids[]" value="'.$permission->id.'" />
                </div>';
                })
                ->addColumn('group.name', function ($permission) {
                    if ($permission->group()->exists()) {
                        return $permission->group->name.'<span class="data_group_id d-none">'.$permission->group->id.'</span>';
                    } else {
                        return 'N/A'.'<span class="data_group_id d-none"></span>';
                    }
                })
                ->addColumn('actions', function ($permission) use ($modelEdit, $modelDelete) {
                    $html = '';

                    if ($modelEdit) {
                        $html .= '<a href="javascript:;" data-url="'.route('admin.permissions.update', $permission->id).'" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_permission" class="btn btn-sm btn-light-primary btn-icon me-4" title="Edit permission"><i class="la la-edit fs-2"></i></a>';
                    }
                    if ($modelDelete) {
                        $html .= '<a href="javascript:;" data-url="'.route('admin.permissions.destroy', $permission->id).'" class="btn btn-sm btn-light-danger btn-icon" title="Delete permission" data-kt-permissions-table-filter="delete_row"><i class="la la-trash fs-2"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['id', 'group.name', 'actions'])
                ->toJson();
        }

        return to_route('admin.rolePermission');

    }

    public function create()
    {
        return to_route('admin.rolePermission');

    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'group_id' => 'required|exists:App\Models\PermissionGroup,id',
        ]);

        try {
            $permission = Permission::create([
                'name' => $request->input('name'),
                'guard_name' => 'web',
                'display_name' => $request->input('display_name'),
                'group_id' => $request->input('group_id'),
            ]);

            return response()->json($permission);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return to_route('admin.rolePermission');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return to_route('admin.rolePermission');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($id)],
            'display_name' => 'required|string|max:255',
            'group_id' => 'required|exists:App\Models\PermissionGroup,id',
        ]);

        try {
            $permission->name = $request->input('name');
            $permission->guard_name = 'web';
            $permission->display_name = $request->input('display_name');
            $permission->group_id = $request->input('group_id');
            $permission->save();

            return response()->json($permission);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);

            DB::transaction(function () use ($permission) {
                $permission->delete();
            });

            return response()->json([
                'message' => 'Permission deleted successfully.',
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
                    Permission::whereIn('id', $ids)->get()->each->delete();
                });

                return response()->json([
                    'message' => 'Permission(s) deleted successfully.',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Permission(s) selected for deletion.',
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
}
