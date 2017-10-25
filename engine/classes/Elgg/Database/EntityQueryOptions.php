<?php
/**
 *
 */

namespace Elgg\Database;

use Elgg\Config;

/**
 * Wrapper class for manipulating and normalizing ege* options
 *
 * @property mixed type_subtype_pairs
 *
 * @property mixed guids
 * @property mixed owner_guids
 * @property mixed container_guids
 *
 * @property mixed modified_time_lower
 * @property mixed modified_time_upper
 * @property mixed created_time_lower
 * @property mixed created_time_upper
 *
 * @property mixed sort_by
 * @property mixed reverse_order_by
 * @property mixed order_by
 * @property mixed group_by
 * @property mixed count
 * @property mixed limit
 * @property mixed offset
 *
 * @property mixed selects
 * @property mixed wheres
 * @property mixed joins
 *
 * @property mixed metadata_name_value_pairs
 * @property mixed metadata_name_value_pairs_operator
 * @property mixed metadata_case_sensitive
 *
 * @property mixed search_name_value_pairs
 *
 * @property mixed annotation_name_value_pairs
 * @property mixed annotation_name_value_pairs_operator
 * @property mixed annotation_case_sensitive
 * @property mixed order_by_annotation
 * @property mixed annotation_created_time_lower
 * @property mixed annotation_created_time_upper
 * @property mixed annotation_owner_guids
 *
 * @property mixed relationship
 * @property mixed relationship_guid
 * @property mixed inverse_relationship
 * @property mixed relationship_join_on
 * @property mixed relationship_created_time_lower
 * @property mixed relationship_created_time_upper
 *
 * @property mixed preload_owners
 * @property mixed preload_containers
 * @property mixed callback
 * @property mixed distinct
 *
 * @property mixed batch
 * @property mixed batch_inc_offset
 * @property mixed batch_size
 *
 * @property mixed ignore_access
 * @property mixed access_user_guid
 * @property mixed use_enabled_clause
 */
class EntityQueryOptions extends \ArrayObject {

