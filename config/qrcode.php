<?php

return [
    'default' => 'png',
    'renderers' => [
        'png' => [
            'extension' => 'png',
            'render' => 'image',
            'backend' => 'gd', // Gunakan GD sebagai backend alih-alih imagick
            'quality' => 90,
            'margin' => 1
        ],
        'svg' => [
            'extension' => 'svg',
            'render' => 'svg',
            'backend' => 'svglib',
        ]
    ]
];