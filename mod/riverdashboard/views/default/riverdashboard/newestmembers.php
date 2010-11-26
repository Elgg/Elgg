<?php

/**
 * Elgg riverdashboard newest messbers sidebar box
 * 
 * @package ElggRiverDash
 * 
 */

$newest_members = elgg_get_entities_from_metadata(array('metadata_names' => 'icontime', 'types' => 'user', 'limit' => 18));
	
?>

<div class="sidebarBox">
<h3><?php echo elgg_echo('riverdashboard:recentmembers') ?></h3>
<div class="membersWrapper"><br />
<?php 
	foreach($newest_members as $mem) {
		echo "<div class=\"recentMember\">" . elgg_view("profile/icon", array('entity' => $mem, 'size' => 'tiny')) . "</div>";
	}
?>
<div class="clearfloat"></div>
</div>
</div>