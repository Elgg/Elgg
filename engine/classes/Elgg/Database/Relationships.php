<?php

namespace Elgg\Database;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\PrivateSettingWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\InvalidArgumentException;

/**
 * Relationships repository contains methods for fetching relationships from database or performing
 * calculations on relationship properties.
 *
 * @since 3.2
 * @internal
 */
class Relationships extends Repository {

	/**
	 * {@inheritDoc}
	 * @throws InvalidParameterException;
	 */
	public function calculate($function, $property, $property_type = null) {
		
		if (!in_array(strtolower($function), QueryBuilder::$calculations)) {
			throw new InvalidArgumentException("'$function' is not a valid numeric function");
		}
		
		if (!isset($property_type)) {
			if (in_array($property, \ElggEntity::PRIMARY_ATTR_NAMES)) {
				$property_type = 'attribute';
			} else {
				$property_type = 'metadata';
			}
		}
		
		$join_column = $this->getJoinColumn();
		
		$select = Select::fromTable('entity_relationships', 'er');
		
		switch ($property_type) {
			case 'attribute':
				if (!in_array($property, \ElggEntity::PRIMARY_ATTR_NAMES)) {
					throw new InvalidParameterException("'$property' is not a valid attribute");
				}
				
				$alias = $select->joinEntitiesTable('er', $join_column, 'inner', 'e');
				$select->select("{$function}({$alias}.{$property}) AS calculation");
				break;
				
			case 'annotation' :
				$alias = 'n_table';
				if (!empty($this->options->annotation_name_value_pairs) && $this->options->annotation_name_value_pairs[0]->names != $property) {
					$alias = $select->joinAnnotationTable('er', $join_column, $property);
				}
				$select->select("{$function}($alias.value) AS calculation");
				break;
				
			case 'metadata' :
				$alias = $select->joinMetadataTable('er', $join_column, $property);
				$select->select("{$function}({$alias}.value) AS calculation");
				break;
				
			case 'private_setting' :
				$alias = $select->joinPrivateSettingsTable('er', $join_column, $property);
				$select->select("{$function}({$alias}.value) AS calculation");
				break;
		}
		
		$select = $this->buildQuery($select);
		
		$result = _elgg_services()->db->getDataRow($select);
		if (empty($result)) {
			return 0;
		}
		
		return (int) $result->calculation;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\Database\QueryExecuting::count()
	 */
	public function count() {
		$select = Select::fromTable('entity_relationships', 'er');
		
		$count_expr = $this->options->distinct ? "DISTINCT er.id" : "*";
		$select->select("COUNT({$count_expr}) AS total");
		
		$select = $this->buildQuery($select);
		
		$result = _elgg_services()->db->getDataRow($select);
		if (empty($result)) {
			return 0;
		}
		
		return (int) $result->total;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function execute() {
		
		if ($this->options->annotation_calculation) {
			$clauses = $this->options->annotation_name_value_pairs;
			if (count($clauses) > 1 && $this->options->annotation_name_value_pairs_operator !== 'OR') {
				throw new \LogicException("Annotation calculation can not be performed on multiple annotation name value pairs merged with AND");
			}
			
			$clause = array_shift($clauses);
			
			return $this->calculate($this->options->annotation_calculation, $clause->names, 'annotation');
		} elseif ($this->options->metadata_calculation) {
			$clauses = $this->options->metadata_name_value_pairs;
			if (count($clauses) > 1 && $this->options->metadata_name_value_pairs_operator !== 'OR') {
				throw new \LogicException("Metadata calculation can not be performed on multiple metadata name value pairs merged with AND");
			}
			
			$clause = array_shift($clauses);
			
			return $this->calculate($this->options->metadata_calculation, $clause->names, 'metadata');
		} elseif ($this->options->count) {
			return $this->count();
		} elseif ($this->options->batch) {
			return $this->batch($this->options->limit, $this->options->offset, $this->options->callback);
		} else {
			return $this->get($this->options->limit, $this->options->offset, $this->options->callback);
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get($limit = null, $offset = null, $callback = null) {
		$select = Select::fromTable('entity_relationships', 'er');
		
		$distinct = $this->options->distinct ? 'DISTINCT' : '';
		$select->select("$distinct er.*");
		
		$this->expandInto($select, 'er');
		
		$select = $this->buildQuery($select);
		
		// Keeping things backwards compatible
		$original_order = elgg_extract('order_by', $this->options->__original_options);
		if (empty($original_order) && $original_order !== false) {
			$select->addOrderBy('er.time_created', 'desc');
			$select->addOrderBy('er.id', 'desc');
		}
		
		if ($limit > 0) {
			$select->setMaxResults((int) $limit);
			$select->setFirstResult((int) $offset);
		}
		
		$callback = $callback ? : $this->options->callback;
		if (!isset($callback)) {
			$callback = function ($row) {
				return new \ElggRelationship($row);
			};
		}
		
		$results = _elgg_services()->db->getData($select, $callback);
		if (!empty($results)) {
			$preload = array_filter($results, function($e) {
				return $e instanceof \ElggRelationship;
			});
			
			_elgg_services()->entityPreloader->preload($preload, [
				'guid_one',
				'guid_two',
			]);
		}
		
		return $results;
	}
	
	/**
	 * Build a database query
	 *
	 * @param QueryBuilder $qb Querybuilder with relationship params
	 *
	 * @return QueryBuilder
	 */
	protected function buildQuery(QueryBuilder $qb) {
		$ands = [];
		
		foreach ($this->options->joins as $join) {
			$join->prepare($qb, 'er');
		}
		
		foreach ($this->options->wheres as $where) {
			$ands[] = $where->prepare($qb, 'er');
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
	 * Joins entities table on guid_one|guid_two in relationships table and applies where clauses
	 *
	 * @param QueryBuilder $qb Query builder
	 *
	 * @return \Closure|CompositeExpression|mixed|null|string
	 */
	protected function buildEntityClause(QueryBuilder $qb) {
		$joined_alias = $qb->joinEntitiesTable('er', $this->getJoinColumn(), 'inner', 'e');
		return EntityWhereClause::factory($this->options)->prepare($qb, $joined_alias);
	}
	
	/**
	 * Process metadata name value pairs
	 * Joins the metadata table on guid_one|guid_two in relationships table and applies metadata where clauses
	 *
	 * @param QueryBuilder          $qb      Query builder
	 * @param MetadataWhereClause[] $clauses Where clauses
	 * @param string                $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedMetadataClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];
		
		$join_column = $this->getJoinColumn();
		
		foreach ($clauses as $clause) {
			if ($clause instanceof MetadataWhereClause) {
				if (strtoupper($boolean) === 'OR' || count($clauses) === 1) {
					$joined_alias = $qb->joinMetadataTable('er', $join_column, null, 'inner', 'md');
				} else {
					$joined_alias = $qb->joinMetadataTable('er', $join_column, $clause->names);
				}
				$parts[] = $clause->prepare($qb, $joined_alias);
			}
		}
		
		return $qb->merge($parts, $boolean);
	}
	
	/**
	 * Process annotation name value pairs
	 * Joins the annotation table on guid_one|guid_two in relationships table and applies annotation where clauses
	 *
	 * @param QueryBuilder            $qb      Query builder
	 * @param AnnotationWhereClause[] $clauses Where clauses
	 * @param string                  $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedAnnotationClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];
		
		$join_column = $this->getJoinColumn();
		
		foreach ($clauses as $clause) {
			if (strtoupper($boolean) === 'OR' || count($clauses) === 1) {
				$joined_alias = $qb->joinAnnotationTable('er', $join_column, null, 'inner', 'an');
			} else {
				$joined_alias = $qb->joinAnnotationTable('er', $join_column, $clause->names);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}
		
		return $qb->merge($parts, $boolean);
	}
	
	/**
	 * Process private setting name value pairs
	 * Joins the private settings table on guid_one|guid_two in relationships table and applies private setting where clauses
	 *
	 * @param QueryBuilder                $qb      Query builder
	 * @param PrivateSettingWhereClause[] $clauses Where clauses
	 * @param string                      $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedPrivateSettingsClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];
		
		$join_column = $this->getJoinColumn();
		
		foreach ($clauses as $clause) {
			if (strtoupper($boolean) === 'OR' || count($clauses) === 1) {
				$joined_alias = $qb->joinPrivateSettingsTable('er', $join_column, null, 'inner', 'ps');
			} else {
				$joined_alias = $qb->joinPrivateSettingsTable('er', $join_column, $clause->names);
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
			$parts[] = $clause->prepare($qb, 'er');
		}
		
		return $qb->merge($parts, $boolean);
	}
	
	/**
	 * Return the base column to use in joins
	 *
	 * @return string
	 */
	protected function getJoinColumn() {
		if (!empty($this->options->inverse_relationship)) {
			return 'guid_two';
		}
		
		return 'guid_one';
	}
}
