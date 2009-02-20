<?php

	$contents = array();
	$contents['all'] = 'all';
	if (!empty($vars['config']->registered_entities)) {
		foreach ($vars['config']->registered_entities as $type => $ar) {
			foreach ($vars['config']->registered_entities[$type] as $object) {
				if (!empty($object )) {
					$keyname = 'item:'.$type.':'.$object;
				} else $keyname = 'item:'.$type; 
				$contents[$keyname] = "{$type},{$object}";
			}
		}
	}
	
	$allselect = ''; $friendsselect = ''; $mineselect = '';
	switch($vars['orient']) {
		case '':		$allselect = 'class="selected"';
						break;
		case 'friends':		$friendsselect = 'class="selected"';
						break;
		case 'mine':		$mineselect = 'class="selected"';
						break;
	}

?>

<div class="contentWrapper">
	<div id="elgg_horizontal_tabbed_nav">
		<ul>
			<li <?php echo $allselect; ?> ><a onclick="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/riverdashboard/?type=<?php echo $vars['type']; ?>&content=<?php echo $vars['subtype']; ?>&callback=true'); return false;" href="?type=<?php echo $vars['type']; ?>&content=<?php echo $vars['subtype']; ?>"><?php echo elgg_echo('all'); ?></a></li>
			<li <?php echo $friendsselect; ?> ><a onclick="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/riverdashboard/?type=<?php echo $vars['type']; ?>&display=friends&content=<?php echo $vars['subtype']; ?>&callback=true'); return false;" href="?type=<?php echo $vars['type']; ?>&display=friends&content=<?php echo $vars['subtype']; ?>"><?php echo elgg_echo('friends'); ?></a></li>
			<li <?php echo $mineselect; ?> ><a onclick="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/riverdashboard/?type=<?php echo $vars['type']; ?>&display=mine&content=<?php echo $vars['subtype']; ?>&callback=true'); return false;" href="?type=<?php echo $vars['type']; ?>&display=mine&content=<?php echo $vars['subtype']; ?>"><?php echo elgg_echo('mine'); ?></a></li>
		</ul>
	</div>
	
	<div class="riverdashboard_filtermenu">
		<select name="content" id="content" onchange="javascript:$('#river_container').load('<?php echo $vars['url']; ?>mod/riverdashboard/?callback=true&display='+$('input#display').val() + '&content=' + $('select#content').val());">
			<?php
	
				foreach($contents as $label => $content) {
					if (("{$vars['type']},{$vars['subtype']}" == $content) ||
						(empty($vars['subtype']) && $content == 'all')) {
						$selected = 'selected="selected"';
					} else $selected = '';
					echo "<option value=\"{$content}\" {$selected}>".elgg_echo($label)."</option>";
				}
			
			?>
		</select>
		<input type="hidden" name="display" id="display" value="<?php echo htmlentities($vars['orient']); ?>" />
		<!-- <input type="submit" value="<?php echo elgg_echo('filter'); ?>" /> -->
	</div>
<!-- </div> -->