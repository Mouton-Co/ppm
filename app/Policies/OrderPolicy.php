<?php

namespace App\Policies;

class OrderPolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = "orders";
    }
}
