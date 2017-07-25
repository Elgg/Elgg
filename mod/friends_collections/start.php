<?php

/**
 * Friend collections
 * Provides an interface for users to manage their friend collections
 */

use Elgg\Friends\Collections\CollectionMenuHandler;
use Elgg\Friends\Collections\DeleteRelationshipHandler;
use Elgg\Friends\Collections\EntityMenuHandler;
use Elgg\Friends\Collections\PageMenuHandler;
use Elgg\Friends\Collections\Router;
use Elgg\Friends\Collections\UrlHandler;

elgg_register_event_handler('init', 'system', 'friends_collections_init');

function friends_collections_init() {

	// Setup /collections controller and collection URLs
	elgg_register_page_handler('collections', [Router::class, 'collectionsPageHandler']);
	elgg_register_plugin_hook_handler('access_collection:url', 'access_collection', UrlHandler::class);

	// Add Collections page menu item
	elgg_register_plugin_hook_handler('register', 'menu:page', PageMenuHandler::class);

	// Setup access collection menu
	elgg_register_plugin_hook_handler('register', 'menu:friends:collection', CollectionMenuHandler::class);

	// We are registering this hook with a high priority, because core entity menu setup for users
	// resets the menu array and is registered with a high priority
	elgg_register_plugin_hook_handler('register', 'menu:entity', EntityMenuHandler::class, 1000);

	// Remove users from access collections when friendship is revoked
	elgg_register_event_handler('delete', 'relationship', DeleteRelationshipHandler::class);

	// Add some styling
	elgg_extend_view('elgg.css', 'collections/collections.css');

}
