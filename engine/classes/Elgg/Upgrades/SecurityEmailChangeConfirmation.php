<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Set a default security config value
 */
class SecurityEmailChangeConfirmation implements AsynchronousUpgrade {

	private $defaults = [
		'security_email_require_confirmation' => true,
	];

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2019071901;
	}

	/**
	 * {@inheritdoc}
	 */
	public function needsIncrementOffset() {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped() {

		foreach ($this->defaults as $name => $value) {
			$existing_value = elgg_get_config($name);
			if (is_null($existing_value)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		return count($this->defaults);
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {

		foreach ($this->defaults as $name => $value) {
			$existing_value = elgg_get_config($name);
			if (is_null($existing_value)) {
				elgg_save_config($name, $value);
			}

			$result->addSuccesses();
		}
	}

}
