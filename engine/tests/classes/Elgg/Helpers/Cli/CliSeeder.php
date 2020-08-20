<?php

namespace Elgg\Helpers\Cli;

use Elgg\Database\Seeds\Seed;

/**
 * @see \Elgg\Cli\DatabaseSeedCommandTest
 */
class CliSeeder extends Seed {
	
	/**
	 * {@inheritDoc}
	 */
	public function seed() {
		system_message(__METHOD__);
	}

	/**
	 * {@inheritDoc}
	 */
	public function unseed() {
		system_message(__METHOD__);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public static function getType() : string {
		return 'testing';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions() : array {
		return [
			'type' => 'object',
			'subtype' => 'dummy',
		];
	}
}
