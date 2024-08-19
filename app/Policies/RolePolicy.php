<?php

namespace App\Policies;

class RolePolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = "roles";
    }
}
