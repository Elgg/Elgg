<?php
/**
 * Basic Elgg search hooks.
 */

/**
 * Returns search results as an array of entities, as a batch, or a count,
 * depending on parameters given.
 *
 * @param array $options Search parameters
 *                       Accepts all options supported by {@link elgg_get_entities()}
 *
 * @option string $query         Search query
 * @option string $type          Entity type. Required if no search type is set
 * @option string $search_type   Custom search type. Required if no type is set
 * @option array  $fields        An array of fields to search in, supported keys are
 * 		[
 * 			'attributes' => ['some attribute', 'some other attribute'],
 *	 		'metadata' => ['some metadata name', 'some other metadata name'],
 * 			'annotations' => ['some annotation name', 'some other annotation name'],
 * 			'private_settings' => ['some private_setting name', 'some other private_setting name'],
 * 		]
 * @option string $sort          An array containing 'property', 'property_type', 'direction' and 'signed'
 * @option bool   $partial_match Allow partial matches, e.g. find 'elgg' when search for 'el'
 * @option bool   $tokenize      Break down search query into tokens,
 *                               e.g. find 'elgg has been released' when searching for 'elgg released'
 *
 * @return ElggBatch|ElggEntity[]|int|false
 *
 * @see elgg_get_entities()
 */
function elgg_search(array $options = []) {
	try {
		return _elgg_services()->search->search($options);
	} catch (InvalidParameterException $e) {
		return false;
	}
}

/**
 * Initializes default search hooks
 * @return void
 * @since 3.0
 */
function _elgg_search_init() {
	elgg_register_plugin_hook_handler('search:fields', 'group', \Elgg\Search\GroupSearchFieldsHandler::class);
	elgg_register_plugin_hook_handler('search:fields', 'group', \Elgg\Search\TagsSearchFieldsHandler::class);
	elgg_register_plugin_hook_handler('search:fields', 'object', \Elgg\Search\ObjectSearchFieldsHandler::class);
	elgg_register_plugin_hook_handler('search:fields', 'object', \Elgg\Search\TagsSearchFieldsHandler::class);
	elgg_register_plugin_hook_handler('search:fields', 'user', \Elgg\Search\UserSearchFieldsHandler::class);
	elgg_register_plugin_hook_handler('search:fields', 'user', \Elgg\Search\TagsSearchFieldsHandler::class);
}

return function (\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_search_init');
};
