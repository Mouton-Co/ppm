<?php

namespace App\Http\Services;

use App\Models\Part;

class PartService
{
    /**
     * The part instance
     *
     * @var Part
     */
    protected $part;

    /**
     * Constructor
     */
    public function __construct(Part $part)
    {
        $this->part = $part;
    }

    /**
     * Update the quantities of a part
     */
    public function updateQuantities($field, $value): bool
    {
        if (
            $field == 'quantity' ||
            $field == 'quantity_in_stock' ||
            $field == 'quantity_ordered'
        ) {
            $this->part->$field = is_numeric($value) && $value >= 0 ?
                $value :
                0;
        }

        $updated = false;

        if (
            $field == 'quantity' ||
            $field == 'quantity_in_stock'
        ) {
            $updated = true;

            if ($field == 'quantity') {
                $this->part->quantity = $value;
            } elseif ($field == 'quantity_in_stock') {
                $this->part->quantity_in_stock = $value;
            }

            $this->part->quantity_ordered = $this->part->quantity - $this->part->quantity_in_stock;

            if ($this->part->quantity_ordered < 0) {
                $this->part->quantity_ordered = 0;
            }
        }

        return $updated;
    }
}
