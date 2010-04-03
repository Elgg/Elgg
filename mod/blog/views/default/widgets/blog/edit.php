<?php
// set default value
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 4;
}
?>
<p>
	<?php echo elgg_echo('blog:numbertodisplay'); ?>:
	<select name="params[num_display]">
<?php

for ($i=1; $i<=10; $i++) {
	$selected = '';
	if ($vars['entity']->num_display == $i) {
		$selected = "selected='selected'";
	}

	echo "	<option value='{$i}' $selected >{$i}</option>\n";
}
?>
	</select>
</p>