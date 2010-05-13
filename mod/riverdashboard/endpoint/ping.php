<?php
/**
 * Check for new activity.
 * Outputs # of new activity items since $_GET['last_checked'] time
 */

// Load Elgg engine will not include plugins
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
     
// check for last checked time
if (!$seconds_passed = get_input('seconds_passed', 0)) {
  	echo '';
   	exit;
}

$last_reload = time() - $seconds_passed;

//grab any new annotations
$annotations = count_annotations('', '', '', '', '', '', '', $last_reload);
if (!$annotations) {
	$annotations = 0;
}

//grab all new objects created	
$entity_creation = elgg_get_entities(array(
	'count' => TRUE, 
	'created_time_lower' => $last_reload,
	'wheres' => array('e.type != \'user\'')
));

if (!$entity_creation) {
	$entity_creation = 0;
}

//grab any entities updated
$entity_update = elgg_get_entities(array(
	'count' => TRUE, 
	'modified_time_lower' => $last_reload,
	'wheres' => array('e.type != \'user\'')
));
if (!$entity_update) {
	$entity_update = 0;
}
//get any relationships, such as friending - this is not working quite right yet
//$relationship_action = elgg_get_entities_from_relationship(array('count' => TRUE));
//if(!$relationship_action)
//	$relationship_action = 0;

//sum all totals
$all_activity = $annotations + $entity_creation + $entity_update;

if ($all_activity > 0) {
  	$s = ($all_activity == 1) ? '' : 's';
  	echo "<a href='' onClick=\"window.location.reload();\" class='update_link'>$all_activity update$s!</a>";
?>
	<script type="text/javascript">
		$(document).ready(function(){
		  
			var pageTitleSubstring;
			var stringStartPosition = document.title.indexOf("]");
			
			if (stringStartPosition == -1) { // we haven't already altered page title
				pageTitleSubstring = document.title; 
			} else { // we previously prepended to page title, need to remove it first
				pageTitleSubstring = document.title.substring( (stringStartPosition+2) );
			}
			
			document.title = "[<?php echo $all_activity; ?> update<?php echo $s; ?>] "+pageTitleSubstring;
		});
	</script>

<?php  	
} else {
  	echo '';
   	exit;
}
