<?php

// Coordenadas padrão: Castro - PR (-24.7911, -50.0119)
return [
    'maps.center' => [
        (float) env('MAPS_LATITUDE', -24.7911),  // latitude (Castro - PR)
        (float) env('MAPS_LONGITUDE', -50.0119), // longitude (Castro - PR)
    ],

    'maps.zoom.default' => (int) env('MAPS_ZOOM_DEFAULT', 13),
    'maps.zoom.approximate' => (int) env('MAPS_ZOOM_APPROXIMATE', 14),
    'maps.zoom.precise' => (int) env('MAPS_ZOOM_PRECISE', 16),
    'maps.zoom.max' => (int) env('MAPS_ZOOM_MAX', 18),
    'maps.zoom.min' => (int) env('MAPS_ZOOM_MIN', 9),

    'maps.tileServer' => env('MAPS_TILESERVER', '//{s}.tile.osm.org/{z}/{x}/{y}.png'),
];
