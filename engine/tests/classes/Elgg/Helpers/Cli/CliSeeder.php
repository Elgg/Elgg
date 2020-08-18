<?php

namespace Elgg\Helpers\Cli;

use Elgg\Database\Seeds\Seed;

/**
 * @see \Elgg\Cli\DatabaseSeedCommandTest
 */
class CliSeeder extends Seed {
	
	/**
	 * Populate database
	 * @return mixed
	 */
	function seed() {
		system_message(__METHOD__);
	}
	
	/**
	 * Removed seeded rows from database
	 * @return mixed
	 */
	function unseed() {
		system_message(__METHOD__);
	}
}
