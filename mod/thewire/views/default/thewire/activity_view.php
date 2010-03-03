<?php

	/**
	 * New wire post view for the activity stream
	 */

	//grab the users latest from the wire
	$latest_wire = elgg_list_entities(array('types' => 'object', 'subtypes' => 'thewire', 'owner_guid' => $_SESSION['user']->getGUID(), 'limit' => 1, 'full_view' => TRUE, 'view_type_toggle' => FALSE, 'pagination' => FALSE));

?>

<script>
function textCounter(field,cntfield,maxlimit) {
    // if too long...trim it!
    if (field.value.length > maxlimit) {
        field.value = field.value.substring(0, maxlimit);
    } else {
        // otherwise, update 'characters left' counter
        cntfield.value = maxlimit - field.value.length;
    }
}
</script>

<div class="sidebarBox">

	<form action="<?php echo $vars['url']; ?>action/thewire/add" method="post" name="noteForm">
			
		<?php
			$display .= "<h3>" . elgg_echo('thewire:newpost') . "</h3><textarea name='note' value='' onKeyDown=\"textCounter(document.noteForm.note,document.noteForm.remLen1,140)\" onKeyUp=\"textCounter(document.noteForm.note,document.noteForm.remLen1,140)\" id=\"thewire_sidebarInputBox\">{$msg}</textarea><br />";
			$display .= "<div class='thewire_characters_remaining'><input readonly type=\"text\" name=\"remLen1\" size=\"3\" maxlength=\"3\" value=\"140\" class=\"thewire_characters_remaining_field\">";
			echo $display;
			echo elgg_echo("thewire:charleft") . "</div>";
		?>
			<input type="hidden" name="method" value="site" />
			<input type="hidden" name="location" value="activity" />
			<input type="hidden" name="access_id" value="2" />
			<input type="submit" value="<?php echo elgg_echo('save'); ?>" id="thewire_submit_button" />
	</form>

	<div class="last_wirepost">
		<?php
			echo $latest_wire;
		?>
	</div>
	
	<img src="<?php echo $vars['url']; ?>mod/thewire/graphics/river_icon_thewire.gif" alt="the wire" align="left" style="margin-right:5px;"/><a href="<?php echo $vars['url']; ?>mod/thewire/everyone.php" />Read the wire</a>

</div>