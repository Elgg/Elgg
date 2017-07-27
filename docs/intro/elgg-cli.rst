Elgg CLI
########

elgg-cli command line tools
===========================

Depending on how your installed Elgg and your server configuration you can access``elgg-cli`` binaries as one of the following from the root of your Elgg (or starter project):

..code::sh

    php ./elgg-cli list
    ./elgg-cli list
    php ./vendor/bin/elgg-cli list
    ./vendor/bin/elgg-cli list


Available commands
==================

..code::sh

    cd /path/to/elgg/
    
    # Get help
    elgg-cli --help
    
    # List all commands
    elgg-cli list
    
    # Install Elgg
    elgg-cli install
    
    # Flush caches
    elgg-cli site:flush_cache
    
    # Run upgrades
    elgg-cli site:upgrade
    
    # Activate plugins
    elgg-cli plugins:activate [--all]
    
    # Deactivate plugins
    elgg-cli plugins:deactivate [--all]
    
    # Add a new user
    elgg-cli user:add [--admin] [--notify]
    
    # Display or change site URL
    elgg-cli site:url <new_url>
    
    # Display or change root path
    elgg-cli config:path <new_path>
    
    # Display or change data directory path
    elgg-cli config:dataroot <new_path>
    
    # Request a page
    elgg-cli route <uri> <method> [--tokens] [--export] [--as]
    
    # Execute an action
    elgg-cli action <action_name> [--as]
    
    # Run cron
    elgg-cli route cron/minute
    elgg-cli route cron/hourly
    # etc

    # List/search entities
    # Keyword search is only available if search plugin is enabled
    elgg-cli entities:get [--guid] [--type] [--subtype] [--limit] [--offset] [--keyword] [--full-view] [--as]


``--as`` flag is available to all commands, accpets a username and allows you to run a specific command as a logged in user.


Adding custom commands
======================

Plugins can add their commands to the CLI application, by adding command class name via ``'commands','cli'`` hook.
Command class must extend ``\Symfony\Component\Console\Command\Command`` or ``\Elgg\CLI\Command``.

..code::php

    class MyCommand extends \Symfony\Component\Console\Command\Command {}

    elgg_register_plugin_hook_handler('commands', 'cli', function($hook, $type, $return) {
        $return[] = MyCommand::class;
        return $return;
    });