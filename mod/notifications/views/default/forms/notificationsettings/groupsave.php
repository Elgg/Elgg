<?php
/**
 * Elgg notifications groups subscription form
 *
 * @package ElggNotifications
 *
 * @uses $vars['user'] ElggUser
 */

/* @var ElggUser $user */
$user = $vars['user'];

global $NOTIFICATION_HANDLERS;
foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
	$subsbig[$method] = elgg_get_entities_from_relationship(array(
		'relationship' => 'notify' . $method,
		'relationship_guid' => $user->guid,
		'type' => 'group',
		'limit' => false,
	));
	$tmparray = array();
	if ($subsbig[$method]) {
		foreach($subsbig[$method] as $tmpent) {
			$tmparray[] = $tmpent->guid;
		}
	}
	$subsbig[$method] = $tmparray;
}

?>

<div class="elgg-module elgg-module-info">
	<div class="elgg-body">
	<?php
		echo elgg_view('notifications/subscriptions/jsfuncs',$vars);
	?>
		<div>
		<?php
			echo elgg_echo('notifications:subscriptions:groups:description');
		?>
		</div>
<?php

if (isset($vars['groups']) && !empty($vars['groups'])) {

?>
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
<?php
	foreach($vars['groups'] as $group) {
		
		$fields = '';
		$i = 0;
		
		foreach($NOTIFICATION_HANDLERS as $method => $foo) {
			if (in_array($group->guid,$subsbig[$method])) {
				$checked[$method] = 'checked="checked"';
			} else {
				$checked[$method] = '';
			}
			if ($i > 0) {
				$fields .= "<td class=\"spacercolumn\">&nbsp;</td>";
			}
			$fields .= <<< END
				<td class="{$method}togglefield">
				<a border="0" id="{$method}{$group->guid}" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}{$group->guid}');">
				<input type="checkbox" name="{$method}subscriptions[]" id="{$method}checkbox" onclick="adjust{$method}('{$method}{$group->guid}');" value="{$group->guid}" {$checked[$method]} /></a></td>
END;
			$i++;
		}
	
?>
			<tr>
				<td class="namefield">
					<div>
					<?php echo $group->name; ?>
					</div>
				</td>
				<?php echo $fields; ?>
				<td>&nbsp;</td>
			</tr>
<?php
	}
?>
		</table>
<?php
}
	echo '<div class="elgg-foot mtm">';
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $user->guid));
	echo elgg_view('input/submit', array('value' => elgg_echo('save')));
	echo '</div>';
	
?>
	</div>
</div>
