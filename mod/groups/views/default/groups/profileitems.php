<?php
	/**
	 * Elgg groups items view.
	 * This is the messageboard, members, pages and latest forums posts. Each plugin will extend the views
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	 //forum 
	 echo "<div class='group_profile forum_latest clearfloat'>";
	 echo elgg_view("groups/forum_latest",array('entity' => $vars['entity']));
	 echo "</div>";
/*
	 //right column
	 echo "<div class='group_profile_column right'>";
	 echo elgg_view("groups/right_column",array('entity' => $vars['entity']));
	 echo "</div>";
	 
	 //left column
	 echo "<div class='group_profile_column left'>";
	 echo elgg_view("groups/left_column",array('entity' => $vars['entity']));
	 echo "</div>";	
*/ 
	 
	 // tools widget area
	 echo "<div id='group_tools_latest' class='clearfloat'>";
		 echo "<div class='group_tool_widget'><h3>Dummy Placeholder Group Tool Widget</h3></div>"; //test
	 echo elgg_view("groups/tool_latest",array('entity' => $vars['entity']));
		 echo "<div class='group_tool_widget'><h3>Another Placeholder for testing</h3></div>"; //test
	 echo "</div>";		 
?>
<script type="text/javascript">
$(document).ready(function () { // subclass every other group tool widget
	$('#group_tools_latest').find('.group_tool_widget:odd').addClass('odd');
});
</script>	 
