<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * Builds queries to restrict access
 */
class AccessWhereClause extends WhereClause {

	public string $access_column = 'access_id';

	public string $owner_guid_column = 'owner_guid';

	public string $guid_column = 'guid';

	public string $enabled_column = 'enabled';

	public string $deleted_column = 'deleted';

	public ?bool $ignore_access = null;

	public ?bool $use_enabled_clause = null;
	
	public ?bool $use_deleted_clause = null;

	public ?int $viewer_guid = null;

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		if (!isset($this->viewer_guid)) {
			$this->viewer_guid = _elgg_services()->session_manager->getLoggedInUserGuid();
		}

		if (!isset($this->ignore_access)) {
			$this->ignore_access = _elgg_services()->userCapabilities->canBypassPermissionsCheck($this->viewer_guid);
		}

		if (!isset($this->use_enabled_clause)) {
			$this->use_enabled_clause = !_elgg_services()->session_manager->getDisabledEntityVisibility();
		}

		if (!isset($this->use_deleted_clause)) {
			$this->use_deleted_clause = !_elgg_services()->session_manager->getDeletedEntityVisibility();
		}

		$ors = [];
		$ands = [];

		$ands[] = parent::prepare($qb, $table_alias);

		if (!$this->ignore_access) {
			if ($this->viewer_guid) {
				// include user's content
				$ors['owner_access'] = $qb->compare($alias($this->owner_guid_column), '=', $this->viewer_guid, ELGG_VALUE_INTEGER);
			}

			// include standard accesses (public, logged in, access collections)
			$access_list = _elgg_services()->accessCollections->getAccessArray($this->viewer_guid);
			$ors['acl_access'] = $qb->compare($alias($this->access_column), '=', $access_list, ELGG_VALUE_INTEGER);
		}

		if ($this->use_enabled_clause) {
			$ands[] = $qb->compare($alias($this->enabled_column), '=', 'yes', ELGG_VALUE_STRING);
		}

		if ($this->use_deleted_clause) {
			$ands[] = $qb->compare($alias($this->deleted_column), '=', 'no', ELGG_VALUE_STRING);
		}

		$params = [
			'table_alias' => $table_alias,
			'user_guid' => $this->viewer_guid,
			'ignore_access' => $this->ignore_access,
			'use_enabled_clause' => $this->use_enabled_clause,
			'access_column' => $this->access_column,
			'owner_guid_column' => $this->owner_guid_column,
			'guid_column' => $this->guid_column,
			'enabled_column' => $this->enabled_column,
			'deleted_column' => $this->deleted_column,
			'use_deleted_clause' => $this->use_deleted_clause,
			'query_builder' => $qb,
		];

		$clauses = _elgg_services()->events->triggerResults('get_sql', 'access', $params, [
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
