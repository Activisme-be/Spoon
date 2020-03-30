<?php

namespace App\Composers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\View\View;

/**
 * Class LayoutComposer
 *
 * @package App\Composers
 */
class LayoutComposer
{
    protected Guard $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function compose(View $view): void
    {
        $view->with('currentUser', $this->auth->user());
    }
}
