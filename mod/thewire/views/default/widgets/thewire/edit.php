<?php
// set default value
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 4;
}
?>
<p>
	<?php echo elgg_echo("thewire:num"); ?>
	<select name="params[num_display]">
<?php
$options = array(1,2,3,4,5,6);
foreach ($options as $option)  {
	$selected = '';
	if ($vars['entity']->num_display == $option) {
		$selected = "selected='selected'";
	}

	echo "	<option value='{$option}' $selected >{$option}</option>\n";
}
?>
	</select>
</p>