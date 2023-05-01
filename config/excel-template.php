<?php

/**
 * Template to check the uploaded designer .xlsx sheets against
 *
 * Layout:
 *
 * Filename => [
 *  allowedValues => []
 * ]
 */

return [
    'No.' => [
        "type"   => "int",
        "unique" => true,
    ],
    'File Name' => [
        "type"   => "string",
        "unique" => true
    ],
    'Qty' => [
        "type"   => "int",
        "unique" => false
    ],
    'Material' => [
        "type"   => "string",
        "unique" => false
    ],
    'Material Thickness' => [
        "type"   => "string",
        "unique" => false
    ],
    'Finish' => [
        "type"   => "string",
        "unique" => false
    ],
    'Used In Weldment' => [
        "type"    => "string",
        "unique"  => false,
        "allowed" => [
            'no',
            'yes'
        ]
    ],
    'Process Type' => [
        "type"    => "string",
        "unique"  => false,
        "allowed" => [
            'lc',
            'mch',
            'lcb',
            'lcbw',
            'lbwm',
            'tlcm',
            'pm',
            'p'
        ]
    ],
    'Notes' => [
        "type"   => "string",
        "unique" => false
    ]
];
