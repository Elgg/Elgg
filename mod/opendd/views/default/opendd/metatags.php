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
	$owner = page_owner_entity();
	
?>
<link rel="alternate" type="application/odd+xml" title="OpenDD" href="<?php echo $CONFIG->url . "pg/opendd/{$owner->username}/activity/opendd" ?>" />