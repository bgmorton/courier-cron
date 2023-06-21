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

    // For real-world usage, we would probably do something here like pulling a list of users from a database who's birthday is today
    // and then sending a happy birthday notification.
    
    // Send notification using the Courier PHP SDK
    $notification = (object) [
        "message" => [
            "to" => [
                "data" => [
                    "name" => "Max Overdrive"
                ],
                "email" => $_ENV['TEST_EMAIL_TO']
            ],
            "template" => $_ENV['TEST_DIRECT_SEND_NOTIFICATION_TEMPLATE_ID'],
            "routing" => [
                "method" => "single",
                "channels" => ["email"]
            ]
        ]
    ];
    $result = $courier->sendEnhancedNotification($notification);
});

// Set up a recurring task
$task
    ->daily()
    ->at('13:30')
    ->description('Sending recurring email');

return $schedule;
