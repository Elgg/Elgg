<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Set default security config values
 * We run this async to allow sites to upgrade without having to deal with tokens
 */
class SetSecurityConfigDefaults implements AsynchronousUpgrade {

	private $defaults = [
		'security_protect_upgrade' => true,
		'security_notify_admins' => true,
		'security_notify_user_password' => true,
		'security_email_require_password' => true,
	];

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2017080950;
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
