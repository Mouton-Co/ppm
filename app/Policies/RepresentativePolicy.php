<?php

namespace App\Policies;

class RepresentativePolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = "representatives";
    }
}
