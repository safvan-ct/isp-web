<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StoreRequest;
use App\Models\Settings;
use App\Repository\Settings\SettingsInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller implements HasMiddleware
{
    public function __construct(protected SettingsInterface $settingsRepository)
    {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view settings'), only: ['index', 'dataTable']),
            new Middleware(PermissionMiddleware::using('store settings'), only: ['store']),
            new Middleware(PermissionMiddleware::using('update settings'), only: ['update']),
            new Middleware(PermissionMiddleware::using('active settings'), only: ['status']),
            new Middleware(PermissionMiddleware::using('delete settings'), only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.settings.index');
    }

    public function store(StoreRequest $request)
    {
        try {
            $this->settingsRepository->updateOrCreate($request->validated());
            return response()->json(['message' => 'Setting created successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(StoreRequest $request, Settings $setting)
    {
        try {
            $this->settingsRepository->updateOrCreate($request->validated(), $setting);
            return response()->json(['message' => 'Settings updated successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Settings $setting)
    {
        $this->settingsRepository->destroy($setting);
        return response()->json(['message' => 'Settings deleted successfully']);
    }

    public function dataTable(Request $request)
    {
        return DataTables::of($this->settingsRepository->dataTable())->make(true);
    }
}
