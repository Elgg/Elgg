<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\InvalidParameterException;
use ElggEntity;

/**
 * Extends QueryBuilder with clauses necesary to sort entity lists by entity properties
 */
class EntitySortByClause extends OrderByClause {

	/**
	 * @var string
	 */
	public $property;

	/**
	 * @var bool
	 */
	public $signed;

	/**
	 * @var string
	 */
	public $join_type;

	/**
	 * @var string
	 */
	public $property_type;
	
	/**
	 * @var bool
	 */
	public $inverse_relationship;

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {

		if (!isset($this->property_type)) {
			if (in_array($this->property, ElggEntity::PRIMARY_ATTR_NAMES)) {
				$this->property_type = 'attribute';
			} else {
				$this->property_type = 'metadata';
			}
		}
		
		// get correct base GUID column
		// default assumes the main table is 'entities'
		$from_column = 'guid';
		if ($qb->getTableName() === QueryBuilder::TABLE_RELATIONSHIPS) {
			$from_column = 'guid_one';
			if ((bool) $this->inverse_relationship) {
				$from_column = 'guid_two';
			}
		}

		switch ($this->property_type) {
			case 'metadata':
				$md_alias = $qb->joinMetadataTable($table_alias, $from_column, $this->property, $this->join_type);
				$column = "{$md_alias}.value";
				break;

			case 'attribute':
				if (!in_array($this->property, ElggEntity::PRIMARY_ATTR_NAMES)) {
					throw new InvalidParameterException("'{$this->property}' is not a valid entity attribute");
				}
				
				if ($qb->getTableName() !== QueryBuilder::TABLE_ENTITIES) {
					$e_alias = $qb->joinEntitiesTable($table_alias, $from_column, $this->join_type);
				} else {
					$e_alias = $table_alias;
				}
				
				$column = "{$e_alias}.{$this->property}";
				break;

			case 'private_setting':
				$ps_alias = $qb->joinPrivateSettingsTable($table_alias, $from_column, $this->property, $this->join_type);
				$column = "{$ps_alias}.value";
				break;

			case 'annotation':
				$an_alias = $qb->joinAnnotationTable($table_alias, $from_column, $this->property, $this->join_type);
				$column = "{$an_alias}.value";
				break;

			case 'relationship':
				if ($qb->getTableName() !== QueryBuilder::TABLE_RELATIONSHIPS) {
					$er_alias = $qb->joinRelationshipTable($table_alias, $from_column, $this->property, $this->inverse_relationship, $this->join_type);
				} else {
					$er_alias = $table_alias;
				}
				$column = "{$er_alias}.time_created";
				
				break;

			default:
				throw new InvalidParameterException("'{$this->property_type}' is not a valid entity property type");
		}

		if ($this->signed) {
			$column = "CAST({$column} AS SIGNED)";
		}

		$this->expr = $column;

		return parent::prepare($qb, $table_alias);
	}
}
