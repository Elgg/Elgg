<?php
/**
* Profile widgets/tools
*
* @package ElggGroups
*/
	
// tools widget area
echo '<ul id="groups-tools">';

// enable tools to extend this area
echo elgg_view("groups/tool_latest", $vars);

echo "</ul>";

