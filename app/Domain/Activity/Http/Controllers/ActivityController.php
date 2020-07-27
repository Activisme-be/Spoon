<?php

namespace App\Domain\Activity\Http\Controllers;

use App\Domain\Activity\Exports\AuditLogsExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ActivityController
 *
 * @package App\Http\Controllers
 */
class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa', 'role:admin', 'forbid-banned-user']);
        $this->middleware('portal:kiosk')->only(['export', 'index']);
    }

    public function index(): Renderable
    {
        return view('activity.index', ['logs' => Activity::latest()->simplePaginate()]);
    }

    public function search(Request $request, Activity $activity): Renderable
    {
        return view('activity.index', [
            'logs' => $activity->where('log_name', 'LIKE', "%{$request->term}%")
                ->orWhere('description', 'LIKE', "%{$request->term}%")
                ->orWhere('created_at', 'LIKE', "%{$request->term}%")
                ->simplePaginate(),
        ]);
    }

    public function export(?string $filter = null): BinaryFileResponse
    {
        $this->middleware('role:admin');

        $fileName = now()->format('d-m-Y').'-audit-logs.xls';
        return (new AuditLogsExport($filter))->download($fileName);
    }

    public function show(User $user): Renderable
    {
        $activities = $user->actions()->orderBy('created_at', 'DESC')->simplePaginate();
        return view('activity.user', compact('activities', 'user'));
    }
}
