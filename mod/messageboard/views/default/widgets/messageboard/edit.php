<?php

/**
 * Elgg message board widget edit view
 *
 * @package ElggMessageBoard
 */

// default value 
$num_display = 5;
if (isset($vars['entity']->num_display)) {
	$num_display = $vars['entity']->num_display;
}


?>
<p>
	<?php echo elgg_echo("messageboard:num_display"); ?>:
	<select name="params[num_display]">
<?php
$options = array(1,2,3,4,5,6,7,8,9,10);
foreach ($options as $option)  {
	$selected = '';
	if ($num_display == $option) {
		$selected = "selected='selected'";
	}

	echo "	<option value='{$option}' $selected >{$option}</option>\n";
}
?>
	</select>
</p>