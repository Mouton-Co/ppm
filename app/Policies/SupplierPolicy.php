<?php

namespace App\Policies;

class SupplierPolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = 'suppliers';
    }
}
