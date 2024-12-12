<?php

namespace App\Policies;

class ProjectResponsiblePolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = 'departments';
    }
}
