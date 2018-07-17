<?php

namespace Elgg\Database;

use Closure;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\PrivateSettingWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use ElggEntity;
use InvalidArgumentException;
use InvalidParameterException;

/**
 * Entities repository contains methods for fetching entities from database or performing
 * calculations on entity properties.
 *
 * API IN FLUX Do not access the methods directly, use elgg_get_entities() instead
 *
 * @todo   At a later stage, this class will contain additional shortcut methods to filter entities
 *         by relationship, metdataion, annotation, private settings etc. Until then, such filtering
 *         can be done via standard ege* options
 *
 * @todo   Resolve table alias collissions when querying for both annotationa and metadata name value pairs
 *         Currently, n_table is expected across core and plugins, we need to refactor that code
 *         and remove n_table joins here
 *
 * @access private
 */
class Entities extends Repository {

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		$qb = Select::fromTable('entities', 'e');

		$count_expr = $this->options->distinct ? "DISTINCT e.guid" : "*";
		$qb->select("COUNT({$count_expr}) AS total");

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		if (!$result) {
			return 0;
		}
		
		return (int) $result->total;
	}

	/**
	 * Performs a mathematical calculation on a set of entity properties
	 *
	 * @param string $function      Valid numeric function
	 * @param string $property      Property name
	 * @param string $property_type 'attribute'|'metadata'|'annotation'|'private_setting'
	 *
	 * @return string
	 * @throws InvalidParameterException
	 */
	public function calculate($function, $property, $property_type = null) {

		if (!in_array(strtolower($function), QueryBuilder::$calculations)) {
			throw new InvalidArgumentException("'$function' is not a valid numeric function");
		}

		if (!isset($property_type)) {
			if (in_array($property, ElggEntity::$primary_attr_names)) {
				$property_type = 'attribute';
			} else {
				$property_type = 'metadata';
			}
		}

		$qb = Select::fromTable('entities', 'e');

		switch ($property_type) {
			case 'attribute':
				if (!in_array($property, ElggEntity::$primary_attr_names)) {
					throw new InvalidParameterException("'$property' is not a valid attribute");
				}

				$qb->addSelect("{$function}(e.{$property}) AS calculation");
				break;

			case 'metadata' :
				$alias = $qb->joinMetadataTable('e', 'guid', $property, 'inner', 'n_table');
				$qb->addSelect("{$function}({$alias}.value) AS calculation");
				break;

			case 'annotation' :
				$alias = $qb->joinAnnotationTable('e', 'guid', $property, 'inner', 'n_table');
				$qb->addSelect("{$function}({$alias}.value) AS calculation");
				break;

			case 'private_setting' :
				$alias = $qb->joinPrivateSettingsTable('e', 'guid', $property, 'inner', 'ps');
				$qb->addSelect("{$function}({$alias}.value) AS calculation");
				break;
		}

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		// do not cast data as calculations could be used as int, float or string
		return $result->calculation;
	}

	/**
	 * Fetch entities
	 *
	 * @param int      $limit    Limit
	 * @param int      $offset   Offset
	 * @param callable $callback Custom callback
	 *
	 * @return ElggEntity[]
	 */
	public function get($limit = null, $offset = null, $callback = null) {

		$qb = Select::fromTable('entities', 'e');

		$distinct = $this->options->distinct ? "DISTINCT" : "";
		$qb->select("$distinct e.*");

		$this->expandInto($qb, 'e');

		$qb = $this->buildQuery($qb);

		// Keeping things backwards compatible
		$original_order = elgg_extract('order_by', $this->options->__original_options);
		if (empty($original_order) && $original_order !== false) {
			$qb->addOrderBy('e.time_created', 'desc');
			// also add order by guid, to rely less on internals of MySQL fallback ordering
			$qb->addOrderBy('e.guid', 'desc');
		}

		if ($limit) {
			$qb->setMaxResults((int) $limit);
			$qb->setFirstResult((int) $offset);
		}

		$options = $this->options->getArrayCopy();

		$options['limit'] = (int) $limit;
		$options['offset'] = (int) $offset;
		$options['callback'] = $callback ? : $this->options->callback;
		if (!isset($options['callback'])) {
			$options['callback'] = 'entity_row_to_elggstar';
		}

		unset($options['count']);

		return _elgg_services()->entityTable->fetch($qb, $options);
	}
	
	/**
	 * Returns a list of months in which entities were updated or created.
	 *
	 * @tip     Use this to generate a list of archives by month for when entities were added or updated.
	 *
	 * @warning Months are returned in the form YYYYMM.
	 *
	 * @return array|false Either an array months as YYYYMM, or false on failure
	 */
	public function getDates() {
		
		$qb = Select::fromTable('entities', 'e');

		$qb->select("DISTINCT EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(e.time_created)) AS yearmonth");

		$this->expandInto($qb, 'e');

		$qb = $this->buildQuery($qb);

		$options = $this->options->getArrayCopy();
		unset($options['count']);
		
		$options['callback'] = false;
		
		$results = _elgg_services()->entityTable->fetch($qb, $options);
		
		if (empty($results)) {
			return false;
		}
	
		return array_map(function ($e) {
			return $e->yearmonth;
		}, $results);
	}

	/**
	 * Execute the query resolving calculation, count and/or batch options
	 *
	 * @return array|\ElggData[]|ElggEntity[]|false|int
	 * @throws \LogicException
	 */
	public function execute() {

		if ($this->options->annotation_calculation) {
			$clauses = $this->options->annotation_name_value_pairs;
			if (count($clauses) > 1 && $this->options->annotation_name_value_pairs_operator !== 'OR') {
				throw new \LogicException("Annotation calculation can not be performed on multiple annotation name value pairs merged with AND");
			}

			$clause = array_shift($clauses);

			return $this->calculate($this->options->annotation_calculation, $clause->names, 'annotation');
		} else if ($this->options->metadata_calculation) {
			$clauses = $this->options->metadata_name_value_pairs;
			if (count($clauses) > 1 && $this->options->metadata_name_value_pairs_operator !== 'OR') {
				throw new \LogicException("Metadata calculation can not be performed on multiple metadata name value pairs merged with AND");
			}

			$clause = array_shift($clauses);

			return $this->calculate($this->options->metadata_calculation, $clause->names, 'metadata');
		} else if ($this->options->count) {
			return $this->count();
		} else if ($this->options->batch) {
			return $this->batch($this->options->limit, $this->options->offset, $this->options->callback);
		} else {
			return $this->get($this->options->limit, $this->options->offset, $this->options->callback);
		}
	}

	/**
	 * Build a database query
	 *
	 * @param QueryBuilder $qb
	 *
	 * @return QueryBuilder
	 */
	protected function buildQuery(QueryBuilder $qb) {

		$ands = [];

		foreach ($this->options->joins as $join) {
			$join->prepare($qb, 'e');
		}

		foreach ($this->options->wheres as $where) {
			$ands[] = $where->prepare($qb, 'e');
		}

		$ands[] = $this->buildEntityClause($qb);
		$ands[] = $this->buildPairedMetadataClause($qb, $this->options->metadata_name_value_pairs, $this->options->metadata_name_value_pairs_operator);
		$ands[] = $this->buildPairedMetadataClause($qb, $this->options->search_name_value_pairs, 'OR');
		$ands[] = $this->buildPairedAnnotationClause($qb, $this->options->annotation_name_value_pairs, $this->options->annotation_name_value_pairs_operator);
		$ands[] = $this->buildPairedPrivateSettingsClause($qb, $this->options->private_setting_name_value_pairs, $this->options->private_setting_name_value_pairs_operator);
		$ands[] = $this->buildPairedRelationshipClause($qb, $this->options->relationship_pairs);

		$ands = $qb->merge($ands);

		if (!empty($ands)) {
			$qb->andWhere($ands);
		}

		return $qb;
	}

	/**
	 * Process entity attribute wheres
	 * Applies entity attribute constrains on the selected entities table
	 *
	 * @param QueryBuilder $qb Query builder
	 *
	 * @return Closure|CompositeExpression|mixed|null|string
	 */
	protected function buildEntityClause(QueryBuilder $qb) {
		return EntityWhereClause::factory($this->options)->prepare($qb, 'e');
	}

	/**
	 * Process metadata name value pairs
	 * Joins the metadata table on entity guid in the entities table and applies metadata where clauses
	 *
	 * @param QueryBuilder          $qb      Query builder
	 * @param MetadataWhereClause[] $clauses Where clauses
	 * @param string                $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedMetadataClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];


		foreach ($clauses as $clause) {
			if ($clause instanceof MetadataWhereClause) {
				if (strtoupper($boolean) === 'OR' || count($clauses) === 1) {
					$joined_alias = $qb->joinMetadataTable('e', 'guid', null, 'inner', 'n_table');
				} else {
					$joined_alias = $qb->joinMetadataTable('e', 'guid', $clause->names);
				}
				$parts[] = $clause->prepare($qb, $joined_alias);
			}
		}

		return $qb->merge($parts, $boolean);
	}

	/**
	 * Process annotation name value pairs
	 * Joins the annotation table on entity guid in the entities table and applies annotation where clauses
	 *
	 * @param QueryBuilder            $qb      Query builder
	 * @param AnnotationWhereClause[] $clauses Where clauses
	 * @param string                  $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedAnnotationClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];

		foreach ($clauses as $clause) {
			if (strtoupper($boolean) === 'OR' || count($clauses) === 1) {
				$joined_alias = $qb->joinAnnotationTable('e', 'guid', null, 'inner', 'n_table');
			} else {
				$joined_alias = $qb->joinAnnotationTable('e', 'guid', $clause->names);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $boolean);
	}

	/**
	 * Process private setting name value pairs
	 * Joins the private settings table on entity guid in the entities table and applies private setting where clauses
	 *
	 * @param QueryBuilder                $qb      Query builder
	 * @param PrivateSettingWhereClause[] $clauses Where clauses
	 * @param string                      $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedPrivateSettingsClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];

		foreach ($clauses as $clause) {
			if (strtoupper($boolean) === 'OR' || count($clauses) === 1) {
				$joined_alias = $qb->joinPrivateSettingsTable('e', 'guid', null, 'inner', 'ps');
			} else {
				$joined_alias = $qb->joinPrivateSettingsTable('e', 'guid', $clause->names);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $boolean);
	}

	/**
	 * Process relationship pairs
	 *
	 * @param QueryBuilder              $qb      Query builder
	 * @param RelationshipWhereClause[] $clauses Where clauses
	 * @param string                    $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedRelationshipClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];

		foreach ($clauses as $clause) {
			if (strtoupper($boolean) == 'OR' || count($clauses) === 1) {
				$joined_alias = $qb->joinRelationshipTable('e', $clause->join_on, null, $clause->inverse, 'inner', 'r');
			} else {
				$joined_alias = $qb->joinRelationshipTable('e', $clause->join_on, $clause->names, $clause->inverse);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $boolean);
	}
}
