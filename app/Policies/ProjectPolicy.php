<?php

namespace App\Policies;

class ProjectPolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = 'projects';
    }
}
