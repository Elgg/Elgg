<?php
	/**
	 * Elgg GUID Tool
	 * 
	 * @package ElggGUIDTool
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
	$format = $vars['format'];
	if (!$format) $format = 'opendd';
?>
<div>
<form action="<?php echo $CONFIG->url; ?>action/import/<?php echo $format; ?>" method="post">
	<p><?php echo sprintf(elgg_echo('guidtool:import:desc'), $format); ?></p>
	<?php echo elgg_view('input/longtext', array('internalname' => 'data')); ?>
	<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
</form>
</div>