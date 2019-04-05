<?php
/**
 * Elgg upgrade library.
 * Contains code for handling versioning and upgrades.
 *
 * @package    Elgg.Core
 * @subpackage Upgrade
 */

use Elgg\Menu\MenuItems;

/**
 * Perform some clean up when upgrade completes
 * @elgg_event complete upgrade
 * @return void
 */
function _elgg_upgrade_completed() {
	$pending = _elgg_services()->upgrades->getPendingUpgrades();
	if (empty($pending)) {
		elgg_delete_admin_notice('pending_upgrades');
	}
}

/**
 * Add menu items to the entity menu of ElggUpgrade
 *
 * @param \Elgg\Hook $hook 'register', 'menu:entity'
 *
 * @return void|MenuItems
 * @access private
 */
function _elgg_upgrade_entity_menu(\Elgg\Hook $hook) {
	
	$entity = $hook->getEntityParam();
	if (!$entity instanceof ElggUpgrade || !$entity->canEdit()) {
		return;
	}
	
	$result = $hook->getValue();
	
	// deleting upgrades has no point, they'll be rediscovered again
	// don't want to completely block the ability in ->canDelete(), just don't offer the link
	$result->remove('delete');
	
	if (!$entity->isCompleted()) {
		$result[] = ElggMenuItem::factory([
			'name' => 'run_upgrade',
			'icon' => 'play',
			'text' => elgg_echo('admin:upgrades:menu:run_single'),
			'href' => false,
			'deps' => [
				'core/js/upgrader',
			],
			'data-guid' => $entity->guid,
		]);
	} elseif ($batch = $entity->getBatch()) {
		if (!$batch->shouldBeSkipped()) {
			// only show reset if it will have an effect
			$result[] = ElggMenuItem::factory([
				'name' => 'reset',
				'icon' => 'sync',
				'text' => elgg_echo('reset'),
				'href' => elgg_generate_action_url('admin/upgrade/reset', [
					'guid' => $entity->guid,
				]),
			]);
		}
	}
	
	return $result;
}

return function (\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('complete', 'upgrade', '_elgg_upgrade_completed');
	
	$hooks->registerHandler('register', 'menu:entity', '_elgg_upgrade_entity_menu', 501);
};
