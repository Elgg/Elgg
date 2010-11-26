<?php

    /**
	 * Elgg pages widget edit
	 *
	 * @package ElggPages
	 */

if (!isset($vars['entity']->pages_num)) {
	$vars['entity']->pages_num = 4;
}

?>
<p>
	<?php echo elgg_echo("pages:num"); ?>:
	<select name="params[pages_num]">
<?php

for ($i=1; $i<=10; $i++) {
	$selected = '';
	if ($vars['entity']->pages_num == $i) {
		$selected = "selected='selected'";
	}

	echo "	<option value='{$i}' $selected >{$i}</option>\n";
}
?>
	</select>
</p>