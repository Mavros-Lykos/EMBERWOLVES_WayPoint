<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if ($user) {
    // 1. Success Notification
    $user->notify(new \App\Notifications\SystemNotification(
        'Delivery Complete',
        'Trip TRP-901 has been successfully delivered and signed.',
        'success'
    ));

    // 2. Warning Notification
    $user->notify(new \App\Notifications\SystemNotification(
        'Temperature Warning',
        'Reefer temperature on Vehicle WP-2234 has exceeded 4°C.',
        'warning'
    ));

    // 3. Error Notification
    $user->notify(new \App\Notifications\SystemNotification(
        'Engine Breakdown',
        'Driver Nimal reported an engine failure on Route 7A.',
        'error'
    ));

    // 4. Info Notification
    $user->notify(new \App\Notifications\SystemNotification(
        'System Update',
        'The dispatch planner has been automatically optimized for afternoon cutoff.',
        'info'
    ));

    echo "Generated 4 different types of notifications (Success, Warning, Error, Info) for " . $user->name . "\n";
} else {
    echo "No user found to notify.\n";
}
