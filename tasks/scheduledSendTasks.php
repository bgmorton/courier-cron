<?php

use Crunz\Schedule;
use Courier\CourierClient;
use Dotenv\Dotenv;

// Configure environment variables - Set the .env directory to the parent of the Tasks directory
// Environment variables are stored in a variable so that they can be passed to the scheduled task, which will not have access to the current global namespace
$dotenv = Dotenv::createArrayBacked(__DIR__ . "/..")->load();

// Configure scheduler
$schedule = new Schedule();

// Configure the Courier PHP SDK - Note the first null is the API path, of which we will use the default
$courier = new CourierClient(null, $dotenv['COURIER_AUTHORIZATION_TOKEN']);

// Create a new scheduled task
$task = $schedule->run(function () use ($courier, $dotenv) {

    echo "Running " . __FILE__ . "\n";

    // For real-world usage, we would probably do something here like pulling a list of new appointments from a database,
    // and then scheduling notifications to the recipient that their appointment is coming up, to be delivered the day before.

    // Send notification using the Courier PHP SDK
    $notification = (object) [
        "to" => [
            "email" => $dotenv['TEST_EMAIL_TO']
        ],
        "template" => $dotenv['TEST_DIRECT_SEND_NOTIFICATION_TEMPLATE_ID'],
        "routing" => [
            "method" => "single",
            "channels" => ["email"]
        ],
        "data" => [
            "name" => "Max Overdrive"
        ]
    ];
    $result = $courier->sendEnhancedNotification($notification);
});

// Schedule a single notification for a specific time
$task->on('13:30 2023-06-01')
    ->description('Sending scheduled email');

return $schedule;
