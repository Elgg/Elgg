<?php

/**
 * Elgg Riverdashboard welcome message
 * 
 * @package ElggRiverDash
 * 
 */

$name = '';
if (isloggedin()) {
	$name = get_loggedin_user()->name;
}
	 
?>
<div id="content_area_user_title">
	<h2><?php echo sprintf(elgg_echo('welcome:user'), $name); ?></h2>
</div>