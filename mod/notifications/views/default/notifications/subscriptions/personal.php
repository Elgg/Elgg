<?php

global $NOTIFICATION_HANDLERS;

?>
<div class="notification_personal">
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3>
			<?php echo elgg_echo('notifications:subscriptions:personal:title'); ?>
		</h3>
	</div>
</div>
<table id="notificationstable" cellspacing="0" cellpadding="4" width="100%">
	<tr>
		<td>&nbsp;</td>
<?php
$i = 0; 
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
	<tr>
		<td class="namefield">
			<p>
				<?php echo elgg_echo('notifications:subscriptions:personal:description') ?>
			</p>
		</td>

<?php

$fields = '';
$i = 0;
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	if ($notification_settings = get_user_notification_settings(elgg_get_logged_in_user_guid())) {
		if ($notification_settings->$method) {
			$personalchecked[$method] = 'checked="checked"';
		} else {
			$personalchecked[$method] = '';
		}
	}
	if ($i > 0) {
		$fields .= "<td class='spacercolumn'>&nbsp;</td>";
	}
	$fields .= <<< END
		<td class="{$method}togglefield">
		<a  border="0" id="{$method}personal" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}personal');">
		<input type="checkbox" name="{$method}personal" id="{$method}checkbox" onclick="adjust{$method}('{$method}personal');" value="1" {$personalchecked[$method]} /></a></td>
END;
	$i++;
}
echo $fields;

?>

		<td>&nbsp;</td>
	</tr>
</table>
</div>