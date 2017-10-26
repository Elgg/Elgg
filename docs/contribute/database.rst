Database
########

Contributing database schema changes

.. contents:: Contents
   :local:
   :depth: 1

Database Migrations
===================

Elgg uses `Phinx`_ to manage the database migrations.

.. _Phinx: https://phinx.org/


Create a migration
------------------

To create a new migration run the following in your console:

.. code-block:: sh

    phinx create -c engine/conf/migrations.php MigrationClassName


This will generate a timestamped skeleton migration in ``engine/schema/migrations/``. Follow Phinx documentation to call
the necessary methods to modify the database tables.


Executing a migration
---------------------

Migrations are executed every time your run ``upgrade.php``. If you would like to execute migrations manually, you can
do so via the command line:

.. code-block:: sh

    phinx migrate -c engine/conf/migrations.php

Check Phinx documentation for additional flags that allow you to run a single migration or a set of migrations within a
time range.
