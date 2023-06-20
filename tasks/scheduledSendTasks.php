<?php

use Crunz\Schedule;
use Courier\CourierClient;
use Dotenv\Dotenv;

// Configure environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configure scheduler
$schedule = new Schedule();

// Configure the Courier PHP SDK
$courier = new CourierClient($_ENV['COURIER_BASE_URL'], $_ENV['COURIER_AUTHORIZATION_TOKEN']);

// Create a new scheduled task
$task = $schedule->run(function () use ($courier) {

    // For real-world usage, we would probably do something here like pulling a list of new appointments from a database,
    // and then scheduling notifications to the recipient that their appointment is coming up, do be delivered the day before.

    // Send notification using the Courier PHP SDK
    $notification = (object) [
        "message" => [
            "to" => [
                "data" => [
                    "name" => "Max Overdrive"
                ],
                "email" => $_ENV['TEST_EMAIL_TO']
            ],
            "template" => $_ENV['TEST_SCHEDULED_SEND_TEMPLATE'],
            "routing" => [
                "method" => "single",
                "channels" => ["email"]
            ]
        ]
    ];
    $result = $courier->sendEnhancedNotification($notification);
});

// Schedule a single notification for a specific time
$task->on('13:30 2023-06-01')
    ->description('Sending scheduled email');

return $schedule;
