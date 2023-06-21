# Examples of Scheduling Courier Notifications in PHP

This repository contains examples on how the [Courier notification platform](https://www.courier.com/) can be used with PHP for sending scheduled notifications.

## Requirements

This project requires the following packages, [installed via composer](https://getcomposer.org/doc/01-basic-usage.md)
- [crunzphp/crunz](https://github.com/crunzphp/crunz) - Schedule one-time and recurring tasks
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) - Handle environment variables 
- [trycourier/courier](https://github.com/trycourier/courier-php) - The Courier PHP SDK
- [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) - Required by the Courier PHP SDK

You can install all of this in your own project by running: 

    composer require crunzphp/crunz vlucas/phpdotenv trycourier/courier guzzlehttp/guzzle

If you are cloning this repository, run the following to install these dependencies from the included  `coomposer.json` file:

    composer install

Crunz is used for scheduling in this app, and it has its own [extensive documentation](https://github.com/crunzphp/crunz). The most important bit is adding it to the [crontab](https://man7.org/linux/man-pages/man5/crontab.5.html) on your server - once this is done, all task handling is handled from within your PHP app, no need to add additional cron jobs:

    * * * * * cd /path/to/project && vendor/bin/crunz schedule:run

## Tasks

Crunz tasks must be created in the `./tasks` directory, and end with `Tasks.php`. 

Crunz requires a configuration file be present, containing a configured timezone. Create this by running:

    vendor/bin/crunz publish:config

You can confirm tasks are present by running:

    vendor/bin/crunz schedule:list

This will output a table containing your scheduled tasks:

    +---+------------------------------+-------------+-----------------+
    | # | Task                         | Expression  | Command to Run  |
    +---+------------------------------+-------------+-----------------+
    | 1 | Sending scheduled email      | 30 13 1 6 * | object(Closure) |
    | 2 | Sending recurring email      | 30 13 * * * | object(Closure) |
    | 3 | Sending scheduled automation | 30 13 1 6 * | object(Closure) |
    +---+------------------------------+-------------+-----------------+

For testing, you can force all tasks to run immediately:

    vendor/bin/crunz schedule:run --force

For more about tasks, including calling external PHP functions and logging the results for debugging, check out the [Crunz documentation](https://github.com/crunzphp/crunz).

## Courier

This example app uses the Courier PHP SDK to send notifications using Courier.

The credentials for Courier must be supplied in the `.env` file which can be created by duplicating the template file:

    cp .env.example .env

...and then filling out the require details.

The following must also be configured on the Courier Side: [Templates, Recipients, and Brands](https://www.courier.com/docs/getting-started/courier-concepts/). Each of these will have their own unique ID which is used by the Courier SDK and can should be added to the `.env` file.

## Using Laravel?

There's no need to use Crunz if you're using Laravel - [it already has scheduling built in](https://laravel.com/docs/10.x/scheduling).