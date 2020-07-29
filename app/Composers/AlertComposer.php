<?php

namespace App\Composers;

use App\Domain\Announcements\Models\SystemAlert;
use Illuminate\View\View;

/**
 * Class AlertComposer
 *
 * @package App\Composers
 */
class AlertComposer
{
    public function compose(View $view): void
    {
        $view->with('notifications_count', SystemAlert::count());
    }
}
