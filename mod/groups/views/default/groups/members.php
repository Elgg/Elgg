<?php
	/**
	 * Elgg groups items view.
	 * This is the messageboard, members, pages and latest forums posts. Each plugin will extend the views
	 * 
	 * @package ElggGroups
	 */
	 
?>

<div id="group_members">
<h2><?php echo elgg_echo("groups:members"); ?></h2>

<?php

    $members = $vars['entity']->getMembers(12);
    foreach($members as $mem){
           
        echo "<div class=\"member_icon\"><a href=\"".$mem->getURL()."\">" . elgg_view("profile/icon",array('entity' => $mem, 'size' => 'tiny', 'override' => 'true')) . "</a></div>";   
           
    }

	echo "<div class=\"clearfloat\"></div>";
	$more_url = "{$vars['url']}pg/groups/memberlist/{$vars['entity']->guid}/";
	echo "<div id=\"groups_member_link\"><a href=\"{$more_url}\">" . elgg_echo('groups:members:more') . "</a></div>";

?>
<div class="clearfloat"></div>
</div>