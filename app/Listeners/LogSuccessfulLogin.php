<?php

namespace App\Listeners;

use App\Models\AuditTrail;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;


class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->login_count += 1;
        $event->user->save();

        $audit_trail = AuditTrail::create([
           'user_id' => $event->user->id,
           'role'    => $event->user->roles[0]->name
        ]);
    }
}
