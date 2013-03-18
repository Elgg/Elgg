<?php
/**
 * Profile widgets/tools
 * 
 * @package Coopfunding
 * @subpackage Projects
 */ 
	
// tools widget area
echo '<ul id="projects-tools" class="elgg-gallery elgg-gallery-fluid mtl clearfix">';

// enable tools to extend this area
echo elgg_view("projects/tool_latest", $vars);

echo "</ul>";

