<?php

namespace Elgg\Groups\Routes;

class Profile extends Index {

	public function __construct($identifier, $segments) {
		$this->identifier = $identifier;
		$this->segments = $segments;
	}

	public function validate() {
		$guid = $this->segments['guid'];
		if (!elgg_entity_gatekeeper($guid, null, null, false)) {
			return elgg_error_response('Group not found', 'groups', 404);
		}

		if (!elgg_group_gatekeeper(false, $guid)) {
			return elgg_error_response('Group not accessible', 'groups', 403);
		}
	}

	public function getBreadcrumbs() {
		$breadcrumbs = parent::getBreadcrumbs();
		$breadcrumbs[] = [
			'title' => $this->getPageEntity()->getDisplayName(),
			'href' => $this->getPageEntity()->getURL(),
		];
		return $breadcrumbs;
	}

	public function getContent() {
		elgg_push_context('group_profile');

		elgg_register_rss_link();

		groups_register_profile_buttons($this->getPageEntity());

		return elgg_view('groups/profile/layout', array('entity' => $this->getPageEntity()));
	}

	public function getFilter() {
		return [];
	}

	public function getIdentifier() {
		return $this->identifier;
	}

	public function getLayout() {
		return 'default';
	}

	public function getPageEntity() {
		return get_entity($this->segments['guid']);
	}

	public function getPageOwner() {
		return $this->getPageEntity();
	}

	public function getPageShell() {
		return 'default';
	}

	public function getSegments() {
		return $this->segments;
	}

	public function getSidebar() {
		if (elgg_is_active_plugin('search')) {
			$sidebar .= elgg_view('groups/sidebar/search', array('entity' => $this->getPageEntity()));
		}
		$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $this->getPageEntity()));
		return $sidebar;
	}

	public function getSidebarAlt() {
		return false;
	}

	public function getTitle() {
		return $this->getPageEntity()->getDisplayName();
	}

	public function getPageTitle() {
		return $this->getTitle();
	}

}
