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
    // and then scheduling notifications to the recipient that their appointment is coming up.
    // Automations allow for chaining commands, so we can schedule multiple reminders to go out at different times.

    // Invoke an automation using the Courier PHP SDK
    $automation = (object) [
        "steps" => [
            [
                "action" => "send",
                "recipient" => $_ENV['TEST_AUTOMATION_RECIPIENT_USER_ID'],
                "template" => $_ENV['TEST_AUTOMATION_NOTIFICATION_TEMPLATE_ID_1'],// Reminder email
                "brand" => $_ENV['YOUR_COURIER_BRAND_ID'],
                "data" => [
                    "name" => "Max Overdrive",
                ]
            ],
            [
                "action" => "delay",
                "duration" => "2 minutes" // You will probably want to delay by days or hours, but minutes is easier for testing
            ],
            [
                "action" => "send",
                "recipient" => $_ENV['TEST_AUTOMATION_RECIPIENT'],
                "template" => $_ENV['TEST_AUTOMATION_NOTIFICATION_TEMPLATE_ID_2'],// Follow-up email
                "brand" => $_ENV['YOUR_COURIER_BRAND_ID'],
                "data" => [
                    "name" => "Max Overdrive",
                ]
            ]
        ]
    ];
    $result = $courier->invokeAutomation($automation);
});

// Schedule the automation for a specific time
$task->on('13:30 2023-06-01')
    ->description('Sending scheduled email');

return $schedule;
