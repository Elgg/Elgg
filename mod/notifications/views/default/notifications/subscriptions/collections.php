<?php

	if ($collections = get_user_access_collections($vars['user']->guid)) {
		global $NOTIFICATION_HANDLERS;
?>
<div class="notification_personal">
<table id="notificationstable" cellspacing="0" cellpadding="4" border="1" width="100%">
  <tr>
    <td>&nbsp;</td>
<?php
	$i = 0; 
	foreach($NOTIFICATION_HANDLERS as $method => $foo) {
		if ($i > 0)
			echo "<td class=\"spacercolumn\">&nbsp;</td>";
?>
	<td class="<?php echo $method; ?>togglefield"><?php echo elgg_echo('notification:method:'.$method); ?></td>
<?php
		$i++;
	}
?>
    <td>&nbsp;</td>
  </tr>
<?php

	foreach($collections as $collection) {

?>
  <tr>
    <td class="namefield">
    	<p>
    		<?php echo $collection->name; ?>
    	</p>
    	
    </td>
    
<?php

		$fields = '';
		$i = 0;
			foreach($NOTIFICATION_HANDLERS as $method => $foo) {
				if ($i > 0) $fields .= "<td class=\"spacercolumn\">&nbsp;</td>";
				$toggle = elgg_echo('toggle');
				$fields .= <<< END
				    <td class="{$method}togglefield">
				    	<a href="#" class="{$method}toggleOn"></a>
				    </td>
END;
				$i++;
			}
		echo $fields;

?>
  
    <td>&nbsp;</td>
  </tr>
<?php

	}

?>
</table>
</div>
<?php
		
	}

?>