<?php
	require_once("../../engine/start.php");
	
	global $CONFIG;
	$_SESSION['id'] = 2;
	
	// Get the user
	$owner_id = page_owner();
	$owner = get_user(page_owner());
	
	$description = get_input("task");
	$status = get_input("status");
	$action = get_input("action");
	$guid = get_input("guid");
	$tags = get_input("tags");
	
	$limit = get_input("limit",10);
	$offset = get_input("offset",0);
	
	switch ($action)
	{
		case 'newtask' : 
			$taskid = create_entity("object", "task", $owner_id, 0);

			if ($taskid)
			{ 
				$entity = get_entity($taskid);

				$entity->setMetaData('task', $task, 'text');
				$entity->setMetaData('status', 'notdone', 'text');
				
				if ($tags!="")
				{
					$tags = explode(",",$tags);
					
					foreach ($tags as $tag)
					{
						$tag = sanitise_string($tag);
						$entity->setMetaData($tag, $tag);
					}
				}
				
				$entity->save();
			} else echo "error";
			
		break;
		
		case 'tick' :
			$entity = get_entity($guid);
			
			$entity->setMetaData('status', 'done', 'text');
		break;
	}

	$body = elgg_view("tasklist/main", array(
		"name" => $owner->name,
		"tasklist" => tasklist_drawtasks($owner_id, $limit, $offset),
		"newtask" => tasklist_draw_newtask_form($owner_id)
	));
	page_draw("Tasklist",$body);
	
?>