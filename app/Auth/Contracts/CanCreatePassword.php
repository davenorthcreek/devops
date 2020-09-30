<?php

namespace App\Auth\Contracts;

interface CanCreatePassword
{
    /**
     * Get the e-mail address where password create links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordCreate();

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordCreateNotification($token);
}
