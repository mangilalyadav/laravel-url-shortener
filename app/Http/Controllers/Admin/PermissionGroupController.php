<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use DataTables;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class PermissionGroupController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:permission-group-list|permission-group-create|permission-group-edit|permission-group-delete', only: ['index', 'show']),
            new Middleware('permission:permission-create', only: ['create', 'store']),
            new Middleware('permission:permission-edit', only: ['edit', 'update']),
            new Middleware('permission:permission-delete', only: ['destroy', 'bulkDestroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissionGroups = PermissionGroup::select('id', 'name');

            $modelEdit = 'edit';
            $modelDelete = 'delete';

            return DataTables::of($permissionGroups)
                ->editColumn('id', function ($permissionGroup) {
                    return '<div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" name="ids[]" value="'.$permissionGroup->id.'" />
                </div>';
                })
                ->addColumn('actions', function ($permissionGroup) use ($modelEdit, $modelDelete) {
                    $html = '';

                    if ($modelEdit) {
                        $html .= '<a href="javascript:;" data-url="'.route('admin.permission_groups.update', $permissionGroup->id).'" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_group" class="btn btn-sm btn-light-primary btn-icon me-4" title="Edit permission group"><i class="la la-edit fs-2"></i></a>';
                    }
                    if ($modelDelete) {
                        $html .= '<a href="javascript:;" data-url="'.route('admin.permission_groups.destroy', $permissionGroup->id).'" class="btn btn-sm btn-light-danger btn-icon" title="Delete permission group" data-kt-groups-table-filter="delete_row"><i class="la la-trash fs-2"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['id', 'actions'])
                ->toJson();
        }

        return to_route('admin.rolePermission');

    }

    public function create()
    {
        return to_route('admin.permission-groups');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:permission_groups,name',
        ]);

        try {
            $PermissionGroup = PermissionGroup::create([
                'name' => $request->input('name'),
            ]);

            return response()->json($PermissionGroup);
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
        return to_route('admin.rolePermission');

    }

    public function edit($id)
    {
        return to_route('admin.rolePermission');
        // $PermissionGroup = PermissionGroup::findOrFail($id);
        // return view('system.permissionGroup.edit',compact('PermissionGroup'));
    }

    public function update(Request $request, $id)
    {
        $PermissionGroup = PermissionGroup::findOrFail($id);

        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permission_groups')->ignore($id)],
        ]);

        try {
            $PermissionGroup->name = $request->input('name');
            $PermissionGroup->save();

            return response()->json($PermissionGroup);
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
            $PermissionGroup = PermissionGroup::findOrFail($id);

            DB::transaction(function () use ($PermissionGroup) {
                $PermissionGroup->delete();
            });

            return response()->json([
                'message' => 'Permission Group deleted successfully.',
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
                    PermissionGroup::whereIn('id', $ids)->get()->each->delete();
                });

                return response()->json([
                    'message' => 'Permission Group(s) deleted successfully.',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Permission Group(s) selected for deletion.',
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
