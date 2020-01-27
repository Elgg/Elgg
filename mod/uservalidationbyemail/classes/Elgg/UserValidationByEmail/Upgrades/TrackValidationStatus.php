<?php

namespace Elgg\UserValidationByEmail\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;
use Elgg\Database\QueryBuilder;

/**
 * Track the email validation status of users
 *
 * @since 3.2
 */
class TrackValidationStatus implements AsynchronousUpgrade {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::getVersion()
	 */
	public function getVersion() {
		return 2019090600;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::shouldBeSkipped()
	 */
	public function shouldBeSkipped() {
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::countItems()
	 */
	public function countItems() {
		return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () {
			return elgg_count_entities($this->getOptions());
		});
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::needsIncrementOffset()
	 */
	public function needsIncrementOffset() {
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::run()
	 */
	public function run(Result $result, $offset) {
		
		$options = $this->getOptions([
			'offset' => $offset,
			'batch' => true,
		]);
		
		elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use (&$result, $options) {
			$users = elgg_get_entities($options);
			
			/* @var $user \ElggUser */
			foreach ($users as $user) {
				if (elgg_set_plugin_user_setting('email_validated', false, $user->guid, 'uservalidationbyemail')) {
					$result->addSuccesses();
					continue;
				}
				
				$result->addFailures();
			}
		});
		
		return $result;
	}
	
	/**
	 * Get options for use in elgg_get_entities()
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 */
	protected function getOptions(array $options = []) {
		$defaults = [
			'type' => 'user',
			'metadata_name_value_pairs' => [
				'name' => 'disable_reason',
				'value' => 'uservalidationbyemail_new_user',
			],
			'plugin_user_setting_name_value_pairs_operator' => 'or',
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.enabled", '=', 'no', ELGG_VALUE_STRING);
				}
			],
		];
		
		return array_merge($defaults, $options);
	}
}
