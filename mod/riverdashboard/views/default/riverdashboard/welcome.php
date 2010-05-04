<?php

/**
 * Elgg Riverdashboard welcome message
 * 
 * @package ElggRiverDash
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
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