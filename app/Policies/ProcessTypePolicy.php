<?php

namespace App\Policies;

class ProcessTypePolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = "process_types";
    }
}
