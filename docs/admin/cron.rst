Cron
####

.. contents:: Contents
   :depth: 2
   :local:

What does it do?
================

`Cron`_ is a program available on Unix-based operating systems that enables
users to run commands and scripts at set intervals or at specific times.

Elgg's cron handler allows administrators and plugin developers to setup jobs
that need to be executed at set intervals.

Most common examples of cron jobs in Elgg include:

 * sending out queued notifications
 * rotating the system log in the database
 * collecting garbage in the database (compacting the database by removing entries that are no longer required)

Plugins can add jobs by registering a plugin hook handler for one of the following cron intervals:

 * ``minute`` - Run every minute
 * ``fiveminute`` - Run every 5 minutes
 * ``fifteenmin`` - Run every 15 minutes
 * ``halfhour`` - Run every 30 minutes
 * ``hourly`` - Run every hour
 * ``daily`` - Run every day
 * ``weekly`` - Run every week
 * ``monthly`` - Run every month
 * ``yearly`` - Run every year

.. code-block:: php

   elgg_register_plugin_hook_handler('cron', 'hourly', function() {

      $events = my_plugin_get_upcoming_events();

      foreach ($events as $event) {
         $attendees = $event->getAttendees();

         // notify
      }
   });


How does it work?
=================

``crontab`` must be setup in such a way as to activate Elgg cron handler every minute, or at a specific interval.
Once cron tab activates the cron job, Elgg executes all hook handlers attached to that interval.

If you have SSH access to your Linux servers, type ``crontab -e`` and add your crontab configuration.

.. code-block:: text

   * * * * * path/to/phpbin path/to/elgg/elgg-cli cron -q

The above command will run every minute and activate all due cron jobs.

Optionally you can activate handlers for a specific interval:

.. code-block:: text

   0 * * * * path/to/phpbin path/to/elgg/elgg-cli cron -i hourly -q


More information about cron can be found at:

.. _Cron: http://en.wikipedia.org/wiki/Cron
.. _cPanel Docs: https://docs.cpanel.net/display/ALD/Cron+Jobs
