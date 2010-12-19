<?php
/**
 * Profile groups
 **/
$groups = list_entities_from_relationship('member',$vars['entity']->getGUID(),false,'group','',0, $limit,false, false);

if(!$groups) {
	$groups = '<p>' . elgg_echo('profile:no_groups') . '</p>';
}

echo $groups;