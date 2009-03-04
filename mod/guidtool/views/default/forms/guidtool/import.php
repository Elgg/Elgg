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
	
	
	$formbody = "<div class=\"contentWrapper\"><p>" . sprintf(elgg_echo('guidtool:import:desc'), $format) . "</p>" .
	elgg_view('input/plaintext', array('internalname' => 'data')) . elgg_view('input/submit', array('value' => elgg_echo("save")));
?>
<div>
<?php
	echo elgg_view('input/form', array('body' => $formbody, 'action' => "{$CONFIG->url}action/import/$format"))
?>
</div>
</div>