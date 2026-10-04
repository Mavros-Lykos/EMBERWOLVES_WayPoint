<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\RoutingService::class);
// Peliyagoda DC to Kandy
echo "Calling Google Maps Routes API (v2)...\n";
$distance = $service->getDistance(6.9497, 79.8899, 7.2906, 80.6337);

if ($distance > 0) {
    echo "SUCCESS: Distance is " . ($distance / 1000) . " km.\n";
} else {
    echo "FAILED: Distance returned 0. Check your API key or logs.\n";
}
