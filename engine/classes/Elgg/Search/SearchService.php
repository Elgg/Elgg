<?php

namespace Elgg\Search;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Config;
use Elgg\Database;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\AttributeWhereClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\QueryBuilder;
use Elgg\EventsService;
use Elgg\Exceptions\DomainException;
use Elgg\Traits\Database\LegacyQueryOptionsAdapter;

/**
 * Search service
 *
 * @internal
 * @since 3.0
 */
class SearchService {

	use LegacyQueryOptionsAdapter;
	
	/**
	 * Constructor
	 *
	 * @param \Elgg\Config        $config Config
	 * @param \Elgg\EventsService $events Events service
	 * @param Database            $db     Database
	 */
	public function __construct(
		protected Config $config,
		protected EventsService $events,
		protected Database $db
	) {
	}

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
	 * @option array  $fields        An array of fields to search in
	 * @option string $sort          An array containing 'property', 'property_type', 'direction' and 'signed'
	 * @option bool   $partial_match Allow partial matches, e.g. find 'elgg' when search for 'el'
	 * @option bool   $tokenize      Break down search query into tokens,
	 *                               e.g. find 'elgg has been released' when searching for 'elgg released'
	 *
	 * @return \ElggBatch|\ElggEntity[]|int|false
	 * @throws DomainException
	 *
	 * @see elgg_get_entities()
	 */
	public function search(array $options = []) {
		$options = $this->prepareSearchOptions($options);

		$query_parts = elgg_extract('query_parts', $options);
		$fields = (array) elgg_extract('fields', $options);

		if (empty($query_parts) || empty(array_filter($fields))) {
			return false;
		}

		$entity_type = elgg_extract('type', $options, 'all', false);
		$entity_subtype = elgg_extract('subtype', $options);
		$search_type = elgg_extract('search_type', $options, 'entities');

		if ($entity_type !== 'all' && !in_array($entity_type, Config::ENTITY_TYPES)) {
			throw new DomainException("'{$entity_type}' is not a valid entity type");
		}

		$options = $this->events->triggerResults('search:options', $entity_type, $options, $options);
		if (!empty($entity_subtype) && is_string($entity_subtype)) {
			$options = $this->events->triggerResults('search:options', "{$entity_type}:{$entity_subtype}", $options, $options);
		}

		$options = $this->events->triggerResults('search:options', $search_type, $options, $options);

		if ($this->events->hasHandler('search:results', $search_type)) {
			$results = $this->events->triggerResults('search:results', $search_type, $options);
			if (isset($results)) {
				// allow events to conditionally replace the result set
				return $results;
			}
		}

		return elgg_get_entities($options);
	}

	/**
	 * Normalize options
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	public function normalizeOptions(array $options = []) {
		
		if (elgg_extract('_elgg_search_service_normalize_options', $options)) {
			// already normalized once before
			return $options;
		}
		
		$search_type = elgg_extract('search_type', $options, 'entities', false);
		$options['search_type'] = $search_type;

		$options = $this->events->triggerResults('search:params', $search_type, $options, $options);

		$options = $this->normalizeQuery($options);
		$options = $this->normalizeSearchFields($options);

		// prevent duplicate normalization
		$options['_elgg_search_service_normalize_options'] = true;
		
		return $options;
	}

	/**
	 * Prepare ege* options
	 *
	 * @param array $options Entity search params
	 *
	 * @return array
	 */
	public function prepareSearchOptions(array $options = []) {
		$options = $this->normalizeOptions($options);

		$fields = elgg_extract('fields', $options);
		$query_parts = elgg_extract('query_parts', $options);
		$partial = elgg_extract('partial_match', $options, true);

		$options['wheres']['search'] = function (QueryBuilder $qb, $alias) use ($fields, $query_parts, $partial) {
			return $this->buildSearchWhereQuery($qb, $alias, $fields, $query_parts, $partial);
		};

		return $options;
	}

