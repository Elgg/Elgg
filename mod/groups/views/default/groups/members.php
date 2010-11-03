<?php
	/**
	 * Elgg groups items view.
	 * This is the messageboard, members, pages and latest forums posts. Each plugin will extend the views
	 * 
	 * @package ElggGroups
	 */
	 
	 //var_export($vars['entity']);
?>

<div id="group_members" class="clearfix">
<h3><?php echo elgg_echo("groups:members"); ?></h3>

<?php
    $members = $vars['entity']->getMembers(10);
    foreach($members as $mem){
        echo "<div class='member_icon'><a href=\"".$mem->getURL()."\">" . elgg_view("profile/icon",array('entity' => $mem, 'size' => 'tiny', 'override' => 'true')) . "</a></div>";   
    }
?>
</div>