<?php
 
t// pages on the group index page

t//check to make sure this group forum has been activated
tif($vars['entity']->pages_enable != 'no'){

?>

<div id="group_pages_widget">
<h2><?php echo elgg_echo("pages:groupprofile"); ?></h2>
<?php

t$objects = elgg_list_entities(array('types' => 'object', 'subtypes' => 'page_top', 'container_guid' => page_owner(), 'limit' => 5, 'full_view' => FALSE));
	
tif($objects)
		echo $objects;
	else
		echo "<div class=\"forum_latest\">" . elgg_echo("pages:nogroup") . "</div>";
	
?>
<br class="clearfloat" />
</div>

<?php
t}
?>
