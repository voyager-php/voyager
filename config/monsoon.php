<?php

return [

    'enable' => true,

    'screens' => [
        'xs' => 480,
        'sm' => 767,
        'md' => 1023,
        'lg' => 1279,
        'xl' => 1400,
    ],

    'default' => [
        
        'colors' => [
            'transparent'   => 'transparent',
            'black'         => '#000000',
            'white'         => '#ffffff',
            'gray'          => [
                '100'       => '#f7fafc',
                '200'       => '#edf2f7',
                '300'       => '#e2e8f0',
                '400'       => '#cbd5e0',
                '500'       => '#a0aec0',
                '600'       => '#718096',
                '700'       => '#4A5568',
                '800'       => '#2d3748',
                '900'       => '#1a202c',
            ],
            'red'           => [
                '100'       => '#fffaf0',
                '200'       => '#feebc8',
                '300'       => '#feb2b2',
                '400'       => '#fc8181',
                '500'       => '#f56565',
                '600'       => '#e53e3e',
                '700'       => '#c53030',
                '800'       => '#9b2c2c',
                '900'       => '#742a2a',
            ],
            'orange'        => [
                '100'       => '#fffaf0',
                '200'       => '#feebc8',
                '300'       => '#fbd38d',
                '400'       => '#f6ad55',
                '500'       => '#ed8936',
                '600'       => '#dd6b20',
                '700'       => '#c05621',
                '800'       => '#9c4221',
                '900'       => '#7b341e',
            ],
            'yellow'        => [
                '100'       => '#fffff0',
                '200'       => '#fefcbf',
                '300'       => '#faf089',
                '400'       => '#f6e05e',
                '500'       => '#ecc94b',
                '600'       => '#d69e2e',
                '700'       => '#b7791f',
                '800'       => '#975a16',
                '900'       => '#744210',
            ],
            'green'         => [
                '100'       => '#f0fff4',
                '200'       => '#c6f6d5',
                '300'       => '#9ae6b4',
                '400'       => '#68d391',
                '500'       => '#48bb78',
                '600'       => '#38a169',
                '700'       => '#2f855a',
                '800'       => '#276749',
                '900'       => '#22543d',
            ],
            'teal'          => [
                '100'       => '#e6fffa',
                '200'       => '#b2f5ea',
                '300'       => '#81e6d9',
                '400'       => '#4fd1c5',
                '500'       => '#38b2ac',
                '600'       => '#319795',
                '700'       => '#2c7a7b',
                '800'       => '#285e61',
                '900'       => '#234e52',
            ],
            'blue'          => [
                '100'       => '#ebf8ff',
                '200'       => '#bee3f8',
                '300'       => '#90cdf4',
                '400'       => '#63b3ed',
                '500'       => '#4299e1',
                '600'       => '#3182ce',
                '700'       => '#2b6cb0',
                '800'       => '#2c5282',
                '900'       => '#2a4365',
            ],
            'indigo'        => [
                '100'       => '#ebf4ff',
                '200'       => '#c3dafe',
                '300'       => '#a3bffa',
                '400'       => '#7f9cf5',
                '500'       => '#667eea',
                '600'       => '#5a67d8',
                '700'       => '#4c51bf',
                '800'       => '#434190',
                '900'       => '#3c366b',
            ],
            'purple'        => [
                '100'       => '#faf5ff',
                '200'       => '#e9d8fd',
                '300'       => '#d6bcfa',
                '400'       => '#b794f4',
                '500'       => '#9f7aea',
                '600'       => '#805ad5',
                '700'       => '#6b46c1',
                '800'       => '#553c9a',
                '900'       => '#44337a',
            ],
            'pink'          => [
                '100'       => '#fff5f7',
                '200'       => '#fed7e2',
                '300'       => '#fbb6ce',
                '400'       => '#f687b3',
                '500'       => '#ed64a6',
                '600'       => '#d53f8c',
                '700'       => '#b83280',
                '800'       => '#97266d',
                '900'       => '#44337a',
            ],
        ],

        'width' => [
            'auto'          => 'auto',
            'screen'        => '100vw',
            'full'          => '100%',
            '0'             => '0',
        ],

        'height' => [
            'auto'          => 'auto',
            'screen'        => '100vh',
            'full'          => '100%',
            '0'             => '0',
        ],

        'min-width' => [
            'full'          => '100%',
            'none'          => 'none',
        ],

        'max-width' => [
            'full'          => '100%',
            'none'          => 'none',
        ],

        'min-height' => [
            'full'          => '100%',
            'none'          => 'none',
            'screen'        => '100vh',
        ],

        'max-height' => [
            'full'          => '100%',
            'none'          => 'none',
        ],

        'top' => [
            '0'             => '0',
            'auto'          => 'auto',
        ],

        'bottom' => [
            '0'             => '0',
            'auto'          => 'auto',
        ],

        'left' => [
            '0'             => '0',
            'auto'          => 'auto',
        ],

        'right' => [
            '0'             => '0',
            'auto'          => 'auto',
        ],

        'margin' => [
            'none'          => '0',
            'auto'          => 'auto',
        ],

        'padding' => [
            'none'          => '0',
            'auto'          => 'auto',
        ],

        'font-family' => [
            'sans'          => 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
            'serif'         => 'font-family: Georgia, Cambria, "Times New Roman", Times, serif',
            'mono'          => 'Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',
        ],

        'font-size' => [
            'xs'            => '0.75rem',
            'sm'            => '0.875rem',
            'base'          => '1rem',
            'lg'            => '1.125rem',
            'xl'            => '1.25rem',
            '2xl'           => '1.5rem',
            '3xl'           => '1.875rem',
            '4xl'           => '2.25rem',
            '5xl'           => '3rem',
            '6xl'           => '4rem',
        ],

        'rounded' => [
            'none'          => '0',
            'full'          => '9999px',
            'sm'            => '0.125rem',
            'md'            => '0.375rem',
            'lg'            => '0.5rem',
        ],

        'border' => [
            'none'          => '0',
        ],

        'order' => [
            'none'          => 0,
            'first'         => -9999,
            'last'          => 9999,
            '1'             => 1,
            '2'             => 2,
            '3'             => 3,
            '4'             => 4,
            '5'             => 5,
            '6'             => 6,
            '7'             => 7,
            '8'             => 8,
            '9'             => 9,
            '10'            => 10,
            '11'            => 11,
            '12'            => 12,
        ],

        'grid' => [
            '1'             => 'repeat(1, minmax(0, 1fr))',
            '2'             => 'repeat(2, minmax(0, 1fr))',
            '3'             => 'repeat(3, minmax(0, 1fr))',
            '4'             => 'repeat(4, minmax(0, 1fr))',
            '5'             => 'repeat(5, minmax(0, 1fr))',
            '6'             => 'repeat(6, minmax(0, 1fr))',
            '7'             => 'repeat(7, minmax(0, 1fr))',
            '8'             => 'repeat(8, minmax(0, 1fr))',
            '9'             => 'repeat(9, minmax(0, 1fr))',
            '10'            => 'repeat(10, minmax(0, 1fr))',
            '11'            => 'repeat(11, minmax(0, 1fr))',
            '12'            => 'repeat(12, minmax(0, 1fr))',
            'none'          => 'none',
        ],

        'gap' => [
            'px'            => '1px',
            '0'             => '0',
            '1'             => '0.25rem',
            '2'             => '0.5rem',
            '3'             => '0.75rem',
            '4'             => '1rem',
            '5'             => '1.25rem',
            '6'             => '1.5rem',
            '8'             => '2rem',
            '10'            => '2.5rem',
            '12'            => '3rem',
            '16'            => '4rem',
            '20'            => '5rem',
            '24'            => '6rem',
            '32'            => '8rem',
            '40'            => '10rem',
            '48'            => '12rem',
            '56'            => '14rem',
            '64'            => '16rem',
        ],

        'line-height' => [
            '3'             => '.75rem',
            '4'             => '1rem',
            '5'             => '1.25rem',
            '6'             => '1.5rem',
            '7'             => '1.75rem',
            '8'             => '2rem',
            '9'             => '2.25rem',
            '10'            => '2.5rem',
            'none'          => '1',
            'tight'         => '1.25',
            'snug'          => '1.375',
            'normal'        => '1.5',
            'relaxed'       => '1.625',
            'loose'         => '2',
        ],

        'border-radius' => [
            'none'          => 0,
            'sm'            => '0.125rem',
            'md'            => '0.375rem',
            'lg'            => '0.5rem',
            'xl'            => '0.75rem',
            '2xl'           => '1rem',
            '3xl'           => '1.5rem',
            'full'          => '9999px',
        ],

        'border-width' => [
            '0'             => 0,
        ],

        'opacity' => [
            '0'             => 0,
            '25'            => '0.25',
            '50'            => '0.5',
            '75'            => '0.75',
            '100'           => '1',
        ],
    ],

];