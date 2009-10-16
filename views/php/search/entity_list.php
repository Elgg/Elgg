<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$entities = $vars['entities'];
if (is_array($entities) && sizeof($entities) > 0) {
	foreach($entities as $entity) {
		echo elgg_view_entity($entity);
	}
}