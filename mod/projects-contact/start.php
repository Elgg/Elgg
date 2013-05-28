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
	
	if (!isset($page[0])) {
		$page[0] = 'owner';
	}	

	$pages = dirname(__FILE__) . '/pages/';	
	
	elgg_push_breadcrumb(elgg_echo('projects'), "projects/all");
			
	switch ($page[0]) {
	
		case "owner":			
			set_input('projectGuid', $page[1]);
			set_input('projectName', $page[2]);

			$url = projects_contact_project_url_($page[1], $page[2]);
			elgg_push_breadcrumb($page[2], $url);
			
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
			set_input('project', $page[1]);
			include "$pages/add.php";
			break;

		default:
			return true;
	}

	elgg_pop_context();
	return true;
}


function projects_contact_url($entity) {	
	global $CONFIG;
	return $CONFIG->url . "projects_contact/view/" . $entity->getGUID();
}

function projects_contact_project_url($entity) {
	$project = get_entity($entity->toGuid);
	return projects_contact_project_url_ ($entity->toGuid, $project->name);
}

function projects_contact_project_url_($guid, $name) {
	global $CONFIG;
	$url = "projects/profile/{$guid}/{$name}";
	return $CONFIG->url . $url;
}

function projects_contact_count_unread($projectGuid) {

	$messages = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'projects-contact',		
		'metadata_name_value_pair' => array(array('name' => 'readed', 'value' => 0), array('name' => 'toGuid', 'value' => $projectGuid))
	));

	$cont = sizeof($messages);
	return $cont;
}

function projects_contact_message_block_menu($hook, $type, $return, $params) {		

	$ownerguid = (int)$params['entity']->owner_guid;

	if (get_subtype_from_id ($params['entity']->subtype) == 'project') {
		//Message list
		if ($ownerguid == elgg_get_logged_in_user_guid()) {
			$url = "projects_contact/owner/{$params['entity']->guid}/{$params['entity']->name}";
			
			$numMsg = projects_contact_count_unread($params['entity']->guid);
			 
			if ($numMsg>0) {
				$UnReadCount = ' (' . $numMsg . ')';				
			}

			$item = new ElggMenuItem('projects_contact', elgg_echo('projects_contact:projects') . $UnReadCount, $url);
			$return[] = $item;
		}else{
		//New Message
			$url = "projects_contact/add/{$params['entity']->guid}/{$params['entity']->name}";
			$item = new ElggMenuItem('projects_contact', elgg_echo('projects_contact:add'), $url);
			$return[] = $item;
		}
	}
	return $return;		
}



