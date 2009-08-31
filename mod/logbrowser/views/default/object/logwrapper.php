<?php
	/**
	 * Elgg log browser.
	 * 
	 * @package ElggLogBrowser

	 * @author Curverider Ltd

	 * @link http://elgg.com/
	 */

	$entry = $vars['entity']->entry;
	
	$by = get_entity($entry->performed_by_guid);
	$object = get_object_from_log_entry($entry->id);
	
	if (is_callable(array($object, 'getURL')))
		$obj_url = $object->getURL();
	
	//echo elgg_view_listing($icon, $info);
?>
	<table class="log_entry">
		<tr>
			<td class="log_entry_time">
			<?php echo date('r', $entry->time_created ); ?>
			</td>
			<td class="log_entry_user">
			<?php if ($by) {
								echo "<a href=\"".$by->getURL()."\">{$by->name}</a>";
								echo " <a href=\"?user_guid={$by->guid}\">" . $by->guid . "</a>"; 
							} 
							else echo "&nbsp;"; ?>
			<td>
			<td class="log_entry_item">
			<?php 
					if ($obj_url) echo "<a href=\"$obj_url\">";
					echo "{$entry->object_class}";
					if ($obj_url) echo "</a>";
					echo " " . $entry->object_id;
					
			?>
			</td>
			<td class="log_entry_action">
				<div class="log_entry_action_<?php echo $entry->event; ?>">
					<?php echo elgg_echo($entry->event); ?>
				</div>
			</td>
		</tr>
	</table>
	