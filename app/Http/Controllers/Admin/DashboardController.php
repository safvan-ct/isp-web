<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HadithBook;
use App\Models\HadithChapter;
use App\Models\HadithVerse;
use App\Models\QuranChapter;
use App\Models\QuranVerse;
use App\Models\User;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DashboardController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view activity-logs'), only: ['activityLog']),
        ];
    }

    public function index()
    {
        $counts = [
            'users'           => User::whereHas('roles', fn($q) => $q->whereIn('name', ['Customer']))->count(),
            'staff'           => User::whereHas('roles', fn($q) => $q->whereNotIn('name', ['Customer', 'Developer', 'Owner']))->count(),
            'quran_chapters'  => QuranChapter::count(),
            'quran_verses'    => QuranVerse::count(),
            'hadith_books'    => HadithBook::count(),
            'hadith_chapters' => HadithChapter::count(),
            'hadith_verses'   => HadithVerse::count(),
        ];

        return view('admin.dashboard', compact('counts'));
    }

    public function activityLog($logName = null, $eventName = null, $causerId = null, $subjectId = null)
    {
        $logs      = Activity::selectRaw('log_name, event, MAX(id) as id')->whereNotNull('causer_id')->groupBy('log_name', 'event')->get();
        $logTables = $logs->pluck('log_name')->unique()->toArray();
        $logEvents = $logs->pluck('event')->unique()->toArray();

        $logUsers = Activity::with('causer:id,first_name,last_name')
            ->when(! empty($logName), fn($query) => $query->where('log_name', $logName))
            ->when(! empty($eventName), fn($query) => $query->where('event', $eventName))
            ->when(! empty($subjectId), fn($query) => $query->where('subject_id', $subjectId))
            ->whereNotNull('causer_id')
            ->get()
            ->pluck('causer')
            ->unique();

        $logSubjects = Activity::selectRaw('subject_id, MAX(id) as id')
            ->when(! empty($logName), fn($query) => $query->where('log_name', $logName))
            ->when(! empty($eventName), fn($query) => $query->where('event', $eventName))
            ->whereNotNull('causer_id')
            ->whereNotNull('subject_id')
            ->groupBy('subject_id')
            ->pluck('subject_id')
            ->unique()
            ->toArray();

        if (empty($logName) && empty($eventName) && empty($causerId) && empty($subjectId)) {
            $activityLogs = [];
        } else {
            $activityLogs = Activity::select(['log_name', 'event', 'causer_id', 'subject_id', 'id', 'properties'])
                ->when(! empty($logName), fn($query) => $query->where('log_name', $logName))
                ->when(! empty($eventName), fn($query) => $query->where('event', $eventName))
                ->when(! empty($causerId), fn($query) => $query->where('causer_id', $causerId))
                ->when(! empty($subjectId), fn($query) => $query->where('subject_id', $subjectId))
                ->whereNotNull('causer_id')
                ->get();
        }

        return view('admin.activity-log',
            compact('activityLogs', 'logTables', 'logEvents', 'logUsers', 'logSubjects', 'logName', 'eventName', 'causerId', 'subjectId')
        );
    }
}
