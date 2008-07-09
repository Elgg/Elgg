<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
	$url = $vars['feed_url'];
	
	
?>
<div id="feed_icon">
<a href="<?php echo $url; ?>">
<img src="<?php echo $CONFIG->url . "mod/opendd/graphics/defaulttiny.jpg"; ?>" border="0" />
</a>
</div>