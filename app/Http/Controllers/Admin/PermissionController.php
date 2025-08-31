<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view permissions'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store permission'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update permission'), only: ['update']),
            new Middleware(PermissionMiddleware::using('delete permission'), only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.permission.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);

        try {
            $permission = Permission::create(['name' => Str::lower($request->name)]);
            Role::whereIn('name', ['Owner', 'Developer'])->each(fn($role) => $role->givePermissionTo($permission->name));

            return response()->json(['message' => 'Permission created successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);

        try {
            $permission->name = Str::lower($request->name);
            $permission->save();

            return response()->json(['message' => 'Permission updated successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permission deleted successfully']);
    }

    public function dataTable(Request $request)
    {
        return DataTables::of(Permission::query())->make(true);
    }
}
