<?php

namespace Elgg\ReportedContent\Controllers;

use Elgg\Controllers\GenericEntity;

/**
 * View a reported content item
 *
 * @since 7.1
 */
class Entity extends GenericEntity {
	
	/**
	 * {@inheritdoc}
	 */
	protected function prepareBreadcrumbs(): void {
		if ($this->getPage() === 'view') {
			/** @var \ElggReportedContent $entity */
			$entity = $this->getEntity();
			if ($entity->state === 'active') {
				elgg_push_breadcrumb(elgg_echo('reportedcontent:new'), elgg_generate_url('admin', [
					'segments' => 'administer_utilities/reportedcontent',
				]));
			} else {
				elgg_push_breadcrumb(elgg_echo('reportedcontent:archived_reports'), elgg_generate_url('admin', [
					'segments' => 'administer_utilities/reportedcontent/archive',
				]));
			}
			
			return;
		}
		
		parent::prepareBreadcrumbs();
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function viewEntity(): string {
		$entity = $this->getEntity();
		
		return elgg_view_page('', $this->getPageOptions('view', [
			'content' => elgg_view_entity($entity),
			'entity' => $entity,
			'show_owner_block' => false,
		]), 'admin');
	}
}
