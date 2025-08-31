<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreRequest;
use App\Models\User;
use App\Repository\Role\RoleInterface;
use App\Repository\User\UserInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller implements HasMiddleware
{
    public function __construct(
        protected UserInterface $userRepository,
        protected RoleInterface $roleRepository
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view staffs'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store staff'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update staff'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active staff'), only: ['status']),
            new Middleware(PermissionMiddleware::using('delete staff'), only: ['destroy']),
        ];
    }

    public function index()
    {
        $roles = $this->roleRepository->getStaffRoles();
        return view('admin.staff.index', compact('roles'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $this->persist($request);
            return response()->json(['message' => 'Staff created successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(StoreRequest $request, User $staff)
    {
        try {
            $this->persist($request, $staff);
            return response()->json(['message' => 'Staff updated successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(User $staff)
    {
        $this->userRepository->destroy($staff);
        return response()->json(['message' => 'Staff deleted successfully']);
    }

    public function dataTable(Request $request)
    {
        $roles = $this->roleRepository->getStaffRoles(true);
        return DataTables::of($this->userRepository->dataTable($roles, ['roles']))->make(true);
    }

    public function status(User $staff)
    {
        try {
            $this->userRepository->toggleActive($staff);
            return response()->json(['message' => 'Staff Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ───────── Helpers ─────────
    private function persist(StoreRequest $request, ?User $staff = null): User
    {
        $data = $request->validated();
        return $this->userRepository->updateOrCreate($data, $staff);
    }
}
