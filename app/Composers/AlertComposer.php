<?php

namespace App\Composers;

use App\Models\SystemAlert;
use Illuminate\View\View;

/**
 * Class AlertComposer
 *
 * @package App\Composers
 */
class AlertComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view The view builder instance.
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('notifications_count', SystemAlert::count());
    }
}
