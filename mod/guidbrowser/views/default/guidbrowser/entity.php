<?php
	/**
	 * Elgg GUID browser
	 * 
	 * @package ElggDevTools
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$guid = $vars['entity_guid'];
	$type = $vars['type'];
	$subtype = $vars['subtype'];
	$full = $vars['full'];
	
?>

<div id="guid-<?php echo $guid; ?>">
	<span onClick="showhide('guid-<?php echo $guid; ?>-full')">
		<table width="100%">
			<tr>
				<td width="50"><b><?php echo $guid; ?></b></td>
				<td><?php echo "$type / $subtype"; ?></td>
				
			</tr>
		</table>
	</span>
	<div id="guid-<?php echo $guid; ?>-full" style="display:none">
		<?php echo $full; ?>
	</div>
</div>