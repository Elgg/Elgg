<?php
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