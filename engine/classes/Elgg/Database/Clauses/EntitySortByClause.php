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
	 * @var string
	 */
	public $direction;

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

		switch ($this->property_type) {
			case 'metadata' :
				$md_alias = $qb->joinMetadataTable($table_alias, 'guid', $this->property, $this->join_type);
				$column = "$md_alias.value";
				break;

			case 'attribute' :
				if (!in_array($this->property, ElggEntity::PRIMARY_ATTR_NAMES)) {
					throw new InvalidParameterException("'$this->property' is not a valid entity attribute");
				}
				$column = "$table_alias.$this->property";
				break;

			case 'private_setting' :
				$ps_alias = $qb->joinPrivateSettingsTable($table_alias, 'guid', $this->property, $this->join_type);
				$column = "$ps_alias.value";
				break;

			case 'annotation' :
				$an_alias = $qb->joinAnnotationTable($table_alias, 'guid', $this->property, $this->join_type);
				$column = "$an_alias.value";
				break;

			default :
				throw new InvalidParameterException("'$this->property_type' is not a valid entity property type");
		}

		if ($this->signed) {
			$column = "CAST($column AS SIGNED)";
		}

		$this->expr = $column;

		return parent::prepare($qb, $table_alias);
	}
}
