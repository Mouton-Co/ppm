<?php

namespace App\Policies;

class UserPolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = 'users';
    }
}
