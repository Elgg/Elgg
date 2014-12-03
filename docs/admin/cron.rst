Cron
####

`Cron`__ is a program available on Unix-based operating systems that enables
users to run commands and scripts at set intervals or at specific times.

__ http://en.wikipedia.org/wiki/Cron

Elgg's cron handler allows administrators and plugin developers to setup jobs
that need to be executed at set intervals.

Most common examples of cron jobs in Elgg include:

 * sending out queued notifications
 * rotating the system log in the database
 * collecting garbage in the database (compacting the database by removing
   entries that are no longer required)

Currently, Elgg supports the following hooks:

 * ``minute`` - Run every minute
 * ``fiveminute`` - Run every 5 minutes
 * ``fifteenmin`` - Run every 15 minutes
 * ``halfhour`` - Run every 30 minutes
 * ``hourly`` - Run every hour
 * ``daily`` - Run every day
 * ``weekly`` - Run every week
 * ``monthly`` - Run every month
 * ``yearly`` - Run every year

.. note::

	``reboot`` cron hook has been deprecated and should not be used


How does it work?
=================

Elgg activates its cron handler when particular cron pages are loaded.
As an example, loading http://example.com/cron/hourly/ in a web browser 
activates the hourly hook. To automate this, cron jobs are setup to hit those
pages at certain times. This is done by setting up a ``crontab`` which is a
configuration file that determines what cron jobs do and at what interval.


Installation
============

The ``crontab`` needs to specify a script or command that will hit the Elgg cron pages.
Two commonly available programs for this are `GET` and `wget`. You will need
to determine the location of one of these on your server. Your crontab also needs
to specify the location of your website.

.. literalinclude:: ../examples/crontab.example

In the above example, change the ``ELGG`` and ``GET`` variables to match you server setup.
If you have SSH access to your Linux servers, type ``crontab -e`` and add
your crontab configuration. If you already have a crontab configured, you will have to
merge Elgg information into it. If you don't have SSH access, you will have to use
a web-based configuration tool. This will vary depending on hosting provider.

If you choose the ``wget`` utility, you might want to consider these flags:

 * ``--output-document`` or ``-O`` to specify the location of the concatenated output file.
   For example, under Debian: ``/usr/bin/wget --output-document=/dev/null``. If you don't do
   that, a new file will be created for each cron page load in the home directory of the cron user.
 * ``--spider`` to prevent the cron page from being downloaded.


On Windows servers, there is a number of cron emulators available.

For information on setting up cron jobs using cPanel see `cPanel Docs`__.

In the ``command`` field, enter the appropriate link of the cron page.
For example, for a weekly cron job, enter the command as http://www.example.com/cron/weekly/.

To see if your cron jobs are running, visit Statistics > Cron in your Elgg admin
panel.

__ http://docs.cpanel.net/twiki/bin/view/AllDocumentation/CpanelDocs/CronJobs


