<?php
/**
 * Elgg tags
 * Functions for managing tags and tag clouds.
 *
 * @package Elgg.Core
 * @subpackage Tags
 */

/**
 * Takes in a comma-separated string and returns an array of tags
 * which have been trimmed
 *
 * @param string $string Comma-separated tag string
 *
 * @return mixed An array of strings or the original data if input was not a string
 */
function string_to_tag_array($string) {
	if (!is_string($string)) {
		return $string;
	}
	
	$ar = explode(",", $string);
	$ar = array_map('trim', $ar);
	$ar = array_filter($ar, 'is_not_null');
	$ar = array_map('strip_tags', $ar);
	$ar = array_unique($ar);
	return $ar;
}

/**
 * Get popular tags and their frequencies
 *
 * Accepts all options supported by {@link elgg_get_entities()}
 *
 * Returns an array of objects that include "tag" and "total" properties
 *
 * @todo When updating this function for 3.0, I have noticed that docs explicitly mention
 *       that tags must be registered, but it was not really checked anywhere in code
 *       So, either update the docs or decide what the behavior should be
 *
 * @param array $options Options
 *
 * @option int      $threshold Minimum number of tag occurrences
 * @option string[] $tag_names Names of registered tag names to include in search
 *
 * @return 	object[]|false
 * @since 1.7.1
 */
function elgg_get_tags(array $options = []) {
	$defaults = [
		'threshold' => 1,
		'tag_names' => [],
	];

	$options = array_merge($defaults, $options);

	$singulars = ['tag_name'];
	$options = _elgg_normalize_plural_options_array($options, $singulars);

	$tag_names = elgg_extract('tag_names', $options);
	if (empty($tag_names)) {
		$tag_names = elgg_get_registered_tag_metadata_names();
	}

	$threshold = elgg_extract('threshold', $options, 1, false);

	unset($options['tag_names']);
	unset($options['threshold']);

	$qb = \Elgg\Database\Select::fromTable('metadata', 'md');
	$qb->select('md.value AS tag')
		->addSelect('COUNT(md.id) AS total')
		->where($qb->compare('md.name', 'IN', $tag_names, ELGG_VALUE_STRING))
		->andWhere($qb->compare('md.value', '!=', '', ELGG_VALUE_STRING))
		->groupBy('md.value')
		->having($qb->compare('total', '>=', $threshold, ELGG_VALUE_INTEGER))
		->orderBy('total', 'desc');

	$options = new \Elgg\Database\QueryOptions($options);
	$alias = $qb->joinEntitiesTable('md', 'entity_guid', 'inner', 'e');
	$qb->addClause(\Elgg\Database\Clauses\EntityWhereClause::factory($options), $alias);

	return _elgg_services()->db->getData($qb);
}

/**
 * Registers a metadata name as containing tags for an entity.
 * This is required if you are using a non-standard metadata name
 * for your tags.
 *
 * Because tags are simply names of metadata, This is used
 * in search to prevent data exposure by searching on
 * arbitrary metadata.
 *
 * @param string $name Tag name
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_register_tag_metadata_name($name) {
	return _elgg_services()->metadataTable->registerTagName($name);
}

/**
 * Unregister metadata tag name
 *
 * @param string $name Tag name
 *
 * @return bool
 * @since 3.0
 */
function elgg_unregister_tag_metadata_name($name) {
	return _elgg_services()->metadataTable->unregisterTagName($name);
}

/**
 * Returns an array of valid metadata names for tags.
 *
 * @return string[]
 * @since 1.7.0
 */
function elgg_get_registered_tag_metadata_names() {
	return _elgg_services()->metadataTable->getTagNames();
}

/**
 * Tags init
 *
 * @return void
 *
 * @access private
 */
function _elgg_tags_init() {
	// register the standard tags metadata name
	elgg_register_tag_metadata_name('tags');
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_tags_init');
};
