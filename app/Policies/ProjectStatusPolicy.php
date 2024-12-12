<?php

namespace App\Policies;

class ProjectStatusPolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = 'project_statuses';
    }
}