	/**
	 * {@inheritdoc}
	 */
	public function &__get($name) {
		return $this[$name];
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		$this[$name] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __unset($name) {
		unset($this[$name]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __isset($name) {
		return isset($this[$name]);
	}

	/**
	 * Normalize options array
	 *
	 * @param array $options Options
	 *
	 * @return static
	 */
	public static function factory(array $options = []) {
		$options = array_merge(self::defaults(), $options);
		$options = new static($options);

		return $options->normalize();
	}

	/**
	 * Get a normalized instance of options
	 * @return static
	 */
	public function normalize() {
		$options = $this->getArrayCopy();

		_elgg_check_unsupported_site_guid($options);

		$options = self::normalizeAccessOptions($options);
		$options = self::normalizeTypeSubtypeOptions($options);
		$options = self::normalizeGuidOptions($options);
		$options = self::normalizeMetadataOptions($options);
		$options = self::normalizeTimeOptions($options);

		foreach (['selects', 'joins', 'wheres'] as $prop) {
			if (!is_array($options[$prop])) {
				if ($options[$prop]) {
					$options[$prop] = [$options[$prop]];
				} else {
					$options[$prop] = [];
				}
			}
		}

		$this->exchangeArray($options);

		return $this;
	}

	/**
	 * Returns defaults array
	 * @return array
	 */
	public static function defaults() {
		return [
			'types' => ELGG_ENTITIES_ANY_VALUE,
			'subtypes' => ELGG_ENTITIES_ANY_VALUE,
			'type_subtype_pairs' => ELGG_ENTITIES_ANY_VALUE,

			'guids' => ELGG_ENTITIES_ANY_VALUE,
			'owner_guids' => ELGG_ENTITIES_ANY_VALUE,
			'container_guids' => ELGG_ENTITIES_ANY_VALUE,

			'modified_time_lower' => ELGG_ENTITIES_ANY_VALUE,
			'modified_time_upper' => ELGG_ENTITIES_ANY_VALUE,
			'created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
			'created_time_upper' => ELGG_ENTITIES_ANY_VALUE,

			'sort_by' => [],
			'reverse_order_by' => null,
			'order_by' => null,
			'group_by' => ELGG_ENTITIES_ANY_VALUE,
			'count' => false,
			'limit' => elgg_get_config('default_limit'),
			'offset' => 0,

			'selects' => [],
			'wheres' => [],
			'joins' => [],

			'metadata_name_value_pairs' => ELGG_ENTITIES_ANY_VALUE,
			'metadata_name_value_pairs_operator' => 'AND',
			'metadata_case_sensitive' => true,
			'order_by_metadata' => [],

			'search_name_value_pairs' => ELGG_ENTITIES_ANY_VALUE,

			'annotation_names' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_values' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_name_value_pairs' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_name_value_pairs_operator' => 'AND',
			'annotation_case_sensitive' => true,
			'order_by_annotation' => [],
			'annotation_created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_created_time_upper' => ELGG_ENTITIES_ANY_VALUE,
			'annotation_owner_guids' => ELGG_ENTITIES_ANY_VALUE,

			'relationship' => null,
			'relationship_guid' => null,
			'inverse_relationship' => false,
			'relationship_join_on' => 'guid',
			'relationship_created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
			'relationship_created_time_upper' => ELGG_ENTITIES_ANY_VALUE,

			'preload_owners' => false,
			'preload_containers' => false,
			'callback' => 'entity_row_to_elggstar',
			'distinct' => true,

			'batch' => false,
			'batch_inc_offset' => true,
			'batch_size' => 25,

			// private API
			'__ElggBatch' => null,

			'ignore_access' => null,
			'access_user_guid' => null,
			'use_enabled_clause' => null,
		];
	}

	/**
	 * Populate access defaults
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	public static function normalizeAccessOptions(array $options = []) {

		if (!isset($options['access_user_guid'])) {
			$options['access_user_guid'] = elgg_get_logged_in_user_guid();
		}

		if (!isset($options['ignore_access'])) {
			$options['ignore_access'] = _elgg_services()->userCapabilities->canBypassPermissionsCheck($options['access_user_guid']);
		}

		if (!isset($options['use_enabled_clause'])) {
			$options['use_enabled_clause'] = access_get_show_hidden_status();
		}

		return $options;
	}

	/**
	 * Normalizes 'types', 'subtypes' and 'type_subtype_pairs' into
	 * a structured array of 'type_subtype_pairs'
	 *
	 * <code>
	 * [
	 *    'object' => ['blog', 'file'],
	 *    'user' => null,
	 * ]
	 * </code>
	 *
	 * @param array $options Options array
	 *
	 * @return array
	 */
	public static function normalizeTypeSubtypeOptions(array $options = []) {

		$singulars = [
			'type',
			'subtype',
		];

		$options = _elgg_normalize_plural_options_array($options, $singulars);

		// can't use helper function with type_subtype_pair because
		// it's already an array...just need to merge it
		if (isset($options['type_subtype_pair']) && isset($options['type_subtype_pairs'])) {
			$options['type_subtype_pairs'] = array_merge((array) $options['type_subtype_pairs'], (array) $options['type_subtype_pair']);
		} else if (isset($options['type_subtype_pair'])) {
			$options['type_subtype_pairs'] = (array) $options['type_subtype_pair'];
		} else if (isset($options['type_subtype_pairs'])) {
			$options['type_subtype_pairs'] = (array) $options['type_subtype_pairs'];
		} else if (isset($options['types'])) {
			$options['type_subtype_pairs'] = [];
			if ($options['types']) {
				foreach ((array) $options['types'] as $type) {
					$options['type_subtype_pairs'][$type] = isset($options['subtypes']) ? (array) $options['subtypes'] : ELGG_ENTITIES_ANY_VALUE;
				}
			}
		}

		if (is_array($options['type_subtype_pairs'])) {
			foreach ($options['type_subtype_pairs'] as $type => $subtypes) {
				if (!in_array($type, Config::getEntityTypes())) {
					elgg_log("'$type' is not a valid entity type", 'WARNING');
					unset($options['type_subtype_pairs'][$type]);
					continue;
				}
			}
		}

		unset($options['type_subtype_pair']);
		unset($options['types']);
		unset($options['subtypes']);

		return $options;
	}

	/**
	 * Normalizes 'metadata_names', 'metadata_values' and 'metadata_name_value_pairs' into
	 * a structured array of 'metadata_name_value_pairs'
	 *
	 * <code>
	 * [
	 *    [
	 *       'name' => 'status',
	 *       'value' => 'published',
	 *       'operand' => '=',
	 *       'case_sensitive' => true,
	 *       'type' => 'string',
	 *    ]
	 * ]
	 * </code>
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public static function normalizeMetadataOptions(array $options = []) {

		$singulars = [
			'metadata_name',
			'metadata_value',
			'metadata_name_value_pair',
		];

		$options = _elgg_normalize_plural_options_array($options, $singulars);

		$options = self::normalizePairedOptions('metadata', $options);
		$options = self::normalizePairedOptions('search', $options);

		if (isset($options['order_by_metadata'])) {
			$name = elgg_extract('name', $options['order_by_metadata']);
			$direction = strtoupper(elgg_extract('direction', $options['order_by_metadata'], 'asc'));
			$as = elgg_extract('as', $options['order_by_metadata']);

			if ($name) {
				$options['sort_by'][] = [
					'field' => $name,
					'direction' => in_array($direction, ['ASC', 'DESC']) ? $direction : null,
					'signed' => $as === 'integer',
				];
			}

			unset($options['order_by']);
			unset($options['reverse_order_by']);
			unset($options['order_by_metadata']);
		}

		return $options;
	}

	/**
	 * Normalizes name_value_pairs for metadata, annotations and search
	 *
	 * @param string $type    Property type
	 * @param array  $options Options
	 *
	 * @return array
	 */
	public static function normalizePairedOptions($type = 'metadata', array $options = []) {
		if (!is_array($options["{$type}_name_value_pairs"])) {
			$options["{$type}_name_value_pairs"] = [];
		}

		if (isset($options["{$type}_name_value_pairs"]['name'])) {
			$options["{$type}_name_value_pairs"][] = [
				'name' => $options["{$type}_name_value_pairs"]['name'],
				'value' => elgg_extract('value', $options["{$type}_name_value_pairs"]),
				'operand' => elgg_extract('operand', $options["{$type}_name_value_pairs"]),
				'case_sensitive' => elgg_extract('case_sensitive', $options["{$type}_name_value_pairs"])
			];
			unset($options["{$type}_name_value_pairs"]['name']);
			unset($options["{$type}_name_value_pairs"]['value']);
			unset($options["{$type}_name_value_pairs"]['operand']);
			unset($options["{$type}_name_value_pairs"]['case_sensitive']);
		}

		foreach ($options["{$type}_name_value_pairs"] as $key => $value) {
			if (is_array($value) && isset($value['name'])) {
				continue;
			}
			$options["{$type}_name_value_pairs"][$key] = [
				'name' => $key,
				'value' => $value,
			];
		}

		if (isset($options["{$type}_names"])) {
			foreach ((array) $options["{$type}_names"] as $metadata_name) {
				$options["{$type}_name_value_pairs"][] = [
					'name' => $metadata_name,
					'value' => isset($options["{$type}_values"]) ? (array) $options["{$type}_values"] : ELGG_ENTITIES_ANY_VALUE,
				];
			}
		}

		foreach ($options["{$type}_name_value_pairs"] as $key => $value) {
			if (!isset($value['case_sensitive'])) {
				$options["{$type}_name_value_pairs"][$key]['case_sensitive'] = $options["{$type}_case_sensitive"];
			}
			if (!isset($value['type'])) {
				$options["{$type}_name_value_pairs"][$key]['type'] = 'string';
			}
		}

		unset($options["{$type}_names"]);
		unset($options["{$type}_values"]);
		unset($options["{$type}_case_sensitive"]);

		return $options;
	}

	/**
	 * Normalizes guid based options
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	public static function normalizeGuidOptions(array $options = []) {

		$singulars = [
			'guid',
			'owner_guid',
			'container_guid',
			'annotation_owner_guid',
		];

		$options = _elgg_normalize_plural_options_array($options, $singulars);

		$names = [
			'guids',
			'owner_guids',
			'container_guids',
			'annotation_owner_guids',
			'relationship_guid',
		];

		foreach ($names as $name) {
			$guids = self::normalizeGuids($options[$name]);
			$options[$name] = !empty($guids) ? $guids : ELGG_ENTITIES_ANY_VALUE;
		}

		return $options;
	}

	/**
	 * Flatten an array of data into an array of GUIDs
	 *
	 * @param mixed ...$args Elements to normalize
	 *
	 * @return int[]|null
	 */
	public static function normalizeGuids(...$args) {
		if (empty($args)) {
			return ELGG_ENTITIES_ANY_VALUE;
		}

		$guids = [];
		foreach ($args as $arg) {
			if ($arg instanceof \stdClass) {
				$guids[] = (int) $arg->guid;
			} else if (is_array($arg)) {
				foreach ($arg as $a) {
					$el_guids = self::normalizeGuids($a);
					$guids = array_merge($guids, $el_guids);
				}
			} else if (is_numeric($arg)) {
				$guids[] = (int) $arg;
			}
		}

		return array_unique($guids);
	}

	/**
	 * Normalizes time based options
	 *
	 * @param array $options Options array
	 *
	 * @return array
	 */
	public static function normalizeTimeOptions(array $options = []) {

		$props = [
			'modified_time_lower',
			'modified_time_upper',
			'created_time_lower',
			'created_time_upper',
			'annotation_created_time_lower',
			'annotation_created_time_upper',
			'relationship_created_time_lower',
			'relationship_created_time_upper',
		];

		foreach ($props as $prop) {
			$time = $options[$prop];
			if ($time instanceof \DateTime) {
				$options[$prop] = $time->getTimestamp();
			} else if (is_string($time)) {
				$dt = new \DateTime($time);
				$options[$prop] = $dt->getTimestamp();
			} else {
				$options[$prop] = (int) $time;
			}
		}

		return $options;
	}
}
