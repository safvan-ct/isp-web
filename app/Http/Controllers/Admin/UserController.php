<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use App\Repository\User\UserInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller implements HasMiddleware
{
    public function __construct(protected UserInterface $userRepository)
    {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view users'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store user'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update user'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active user'), only: ['status']),
            new Middleware(PermissionMiddleware::using('delete user'), only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.user.index');
    }

    public function dataTable(Request $request)
    {
        return DataTables::of($this->userRepository->dataTable(['Customer']))->make(true);
    }

    public function store(StoreRequest $request)
    {
        try {
            $this->persist($request);
            return response()->json(['message' => 'User created successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(StoreRequest $request, User $user)
    {
        try {
            $this->persist($request, $user);
            return response()->json(['message' => 'User updated successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(User $user)
    {
        $this->userRepository->destroy($user);
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function status(User $user)
    {
        try {
            $this->userRepository->toggleActive($user);
            return response()->json(['message' => 'User Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ───────── Helpers ─────────
    private function persist(StoreRequest $request, ?User $user = null): User
    {
        $data         = $request->validated();
        $data['role'] = 'Customer';

        return $this->userRepository->updateOrCreate($data, $user);
    }
}
