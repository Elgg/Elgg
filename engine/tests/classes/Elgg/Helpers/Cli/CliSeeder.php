<?php

namespace Elgg\Helpers\Cli;

use Elgg\Database\Seeds\Seed;

/**
 * @see \Elgg\Cli\DatabaseSeedCommandUnitTest
 */
class CliSeeder extends Seed {
	
	/**
	 * {@inheritDoc}
	 */
	public function seed() {
		elgg_register_success_message(__METHOD__);
	}

	/**
	 * {@inheritDoc}
	 */
	public function unseed() {
		elgg_register_success_message(__METHOD__);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public static function getType(): string {
		return 'testing';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions(): array {
		return [
			'type' => 'object',
			'subtype' => 'dummy',
		];
	}
}
