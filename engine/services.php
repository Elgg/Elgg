<?php

use DI\Factory\RequestedEntry;

return [
	'accounts' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'config' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'csrf' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'db' => function(RequestedEntry $entry) { return _elgg_services()->publicDb; },
	'events' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'fields' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'gatekeeper' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'group_tools' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'html_formatter' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'hooks' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'locale' => function(RequestedEntry $entry) { return _elgg_services()->localeService; },
	'logger' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'menus' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'mimetype' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'session' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'system_messages' => function(RequestedEntry $entry) { return _elgg_services()->systemMessages; },
	'table_columns' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
	'translator' => function(RequestedEntry $entry) { return _elgg_services()->{$entry->getName()}; },
];
