<?php
return [
    'measures_config' => [
        'Weight' => [
            'standard' => 'GRAM',
            'units'    => [
                'GRAM'     => [
                    'convert'           => [['mul' => 1]],
                    'symbol'            => 'g',
                    'name'              => 'gram',
                    'unece_code'        => 'GRM',
                    'alternative_units' => [],
                ],
                'KILOGRAM'     => [
                    'convert'           => [['mul' => 1000]],
                    'symbol'            => 'kg',
                    'name'              => 'kilo gram',
                    'unece_code'        => 'KGM',
                    'alternative_units' => ['kilo'],
                ],
                'BADMETER' => [
                    'convert'           => [['mul' => 666]],
                    'symbol'            => 'm',
                    'alternative_units' => [],
                ],
            ],
        ],
        'Length' => [
            'standard' => 'METER',
            'units'    => [
                'METER' => [
                    'convert'           => [['mul' => 1]],
                    'symbol'            => 'm',
                    'alternative_units' => ['mt'],
                ],
                'KILOMETER' => [
                    'convert'           => [['mul' => 1000]],
                    'symbol'            => 'km',
                    'alternative_units' => [],
                ],
            ],
        ],
    ]
];
