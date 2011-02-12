<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
	
	function setCollection(members, method, id) {
		for ( var i in members ) {
			var checked = $('#' + method + 'collections' + id).children("INPUT[type='checkbox']").attr('checked');
			if ($("#"+method+members[i]).children("INPUT[type='checkbox']").attr('checked') != checked) {  
				$("#"+method+members[i]).children("INPUT[type='checkbox']").attr('checked', checked);
				functioncall = 'adjust' + method + '_alt("'+method+members[i]+'");';
				eval(functioncall);
			}
		} 
	}
	
</script>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3>
			<?php echo elgg_echo('notifications:subscriptions:friends:title'); ?>
		</h3>
	</div>
<p class="margin-none">
	<?php echo elgg_echo('notifications:subscriptions:friends:description'); ?>
</p>
<table id="notificationstable" cellspacing="0" cellpadding="4" width="100%">
	<tr>
		<td>&nbsp;</td>
<?php
	$i = 0; 
	global $NOTIFICATION_HANDLERS;
	foreach($NOTIFICATION_HANDLERS as $method => $foo) {
		if ($i > 0) {
			echo "<td class='spacercolumn'>&nbsp;</td>";
		}
?>
	<td class="<?php echo $method; ?>togglefield"><?php echo elgg_echo('notification:method:'.$method); ?></td>
<?php
		$i++;
	}
?>
		<td>&nbsp;</td>
	</tr>
<?php
	$members = array();
	if ($friends = get_user_friends(elgg_get_logged_in_user_guid(), '', 9999, 0)) {
		foreach($friends as $friend) {
			$members[] = $friend->guid;
		}
	}
	$memberno = sizeof($members);
	$members = implode(',',$members);

?>
	<tr>
		<td class="namefield">
			<p>
				<?php echo elgg_echo('friends:all'); ?> (<?php echo $memberno; ?>)
			</p>
		</td>
<?php

	$fields = '';
	$i = 0;
	foreach($NOTIFICATION_HANDLERS as $method => $foo) {
		$metaname = 'collections_notifications_preferences_' . $method;
		if ($collections_preferences = elgg_get_logged_in_user_entity()->$metaname) {
			if (!empty($collections_preferences) && !is_array($collections_preferences)) {
				$collections_preferences = array($collections_preferences);
			}
			if (is_array($collections_preferences)) {
				if (in_array(-1,$collections_preferences)) {
					$collectionschecked[$method] = 'checked="checked"';
				} else {
					$collectionschecked[$method] = '';
				}
			}
		}
		if ($i > 0) {
			$fields .= "<td class='spacercolumn'>&nbsp;</td>";
		}
		$fields .= <<< END
			<td class="{$method}togglefield">
			<a border="0" id="{$method}collections-1" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}collections-1'); setCollection([{$members}],'{$method}',-1);">
			<input type="checkbox" name="{$method}collections[]" id="{$method}checkbox" onclick="adjust{$method}('{$method}collections-1');" value="-1" {$collectionschecked[$method]} /></a></td>
END;
		$i++;
	}
	echo $fields;

?>
		<td>&nbsp;</td>
	</tr>
<?php
/*
	@todo
	collections removed from notifications - they are no longer used and will be replaced with shared access collections
	
	if ($collections = get_user_access_collections(elgg_get_logged_in_user_guid())) {
		foreach($collections as $collection) {
			$members = get_members_of_access_collection($collection->id, true);
			$memberno = sizeof($members);
			$members = implode(',', $members);

?>
	<tr>
		<td class="namefield">
			<p>
				<?php echo $collection->name; ?> (<?php echo $memberno; ?>)
			</p>
		</td>

<?php

			$fields = '';
			$i = 0;
			foreach($NOTIFICATION_HANDLERS as $method => $foo) {
				$metaname = 'collections_notifications_preferences_' . $method;
				if ($collections_preferences = elgg_get_logged_in_user_entity()->$metaname) {
					if (!empty($collections_preferences) && !is_array($collections_preferences)) {
						$collections_preferences = array($collections_preferences);
					}
					if (is_array($collections_preferences)) {
						if (in_array($collection->id,$collections_preferences)) {
							$collectionschecked[$method] = 'checked="checked"';
						} else {
							$collectionschecked[$method] = '';
						}
					}
				}
				if ($i > 0) {
					$fields .= "<td class='spacercolumn'>&nbsp;</td>";
				}
				$fields .= <<< END
					<td class="{$method}togglefield">
					<a border="0" id="{$method}collections{$collection->id}" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}collections{$collection->id}'); setCollection([{$members}],'{$method}',{$collection->id});">
					<input type="checkbox" name="{$method}collections[]" id="{$method}checkbox" onclick="adjust{$method}('{$method}collections{$collection->id}');" value="{$collection->id}" {$collectionschecked[$method]} /></a></td>
END;
				$i++;
			}
			echo $fields;

?>

		<td>&nbsp;</td>
	</tr>
<?php

		}
	}

*/
?>
</table>
</div>
