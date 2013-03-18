<?php
/**
* Profile widgets/tools
* 
* @package ElggGroups
*/ 
	
// tools widget area
echo '<ul id="projects-tools" class="elgg-gallery elgg-gallery-fluid mtl clearfix">';

// enable tools to extend this area
echo elgg_view("projects/tool_latest", $vars);

// backward compatibility
$right = elgg_view('projects/right_column', $vars);
$left = elgg_view('projects/left_column', $vars);
if ($right || $left) {
	elgg_deprecated_notice('The views projects/right_column and projects/left_column have been replaced by projects/tool_latest', 1.8);
	echo $left;
	echo $right;
}

echo "</ul>";

