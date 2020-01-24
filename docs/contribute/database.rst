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

    vendor/bin/phinx create -c engine/conf/migrations.php MigrationClassName


This will generate a timestamped skeleton migration in ``engine/schema/migrations/``. Follow Phinx documentation to call
the necessary methods to modify the database tables.

.. _contribute/database#execute-migration:

Executing a migration
---------------------

Migrations are executed every time your run ``upgrade.php``. If you would like to execute migrations manually, you can
do so via the command line:

.. code-block:: sh

    // When Elgg is the root project
    vendor/bin/phinx migrate -c engine/conf/migrations.php
    
    // When Elgg is installed as a Composer dependency
    vendor/bin/phinx migrate -c vendor/elgg/elgg/engine/conf/migrations.php

Check Phinx documentation for additional flags that allow you to run a single migration or a set of migrations within a
time range.
