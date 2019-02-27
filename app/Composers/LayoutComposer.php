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
    /**
     * The guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * AccountComposer constructor
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('currentUser', $this->auth->user());
    }
}
