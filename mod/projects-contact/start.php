<?php

elgg_register_event_handler('init', 'system', 'projects_contact_init');

function projects_contact_init() {

	$root = dirname(__FILE__);
	elgg_register_library('elgg:projects_contact', "$root/lib/projects-contact.php");

	// actions
	$action_path = "$root/actions/projects-contact";
	elgg_register_action('projects-contact/save', "$action_path/save.php");
	elgg_register_action('projects-contact/delete', "$action_path/delete.php");
	elgg_register_action('projects-contact/process', "$action_path/process.php");
	
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'projects_contact_message_block_menu');

	elgg_register_page_handler('projects_contact', 'projects_contact_page_handler');

	elgg_extend_view('css/elgg', 'projects-contact/css');
	elgg_extend_view('js/elgg', 'projects-contact/js');

	// Register a URL handler for projects_contact
	elgg_register_entity_url_handler('object', 'projects_contact', 'projects_contact_url');

	// Register entity type for search
	elgg_register_entity_type('object', 'projects-contact');
	
}


function projects_contact_page_handler($page) {

	elgg_load_library('elgg:projects_contact');
	elgg_load_library('elgg:projects');
	
	if (!isset($page[0])) {
		$page[0] = 'owner';
	}	

	$pages = dirname(__FILE__) . '/pages/';	
	
	elgg_push_breadcrumb(elgg_echo('projects'), "projects/all");
			
	switch ($page[0]) {
	
		case "owner":			
			$project = projects_get_from_alias($page[1]);

			if (!$project) {
				forward();
			}
			
			set_input('project_guid', $project->guid);
			elgg_push_breadcrumb($project->name, $project->getUrl());
			
			include "$pages/owner.php";						
			break;
		
		case "view":			
			$contact = get_entity((int)$page[1]);
			$project = get_entity($contact->toGuid);

			set_input('guid', $page[1]);	
			
			$url =  projects_contact_project_url($contact);
			elgg_push_breadcrumb($project->name, $url);
					
			include "$pages/view.php";
			break;

		case "add":
			$project_guid = projects_get_from_alias($page[1])->guid;
			if (!$project_guid) {
				forward();
			}
			elgg_set_page_owner_guid($project_guid);
			include "$pages/add.php";
			break;

		default:
			return true;
	}

	elgg_pop_context();
	return true;
}


function projects_contact_url($entity) {	
	return elgg_get_site_url() . "projects_contact/view/" . $entity->getGUID();
}

function projects_contact_count_unread($projectGuid) {

	$messages = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'projects-contact',		
		'metadata_name_value_pair' => array(
			array('name' => 'readed', 'value' => 0),
			array('name' => 'toGuid', 'value' => $projectGuid),
		),
	));

	$cont = sizeof($messages);
	return $cont;
}

function projects_contact_message_block_menu($hook, $type, $return, $params) {		

	if (!elgg_instanceof($params['entity'], 'group', 'project')) {
		return $return;
	}
	
	if ($params['entity']->isMember()) {
		$url = "projects_contact/owner/{$params['entity']->alias}/}";
		
		$text = elgg_echo('projects_contact:projects');
		$num_msg = projects_contact_count_unread($params['entity']->guid);
		if ($num_msg > 0) {
			$text .= " ($num_msg)";
		}
		
		$return[] = new ElggMenuItem('projects_contact_inbox', $text, $url); 
	}
	
	if (elgg_is_logged_in()) {
		$url = "projects_contact/add/{$params['entity']->alias}";
		$text = elgg_echo('projects_contact:add');
		$return[] = new ElggMenuItem('projects_contact_add', $text, $url);
	}

	return $return;		
}



