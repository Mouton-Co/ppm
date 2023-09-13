<?php

/**
 * Template to check the uploaded designer .xlsx sheets against
 *
 * Layout with default values:
 *
 * Filename => [
 *  type => string
 *  unique => false
 *  required => false
 *  allowedValues => []
 * ]
 */

return [
    'No.' => [
        "type"     => "int",
        "unique"   => true,
        "required" => true,
    ],
    'File Name' => [
        "unique"   => true,
        "required" => true,
    ],
    'Quantity' => [
        "type"   => "int",
        "required" => true,
    ],
    'Material' => [
        "required" => true,
    ],
    'Material Thickness' => "",
    'Finish' => "",
    'Used In Weldment' => [
        "required" => true,
        "allowed"  => [
            'no',
            'yes',
            'lone weldment',
            '',
        ]
    ],
    'Process Type' => [
        "required" => true,
        "allowed"  => [
            'lc',
            'lcm',
            'mch',
            'lcb',
            'lcbm',
            'lcbw',
            'lbwm',
            'tlcm',
            'tlc',
            'pm',
            'p'
        ]
    ],
    'Notes' => "",
    "Manufactured or Purchased" => [
        "allowed"  => [
            "purchased",
            "manufactured",
            "purchased then manufactured",
        ],
    ],
];
