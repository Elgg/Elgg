<?php
// set default value
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 4;
}
?>
<p>
	<?php echo elgg_echo("file:num_files"); ?>:
	<select name="params[num_display]">
<?php
$options = array(1,2,3,4,5,6,7,8,9,10,15,20);
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

<p>
    <?php echo elgg_echo("file:gallery_list"); ?>?
    <select name="params[gallery_list]">
        <option value="1" <?php if($vars['entity']->gallery_list == 1) echo "SELECTED"; ?>><?php echo elgg_echo("file:list"); ?></option>
	    <option value="2" <?php if($vars['entity']->gallery_list == 2) echo "SELECTED"; ?>><?php echo elgg_echo("file:gallery"); ?></option>
    </select>
</p>