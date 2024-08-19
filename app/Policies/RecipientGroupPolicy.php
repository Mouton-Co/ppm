<?php

namespace App\Policies;

class RecipientGroupPolicy extends Policy
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = "email_triggers";
    }
}
