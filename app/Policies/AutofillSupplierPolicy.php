<?php

namespace App\Policies;

class AutofillSupplierPolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = "autofill_suppliers";
    }
}
