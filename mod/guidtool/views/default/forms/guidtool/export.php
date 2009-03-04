<?php
	/**
	 * Elgg GUID Tool
	 * 
	 * @package ElggGUIDTool
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
	$format = $vars['format'];
	if (!$format) $format = 'opendd';
	
	$entity_guid = get_input('entity_guid');
	
	
?>
<div id="export">
<?php echo elgg_view('output/text', array('value' => htmlentities(file_get_contents($CONFIG->url . "export/$format/$entity_guid/"), ENT_QUOTES, 'UTF-8'))) ?>
</div>