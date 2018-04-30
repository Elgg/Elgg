<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * Builds queries to restrict access
 */
class AccessWhereClause extends WhereClause {

	/**
	 * @var string
	 */
	public $access_column = 'access_id';
	/**
	 * @var string
	 */
	public $owner_guid_column = 'owner_guid';

	/**
	 * @var string
	 */
	public $guid_column = 'guid';

	/**
	 * @var string
	 */
	public $enabled_column = 'enabled';

	/**
	 * @var bool
	 */
	public $ignore_access;

	/**
	 * @var bool
	 */
	public $use_enabled_clause;

	/**
	 * @var int
	 */
	public $viewer_guid;

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {

		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		if (!isset($this->viewer_guid)) {
			$this->viewer_guid = _elgg_services()->session->getLoggedInUserGuid();
		}

		if (!isset($this->ignore_access)) {
			$this->ignore_access = _elgg_services()->userCapabilities->canBypassPermissionsCheck($this->viewer_guid);
		}

		if (!isset($this->use_enabled_clause)) {
			$this->use_enabled_clause = !_elgg_services()->session->getDisabledEntityVisibility();
		}

		$ors = [];
		$ands = [];

		$ands[] = parent::prepare($qb, $table_alias);

		if (!$this->ignore_access) {
			// This hook has been deprecated, but the plugins may still be using it to add custom collection ids
			$legacy_mode = _elgg_services()->hooks->hasHandler('access:collections:read', 'user');

			if ($this->viewer_guid) {
				if ($this->owner_guid_column) {
					// include user's content
					$ors['owner_access'] = $qb->compare($alias($this->owner_guid_column), '=', $this->viewer_guid, ELGG_VALUE_INTEGER);
				}

				if ($legacy_mode) {
					// Use deprecated hook in case plugins
					$access_list = _elgg_services()->accessCollections->getAccessArray($this->viewer_guid);
					$ors['acl_access'] = $qb->compare($alias($this->access_column), '=', $access_list, ELGG_VALUE_INTEGER);
				} else {
					$collections_subquery = $qb->subquery('access_collections');
					$collections_subquery->select(1)
						->where($qb->compare('owner_guid', '=', $this->viewer_guid, ELGG_VALUE_INTEGER))
						->andWhere($qb->compare('id', '=', $alias($this->access_column)));

					$membership_subquery = $qb->subquery('access_collection_membership');
					$membership_subquery->select(1)
						->where($qb->compare('user_guid', '=', $this->viewer_guid, ELGG_VALUE_INTEGER))
						->andWhere($qb->compare('access_collection_id', '=', $alias($this->access_column)));

					$ors['acl_access'] = $qb->merge([
						$qb->compare($alias($this->access_column), '=', ACCESS_PUBLIC, ELGG_VALUE_INTEGER),
						$qb->compare($alias($this->access_column), '=', ACCESS_LOGGED_IN, ELGG_VALUE_INTEGER),
						"EXISTS ({$collections_subquery->getSQL()})",
						"EXISTS ({$membership_subquery->getSQL()})",
					], 'OR');
				}
			} else if (!$legacy_mode) {
				$ors['acl_access'] = $qb->compare($alias($this->access_column), '=', ACCESS_PUBLIC, ELGG_VALUE_INTEGER);
			}
		}

		if ($this->use_enabled_clause) {
			$ands[] = $qb->compare($alias($this->enabled_column), '=', 'yes', ELGG_VALUE_STRING);
		}

		$hook_params = [
			'table_alias' => $table_alias,
			'user_guid' => $this->viewer_guid,
			'ignore_access' => $this->ignore_access,
			'use_enabled_clause' => $this->use_enabled_clause,
			'access_column' => $this->access_column,
			'owner_guid_column' => $this->owner_guid_column,
			'guid_column' => $this->guid_column,
			'enabled_column' => $this->enabled_column,
			'query_builder' => $qb,
		];

		$clauses = _elgg_services()->hooks->trigger('get_sql', 'access', $hook_params, [
			'ors' => $ors,
			'ands' => $ands,
		]);

		$ors = array_filter($clauses['ors']);
		$ands = array_filter($clauses['ands']);

		if (!empty($ors)) {
			$ands[] = $qb->merge($ors, 'OR');
		}

		return $qb->merge($ands);
	}

}
