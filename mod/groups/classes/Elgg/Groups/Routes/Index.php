<?php

namespace Elgg\Groups\Routes;

class Index implements \Elgg\Route {

	public function __construct($identifier, $segments) {
		$this->identifier = $identifier;
		$this->segments = $segments;
	}

	public function validate() {
		
	}
	
	public function getBreadcrumbs() {
		return [
			[
				'title' => elgg_echo('groups'),
				'href' => 'groups/all',
			],
		];
	}

	public function getContent() {
		if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
			elgg_register_title_button('groups', 'add', 'group');
		}

		$selected_tab = get_input('filter', 'newest');
		if (!elgg_view_exists("groups/listing/$selected_tab")) {
			$selected_tab = 'newest';
		}
		return elgg_view("groups/listing/$selected_tab");
	}

	public function getFilter() {
		$tabs = array(
			'newest' => array(
				'text' => elgg_echo('sort:newest'),
				'href' => 'groups/all?filter=newest',
				'priority' => 200,
			),
			'alpha' => array(
				'text' => elgg_echo('sort:alpha'),
				'href' => 'groups/all?filter=alpha',
				'priority' => 250,
			),
			'popular' => array(
				'text' => elgg_echo('sort:popular'),
				'href' => 'groups/all?filter=popular',
				'priority' => 300,
			),
			'featured' => array(
				'text' => elgg_echo('groups:featured'),
				'href' => 'groups/all?filter=featured',
				'priority' => 400,
			),
		);

		if (elgg_is_active_plugin('discussions')) {
			$tabs['discussion'] = array(
				'text' => elgg_echo('discussion:latest'),
				'href' => 'groups/all?filter=discussion',
				'priority' => 500,
			);
		}

		return $tabs;
	}

	public function getIdentifier() {
		return $this->identifier;
	}

	public function getLayout() {
		return 'default';
	}

	public function getPageEntity() {
		return null;
	}

	public function getPageOwner() {
		return null;
	}

	public function getPageShell() {
		return 'default';
	}

	public function getSegments() {
		return $this->segments;
	}

	public function getSidebar() {
		$sidebar = elgg_view('groups/sidebar/find');
		$sidebar .= elgg_view('groups/sidebar/featured');
		return $sidebar;
	}

	public function getSidebarAlt() {
		return false;
	}

	public function getTitle() {
		return elgg_echo('groups:all');
	}

	public function getPageTitle() {
		return $this->getTitle();
	}

}
