<?php

namespace Elgg\ReportedContent\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Entity {
	
	/**
	 * Add items to entity menu for archiving
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity:object:reported_content'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerArchive(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggReportedContent) {
			return;
		}
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if ($entity->state === 'archived') {
			return;
		}
				
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'archive',
			'icon' => 'archive',
			'text' => elgg_echo('reportedcontent:archive'),
			'href' => elgg_generate_action_url('reportedcontent/archive', [
				'guid' => $entity->guid,
			]),
			'section' => 'actions',
			'data-colorbox-opts' => json_encode([
				'width' => '85%',
				'height' => '85%',
				'iframe' => true,
			]),
		]);
	
		return $return;
	}
	
	/**
	 * Add items to entity menu for reporting entities
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerEntityReporting(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggEntity || !elgg_is_logged_in()) {
			return;
		}
		
		$report_this = (bool) $hook->getParam('report_this', is_registered_entity_type($entity->type, $entity->subtype));
		if (!$report_this) {
			return;
		}
				
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'report_this',
			'href' => elgg_http_add_url_query_elements('ajax/form/reportedcontent/add', [
				'address' => $entity->getURL(),
				'title' => $entity->getDisplayName(),
				'entity_guid' => $entity->guid,
			]),
			'title' => elgg_echo('reportedcontent:this:tooltip'),
			'text' => elgg_echo('reportedcontent:this'),
			'icon' => 'exclamation-triangle',
			'link_class' => 'elgg-lightbox',
			'deps' => 'elgg/reportedcontent',
		]);
	
		return $return;
	}
}
