<div class="sidebar_container clearfix">
<?php
	$newest_members = $vars['members'];
?>
<h3><?php echo elgg_echo('riverdashboard:recentmembers') ?></h3>
<?php 
	foreach($newest_members as $mem){
		echo "<div class='entity-listing-icon'>" . elgg_view("profile/icon",array('entity' => $mem, 'size' => 'small')) . "</div>";
	}
?>
</div>