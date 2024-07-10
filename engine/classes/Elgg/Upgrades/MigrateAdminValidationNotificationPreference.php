<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

class MigrateAdminValidationNotificationPreference extends AsynchronousUpgrade {
	
	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): int {
		return 2024071001;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped(): bool {
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function countItems(): int {
		return elgg_count_entities($this->getOptions());
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset): Result {
		/* @var $batch \ElggBatch */
		$batch = elgg_get_entities($this->getOptions([
			'offset' => $offset,
		]));
		
		$methods = elgg_get_notification_methods();
		
		/* @var $user \ElggUser */
		foreach ($batch as $user) {
			// disable for all methods
			foreach ($methods as $method) {
				$user->setNotificationSetting($method, false, 'admin_validation_notification');
			}
			
			// migrate preference
			$user->setNotificationSetting('email', (bool) $user->admin_validation_notification, 'admin_validation_notification');
			unset($user->admin_validation_notification);
			
			$result->addSuccesses();
		}
		
		return $result;
	}
	
	/**
	 * Get the options to fetch orphaned comments
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 * @see elgg_get_entities()
	 */
	protected function getOptions(array $options = []): array {
		$defaults = [
			'type' => 'user',
			'limit' => 100,
			'batch' => true,
			'batch_inc_offset' => $this->needsIncrementOffset(),
			'batch_size' => 50,
			'metadata_name' => 'admin_validation_notification',
		];
		
		return array_merge($defaults, $options);
	}
}
