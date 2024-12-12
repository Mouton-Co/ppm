<?php

namespace App\Policies;

class SubmissionPolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = 'design';
    }
}
