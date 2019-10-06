<?php

namespace App\Http\Controllers;

use App\Exports\AuditLogsExport;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ActivityController.
 */
class ActivityController extends Controller
{
    /**
     * Create new ActivityController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', '2fa', 'role:admin', 'forbid-banned-user']);
        $this->middleware('portal:kiosk')->only(['export', 'index']);
    }

    /**
     * Method for displaying all the audit logs in the application.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('activity.index', ['logs' => Activity::latest()->simplePaginate()]);
    }

    /**
     * Method for searching audit entries in the application.
     *
     * @param  Request  $request    The form request instance that holds all the data.
     * @param  Activity $activity   The activity model for all the audit entries.
     * @return Renderable
     */
    public function search(Request $request, Activity $activity): Renderable
    {
        return view('activity.index', [
            'logs' => $activity->where('log_name', 'LIKE', "%{$request->term}%")
                ->orWhere('description', 'LIKE', "%{$request->term}%")
                ->orWhere('created_at', 'LIKE', "%{$request->term}%")
                ->simplePaginate(),
        ]);
    }

    /**
     * Method for downloading the audit entries to an excel file. (.xls).
     *
     * @param  string|null $filter The criteria name that has been given by the user.
     * @return BinaryFileResponse
     */
    public function export(?string $filter = null): BinaryFileResponse
    {
        $this->middleware('role:admin');

        $fileName = now()->format('d-m-Y').'-audit-logs.xls';
        return (new AuditLogsExport($filter))->download($fileName);
    }

    /**
     * Method to display the logged user operations in the application.
     *
     * @param  User $user   The database entity from the given user.
     * @return Renderable
     */
    public function show(User $user): Renderable
    {
        $activities = $user->actions()->orderBy('created_at', 'DESC')->simplePaginate();
        return view('activity.user', compact('activities', 'user'));
    }
}