	/**
	 * Normalize query parts
	 *
	 * @param array $options Options
	 *
	 * @return array
	 */
	public function normalizeQuery(array $options = []) {

		$query = elgg_extract('query', $options, '');
		$query = strip_tags($query);
		$query = htmlspecialchars($query, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8', false);
		$query = trim($query);

		$words = preg_split('/\s+/', $query);
		$words = array_map(function ($e) {
			return trim($e);
		}, $words);

		$query = implode(' ', $words);

		$options['query'] = $query;

		$tokenize = elgg_extract('tokenize', $options, true);
		if ($tokenize) {
			$parts = $words;
		} else {
			$parts = [$query];
		}

		$options['query_parts'] = array_unique(array_filter($parts));

		return $options;
	}

	/**
	 * Normalizes an array of search fields
	 *
	 * @param array $options Search parameters
	 *
	 * @return array
	 */
	public function normalizeSearchFields(array $options = []) {

		$default_fields = [
			'attributes' => [],
			'metadata' => [],
			'annotations' => [],
		];

		$fields = $default_fields;

		$clean_field_property_types = function ($new_fields) use ($default_fields) {
			$property_types = array_keys($default_fields);
			foreach ($property_types as $property_type) {
				if (empty($new_fields[$property_type])) {
					$new_fields[$property_type] = [];
				} else {
					$new_fields[$property_type] = array_unique($new_fields[$property_type]);
				}
			}
			
			return $new_fields;
		};

		$merge_fields = function ($new_fields) use (&$fields, $clean_field_property_types) {
			if (empty($new_fields) || !is_array($new_fields)) {
				return;
			}
			
			$new_fields = $clean_field_property_types($new_fields);
			
			$fields = array_merge_recursive($fields, $new_fields);
		};

		// normalize type/subtype to support all combinations
		$normalized_options = $this->normalizeTypeSubtypeOptions($options);

		$type_subtype_pairs = elgg_extract('type_subtype_pairs', $normalized_options);
		if (!empty($type_subtype_pairs)) {
			foreach ($type_subtype_pairs as $entity_type => $entity_subtypes) {
				$result = $this->events->triggerResults('search:fields', $entity_type, $options, $default_fields);
				$merge_fields($result);
				
				if (elgg_is_empty($entity_subtypes)) {
					continue;
				}
				
				foreach ($entity_subtypes as $entity_subtype) {
					$result = $this->events->triggerResults('search:fields', "{$entity_type}:{$entity_subtype}", $options, $default_fields);
					$merge_fields($result);
				}
			}
		}

		// search fields for search type
		$search_type = elgg_extract('search_type', $options, 'entities');
		if ($search_type) {
			$fields = $this->events->triggerResults('search:fields', $search_type, $options, $fields);
		}

		// make sure all supported field types are available
		$fields = $clean_field_property_types($fields);

		if (empty($options['fields'])) {
			$options['fields'] = $fields;
		} else {
			// only allow known fields
			foreach ($fields as $property_type => $property_type_fields) {
				if (empty($options['fields'][$property_type])) {
					$options['fields'][$property_type] = [];
					continue;
				}

				$allowed = array_intersect($property_type_fields, (array) $options['fields'][$property_type]);
				$options['fields'][$property_type] = array_values(array_unique($allowed));
			}
		}

		return $options;
	}

	/**
	 * Builds search clause
	 *
	 * @param QueryBuilder $qb            Query builder
	 * @param string       $alias         Entity table alias
	 * @param array        $fields        Fields to match against
	 * @param array        $query_parts   Search query
	 * @param bool         $partial_match Allow partial matches
	 *
	 * @return CompositeExpression|string
	 */
	public function buildSearchWhereQuery(QueryBuilder $qb, $alias, $fields, $query_parts, $partial_match = true) {

		$attributes = elgg_extract('attributes', $fields, [], false);
		$metadata = elgg_extract('metadata', $fields, [], false);
		$annotations = elgg_extract('annotations', $fields, [], false);

		$ors = [];

		$populate_where = function ($where, $part) use ($partial_match) {
			$where->values = $partial_match ? "%{$part}%" : $part;
			$where->comparison = 'LIKE';
			$where->value_type = ELGG_VALUE_STRING;
			$where->case_sensitive = false;
		};

		if (!empty($attributes)) {
			foreach ($attributes as $attribute) {
				$attribute_ands = [];
				foreach ($query_parts as $part) {
					$where = new AttributeWhereClause();
					$where->names = $attribute;
					$populate_where($where, $part);
					$attribute_ands[] = $where->prepare($qb, $alias);
				}
				
				$ors[] = $qb->merge($attribute_ands, 'AND');
			}
		}

		if (!empty($metadata)) {
			$metadata_ands = [];
			$md_alias = $qb->joinMetadataTable($alias, 'guid', $metadata, 'left');
			foreach ($query_parts as $part) {
				$where = new MetadataWhereClause();
				$populate_where($where, $part);
				$metadata_ands[] = $where->prepare($qb, $md_alias);
			}
			
			$ors[] = $qb->merge($metadata_ands, 'AND');
		}

		if (!empty($annotations)) {
			$annotations_ands = [];
			$an_alias = $qb->joinAnnotationTable($alias, 'guid', $annotations, 'left');
			foreach ($query_parts as $part) {
				$where = new AnnotationWhereClause();
				$populate_where($where, $part);
				$annotations_ands[] = $where->prepare($qb, $an_alias);
			}
			
			$ors[] = $qb->merge($annotations_ands, 'AND');
		}

		return $qb->merge($ors, 'OR');
	}
}
