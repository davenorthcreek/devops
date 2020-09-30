<?php

namespace App\Auth;

use App\Auth\Notifications\CreatePassword as CreatePasswordNotification;

trait CanCreatePassword
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordCreate()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordCreateNotification($token)
    {
        $this->notify(new CreatePasswordNotification($token));
    }
}
