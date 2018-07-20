Elgg CLI
########

.. contents:: Contents
   :local:
   :depth: 1


elgg-cli command line tools
===========================

Depending on how you installed Elgg and your server configuration you can access``elgg-cli`` binaries as one of the following from the root of your Elgg installation:

.. code-block:: sh

    php ./elgg-cli list
    ./elgg-cli list
    php ./vendor/bin/elgg-cli list
    ./vendor/bin/elgg-cli list


Available commands
==================

.. code-block:: sh

    cd /path/to/elgg/

    # Get help
    vendor/bin/elgg-cli --help

    # List all commands
    vendor/bin/elgg-cli list

    # Install Elgg
    vendor/bin/elgg-cli install [-c|--config CONFIG]

    # Run Simpletest test suite
    vendor/bin/elgg-cli simpletest [-c|--config CONFIG] [-p|--plugins PLUGINS] [-f|--filter FILTER]

    # Seed the database with fake entities
    vendor/bin/elgg-cli database:seed [-l|--limit LIMIT]

    # Remove seeded faked entities
    vendor/bin/elgg-cli database:unseed

    # Optimize database tables
    # Request garbagecollector plugin
    vendor/bin/elgg-cli database:optimize

    # Run cron jobs
    vendor/bin/elgg-cli cron [-i|--interval INTERVAL] [-q|--quiet]

    # Flush caches
    vendor/bin/elgg-cli flush

    # System upgrade
    # -v|-vv|-vvv control verbosity of the command (helpful for debugging upgrade scripts)
    vendor/bin/elgg-cli upgrade [-v]

    # Upgrade and execute all async upgrades
    vendor/bin/elgg-cli upgrade async [-v]

    # List all, active or inactive plugins
    # STATUS = all | active | inactive
    vendor/bin/elgg-cli plugins:list [-s|--status STATUS]

    # Activate plugins
    # List plugin ids separating them with spaces: vendor/bin/elgg-cli plugins:activate activity blog
    # use -f flag to resolve conflicts and dependencies
    vendor/bin/elgg-cli plugins:activate [<plugins>] [-f|--force]

    # Deactivate plugins
    # List plugin ids separating them with spaces: vendor/bin/elgg-cli plugins:deactivate activity blog
    # use -f flag to also disable dependents
    vendor/bin/elgg-cli plugins:deactivate [<plugins>] [-f|--force]


Adding custom commands
======================

Plugins can add their commands to the CLI application, by adding command class name via ``'commands','cli'`` hook.
Command class must extend ``\Elgg\CLI\Command``.

.. code-block:: php

    class MyCommand extends \Elgg\Сli\Command {

    }

    elgg_register_plugin_hook_handler('commands', 'cli', function($hook, $type, $return) {

        $return[] = MyCommand::class;

        return $return;

    });
