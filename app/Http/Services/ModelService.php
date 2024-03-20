<?php

namespace App\Http\Services;

class ModelService
{
    /**
     * Get the value of a nested property
     *
     * @param  mixed  $resource
     * @param  string|null  $value
     */
    public static function nestedValue($resource, $value): ?string
    {
        $nesters = explode('->', $value);
        $nester = $nesters[0];
        $field = $resource->$nester ?? null;

        for ($i = 1; $i < count($nesters); $i++) {
            $nester = $nesters[$i];
            $field = $field->$nester ?? null;
        }

        return $field;
    }
}
