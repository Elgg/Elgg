<?php

/**
 * Normalizes metadata / annotation option names to their corresponding metastrings name.
 *
 * @param array $options An options array
 * @return array
 * @access private
 */
function _elgg_normalize_metastrings_options(array $options = []) {

	// support either metastrings_type or metastring_type
	// because I've made this mistake many times and hunting it down is a pain...
	$type = elgg_extract('metastring_type', $options, null);
	$type = elgg_extract('metastrings_type', $options, $type);

	$options['metastring_type'] = $type;

	// support annotation_ and annotations_ because they're way too easy to confuse
	$prefixes = ['metadata_', 'annotation_', 'annotations_'];

	// map the metadata_* options to metastring_* options
	$map = [
		'names'                 => 'metastring_names',
		'values'                => 'metastring_values',
		'case_sensitive'        => 'metastring_case_sensitive',
		'owner_guids'           => 'metastring_owner_guids',
		'created_time_lower'    => 'metastring_created_time_lower',
		'created_time_upper'    => 'metastring_created_time_upper',
		'calculation'           => 'metastring_calculation',
		'ids'                   => 'metastring_ids',
	];

	foreach ($prefixes as $prefix) {
		$singulars = ["{$prefix}name", "{$prefix}value", "{$prefix}owner_guid", "{$prefix}id"];
		$options = _elgg_normalize_plural_options_array($options, $singulars);

		foreach ($map as $specific => $normalized) {
			$key = $prefix . $specific;
			if (isset($options[$key])) {
				$options[$normalized] = $options[$key];
			}
		}
	}

	return $options;
}

/**
 * Metastring unit tests
 *
 * @param string $hook  unit_test
 * @param string $type  system
 * @param array  $value Array of other tests
 *
 * @return array
 * @access private
 */
function _elgg_metastrings_test($hook, $type, $value) {
	$value[] = ElggCoreMetastringsTest::class;
	return $value;
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_metastrings_test');
};
