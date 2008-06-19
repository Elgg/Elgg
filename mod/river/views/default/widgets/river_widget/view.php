<?php
	/**
	 * View the widget
	 * 
	 * @package ElggRiver
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$owner = page_owner_entity();
?>
<h1><?php echo sprintf(elgg_echo('river:widget:title'), $owner->name . "'s") ?></h1>
<?php
	echo elgg_view_river($owner->guid);
?>