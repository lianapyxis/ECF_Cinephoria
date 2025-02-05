<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    'jquery/dist/jquery.min' => [
        'path' => 'vendor/jquery/jquery.index.js',
        'entrypoint' => true,
    ],
    'datatables.js' => [
        'path' => 'vendor/datatables.net/datatables.net.index.js',
        'entrypoint' => true,
    ],
    'datatables-buttons.js' => [
        'path' => 'vendor/datatables-buttons/datatables-buttons.index.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.12',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'datatables' => [
        'version' => '1.10.18',
    ],
    'datatables-buttons' => [
        'version' => '1.0.3',
    ],
    'datatables.net' => [
        'version' => '2.2.0',
    ],
    'chart.js' => [
        'version' => '4.4.7',
    ],
    '@kurkle/color' => [
        'version' => '0.3.4',
    ],
];
