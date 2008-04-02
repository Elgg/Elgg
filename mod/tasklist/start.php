<?php
	/**
	 * Simple tasklist plugin
	 *
	 * These parameters are required for the event API, but we won't use them:
	 * 
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */
	function tasklist_init($event, $object_type, $object = null) {
		
		global $CONFIG;
			
		add_menu("Tasklist",$CONFIG->wwwroot . "mod/tasklist/",array(
				menu_item("The tasklist plugin",$CONFIG->wwwroot."mod/tasklist/"),
		));
	}

	/**
	 * The entity.
	 *
	 * @param ElggObject $entity
	 */
	function tasklist_draw_task($entity)
	{
		// Get the status
		$status = $entity->getMetaData("status");
		
		// Task
		$task = $entity->getMetaData("task");
print_r($task);
print_r($entity);		
echo "TSD : $task";
		// Render the item
		return elgg_view("tasklist/item", array(
			"owner_id" => $entity->owner_guid,
			"task" => $task,
			"status" => $status,
			"guid" => $entity->guid
		));
	}
	
	function tasklist_drawtasks($ownerid, $offset = 0, $limit = 10)
	{
		// Get all entities of task
		//$entities = get_entities("object","task",$ownerid, "time_created desc", $limit, $offset);
		$entities = get_entities_from_metadata("status", "notdone", "object", "task", $limit, $offset);

		if (($entities) && (is_array($entities)))
		{
			$display = "<table>";
			
			foreach($entities as $e)
				$display .= tasklist_draw_task($e);
			
			$display .= "</table>";
				
			return $display;
		}
		
		return "No tasks present for user.";
	}
	
	function tasklist_draw_newtask_form($ownerid)
	{
		return elgg_view("tasklist/newtask", array(
			"owner_id" => $ownerid
		));
	}
	
	// Make sure test_init is called on initialisation
	register_event_handler('init','system','tasklist_init');
		
?>