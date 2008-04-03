<?php
	global $CONFIG;
	
	$limit = $vars['limit'];
	$offset = $vars['offset'];
	$type = $vars['type'];
	$subtype = $vars['subtype'];
	
	
	$common = "&type=$type&subtype=$subtype";
?>

<div id="guidbrowser_navbar">
	<table width="100%">
		<tr>
			<td align="left"><?php if ($offset>0){?><a href="<?php echo $CONFIG->wwwroot . "mod/guidbrowser/?offset=" . ($offset-$limit) . $common  ?>">Previous</a><?php } ?></td>
			<td align="right"><a href="<?php echo $CONFIG->wwwroot . "mod/guidbrowser/?offset=" . ($offset+$limit) . $common  ?>">Next</a></td>
		</tr>
	</table>
</div>