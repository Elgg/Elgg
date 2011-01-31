<?php

/**
 * Elgg riverdashboard navigation view
 *
 * @package ElggRiverDash
 *
 */

$contents = array();
$contents['all'] = 'all';
if (!empty($vars['config']->registered_entities)) {
	foreach ($vars['config']->registered_entities as $type => $ar) {
		if (count($vars['config']->registered_entities[$type])) {
			foreach ($vars['config']->registered_entities[$type] as $subtype) {
				$keyname = 'item:' . $type . ':' . $subtype;
				$contents[$keyname] = "{$type},{$subtype}";
			}
		} else {
			$keyname = 'item:' . $type;
			$contents[$keyname] = "{$type},";
		}
	}
}

$allselect = '';
$friendsselect = '';
$mineselect = '';
switch($vars['orient']) {
	case '':
		$allselect = 'class="selected"';
		break;
	case 'friends':
		$friendsselect = 'class="selected"';
		break;
	case 'mine':
		$mineselect = 'class="selected"';
		break;
}

?>

<?php
if (isloggedin()) {
?>
	<div id="elgg_horizontal_tabbed_nav">
		<ul>
			<li <?php echo $allselect; ?> ><a onclick="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/riverdashboard/?content=<?php echo $vars['type']; ?>,<?php echo $vars['subtype']; ?>&amp;callback=true'); return false;" href="?display="><?php echo elgg_echo('all'); ?></a></li>
			<li <?php echo $friendsselect; ?> ><a onclick="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/riverdashboard/?display=friends&amp;content=<?php echo $vars['type']; ?>,<?php echo $vars['subtype']; ?>&amp;callback=true'); return false;" href="?display=friends"><?php echo elgg_echo('friends'); ?></a></li>
			<li <?php echo $mineselect; ?> ><a onclick="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/riverdashboard/?display=mine&amp;content=<?php echo $vars['type']; ?>,<?php echo $vars['subtype']; ?>&amp;callback=true'); return false;" href="?display=mine"><?php echo elgg_echo('mine'); ?></a></li>
		</ul>
	</div>
<?php
}
?>

	<div class="riverdashboard_filtermenu">
		<select name="content" id="content" onchange="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/riverdashboard/?callback=true&amp;display='+$('input#display').val() + '&amp;content=' + $('select#content').val());">
			<?php

			foreach($contents as $label => $content) {
				if (("{$vars['type']},{$vars['subtype']}" == $content) ||
						(empty($vars['subtype']) && $content == 'all')) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				echo "<option value=\"{$content}\" {$selected}>" . elgg_echo($label) . "</option>";
			}

?>
		</select>
		<input type="hidden" name="display" id="display" value="<?php echo htmlentities($vars['orient']); ?>" />
		<!-- <input type="submit" value="<?php echo elgg_echo('filter'); ?>" /> -->
	</div>
	<!-- </div> -->
