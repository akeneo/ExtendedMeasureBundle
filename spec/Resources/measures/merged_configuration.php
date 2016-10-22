<?php
return [
    'measures_config' => [
        'SpecFamily' => [
            'standard' => 'SPEC_STANDARD',
            'units'    => [
                'SPEC_STANDARD' => [
                    'convert'           => [['mul' => 1]],
                    'symbol'            => 'spec',
                    'name'              => 'standard for the specs',
                    'unece_code'        => 'fooCode',
                    'alternative_units' => []
                ],
                'KILO_STANDARD' => [
                    'convert'           => [['mul' => 1000]],
                    'symbol'            => 'kilospec',
                    'name'              => 'kilo measure of the standard',
                    'unece_code'        => 'barCode',
                    'alternative_units' => ['kylo']
                ]
            ]
        ],
        'Weight'     => [
            'standard' => 'g',
            'units'    => [
                'KILOGRAM'   => [
                    'convert'           => [['mul' => 1000]],
                    'symbol'            => 'kg',
                    'name'              => 'kilogram',
                    'unece_code'        => 'KGM',
                    'alternative_units' => ['kilo', 'kilogramme']
                ],
                'WRONGMETER' => [
                    'symbol'            => 'm',
                    'convert'           => [['mul' => 666]],
                    'alternative_units' => []
                ]
            ]
        ],
        'Length'     => [
            'standard' => 'METER',
            'units'    => [
                'METER' => [
                    'symbol'            => 'm',
                    'convert'           => [['mul' => 1]],
                    'alternative_units' => []
                ]
            ]
        ]
    ]
];
