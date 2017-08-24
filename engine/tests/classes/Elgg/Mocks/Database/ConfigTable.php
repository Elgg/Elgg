<?php
/**
 *
 */

namespace Elgg\Mocks\Database;

class ConfigTable extends \Elgg\Database\ConfigTable {

	public function get($name) {
		return [];
	}
}