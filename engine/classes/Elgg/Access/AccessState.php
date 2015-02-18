<?php
namespace Elgg\Access;

/**
 * Snapshot of the current state of access control
 */
class AccessState {
	public $ignored = false;
	public $current_user_guid = 0;

	public function __toString() {
		return "{$this->current_user_guid}" . ($this->ignored ? 'i' : '');
	}
}
