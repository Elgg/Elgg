<?php
/**
* Profile widgets/tools
* 
* @package ElggGroups
*/ 
	
// tools widget area
echo '<div id="groups-tools" class="elgg-gallery elgg-gallery-fluid mtl clearfix">';
	
// enable tools to extend this area
echo elgg_view("groups/tool_latest", array('entity' => $vars['entity']));

echo "</div>";		 

