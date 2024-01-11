<?php

namespace Elgg\Helpers;

/**
 * @see \ElggExtenderUnitTest
 */
class ElggExtenderExtension extends \ElggExtender {
	public function canEdit(int $user_guid = 0): bool {
		return false;
	}
	
	public function save(): bool {
		return false;
	}
	
	public function delete(): bool {
		return false;
	}
	
	public function getObjectFromID(int $id): ?\ElggEntity {
		return null;
	}
}
