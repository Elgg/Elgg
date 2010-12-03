<?php

$contents = array();
$contents['all'] = 'all';
if (!empty($vars['config']->registered_entities)) {
	foreach ($vars['config']->registered_entities as $type => $ar) {
		foreach ($vars['config']->registered_entities[$type] as $object) {
			if ($object != 'helppage'){
				if (!empty($object )) {
					$keyname = 'item:'.$type.':'.$object;
				} else {
					$keyname = 'item:'.$type;
				}
				$contents[$keyname] = "{$type},{$object}";
			}
		}
	}
}
	
$allselect = $friendsselect = $mineselect = $display_option = '';
switch($vars['orient']) {
	case '':
		$allselect = 'class="selected"';
		break;
	case 'friends':
		$friendsselect = 'class="selected"';
		$display_option = '&amp;display=friends';
		break;
	case 'mine':
		$mineselect = 'class="selected"';
		$display_option = '&amp;display=mine';
		break;
}
?>
<div class="riverdashboard-filtermenu"> 
	<?php
		$location_filter = "<select onchange=\"window.open(this.options[this.selectedIndex].value,'_top')\" name=\"file_filter\" class='Notstyled' >";
		$current = get_input('subtype');
		foreach($contents as $label => $content) {
			$get_values = explode(",", $content);
			//select the current filter
			if ($get_values[1] == $current) {
				$selected = "SELECTED";
			}
			//set the drop down filter
			if ($content[0] && $content[1]) {
				$location_filter .= "<option {$selected} class='Nomenuoption' value=\"{$CONFIG->url}pg/activity/?type={$get_values[0]}&subtype={$get_values[1]}{$display_option}\" >" . elgg_echo('Show') . " " . elgg_echo($label) . "</option>";
			}
			//reset selected
			$selected = '';
		}
		$location_filter .= "</select>";
		echo $location_filter;
	?>
	<input type="hidden" name="display" id="display" value="<?php echo htmlentities($vars['orient']); ?>" />
</div>
<div id="riverdashboard-updates" class="clearfix"></div>
