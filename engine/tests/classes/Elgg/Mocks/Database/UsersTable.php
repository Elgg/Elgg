<?php

namespace Elgg\Mocks\Database;

class UsersTable extends \Elgg\Database\UsersTable {

	public function getByUsername($username) {
		$metadata = $this->metadata->getAll();
		foreach ($metadata as $md) {
			if ($md->name === 'username' && $md->value === $username) {
				return get_entity($md->entity_guid);
			}
		}
	}

	public function getByEmail($email) {
		$metadata = $this->metadata->getAll();
		foreach ($metadata as $md) {
			if ($md->name === 'email' && $md->value === $email) {
				return get_entity($md->entity_guid);
			}
		}
	}
}