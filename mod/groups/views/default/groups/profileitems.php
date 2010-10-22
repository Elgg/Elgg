<?php
/**
* Elgg groups - group homepage (profile) - provide an area for tools to extend with their latest content.
* 
* @package ElggGroups
*/ 
	 
// tools widget area
echo "<div id='group_tools_latest' class='clearfloat'>";

	// activity latest 
	echo "<div class='group_tool_widget activity clearfloat'>";
	echo elgg_view("groups/activity_latest",array('entity' => $vars['entity']));
	echo "</div>";
	 
	// forum latest
	echo "<div class='group_tool_widget forum clearfloat'>";
	echo elgg_view("groups/forum_latest",array('entity' => $vars['entity']));
	echo "</div>";
	
	// enable tools to extend this area
	echo elgg_view("groups/tool_latest",array('entity' => $vars['entity']));

echo "</div>";		 
?>
<script type="text/javascript">
$(document).ready(function () { // subclass every other group tool widget
	$('#group_tools_latest').find('.group_tool_widget:odd').addClass('odd');
});
</script>	 
