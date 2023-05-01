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
        "type" => "int"
    ],
    'File Name' => [
        "type" => "string"
    ],
    'Qty' => [
        "type" => "int"
    ],
    'Material' => [
        "type" => "string"
    ],
    'Material Thickness' => [
        "type" => "string"
    ],
    'Finish' => [
        "type" => "string"
    ],
    'Used In Weldment' => [
        "type" => "string",
        "allowed" => [
            'no',
            'yes'
        ]
    ],
    'Process Type' => [
        "type" => "string",
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
        "type" => "string"
    ]
];
