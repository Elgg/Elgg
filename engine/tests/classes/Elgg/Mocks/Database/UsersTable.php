<?php

namespace Elgg\Mocks\Database;

class UsersTable extends \Elgg\Database\UsersTable {

	/**
	 * {@inheritDoc}
	 */
	public function getByUsername(string $username): ?\ElggUser {
		$metadata = $this->metadata->getAll();
		foreach ($metadata as $md) {
			if ($md->name === 'username' && $md->value === $username) {
				return get_entity($md->entity_guid);
			}
		}
		
		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getByEmail(string $email): array {
		$metadata = $this->metadata->getAll();
		foreach ($metadata as $md) {
			if ($md->name === 'email' && $md->value === $email) {
				return get_entity($md->entity_guid);
			}
		}
		
		return [];
	}
}
