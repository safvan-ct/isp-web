<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use App\Repository\Role\RoleInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use illuminate\Support\Str;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller implements HasMiddleware
{
    public function __construct(protected RoleInterface $roleRepository)
    {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view roles'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store role'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update role'), only: ['update']),
            new Middleware(PermissionMiddleware::using('delete role'), only: ['destroy']),
        ];
    }

    public function index()
    {
        $results     = Permission::select('id', 'name')->get();
        $permissions = $results->groupBy(function ($permission) {
            $data = explode(' ', $permission->name);
            $name = implode(' ', array_slice($data, 1));
            return Str::singular($name);
        })->map(function ($group) {
            return $group->map(fn($perm) => ['id' => $perm->id, 'name' => $perm->name])->values();
        })->sortKeys();

        return view('admin.role.index', compact('permissions'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $this->persist($request);
            return response()->json(['message' => 'Role created successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(StoreRequest $request, Role $role)
    {
        try {
            $this->persist($request, $role);
            return response()->json(['message' => 'Role updated successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Role $role)
    {
        $this->roleRepository->destroy($role);
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function dataTable(Request $request)
    {
        return DataTables::of($this->roleRepository->dataTable())->make(true);
    }

    // ───────── Helpers ─────────
    private function persist(StoreRequest $request, ?Role $role = null): Role
    {
        $data = $request->validated();
        return $this->roleRepository->updateOrCreate($data, $role);
    }
}
