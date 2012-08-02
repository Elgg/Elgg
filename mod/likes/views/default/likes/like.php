<?php
/**
 * Elgg like button called via ajax
 */
 $guid = (int) get_input('guid');
 $entity = get_entity($guid);
 if($entity){
	echo elgg_view('likes/button', array('entity' => $entity));
 }
?>