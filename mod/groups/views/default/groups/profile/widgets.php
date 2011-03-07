<?php
/**
* Profile widgets/tools
* 
* @package ElggGroups
*/ 
	
// tools widget area
echo '<div id="groups-tools" class="mtl clearfix">';
	
// enable tools to extend this area
echo elgg_view("groups/tool_latest", array('entity' => $vars['entity']));

echo "</div>";		 
?>

<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
$(function () { // subclass every other group tool widget
	$('#groups-tools').find('.elgg-module:odd').addClass('odd');
});
</script>	 
