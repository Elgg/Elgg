<?php

	/**
	 * Elgg thewire edit/add page
	 * 
	 * @package ElggTheWire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 */

		$wire_user = get_input('wire_username');
		if (!empty($wire_user)) { $msg = '@' . $wire_user . ' '; } else { $msg = ''; }

?>
<div class="contentWrapper">
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

	<form action="<?php echo $vars['url']; ?>action/thewire/add" method="post" name="noteForm">
			<label>
			<?php
			    $display .= "<br /><textarea name='note' value='' onKeyDown=\"textCounter(document.noteForm.note,document.noteForm.remLen1,140)\" onKeyUp=\"textCounter(document.noteForm.note,document.noteForm.remLen1,140)\" id=\"thewire_large-textarea\">{$msg}</textarea><br />";
                $display .= "<div class='thewire_characters_remaining'><input readonly type=\"text\" name=\"remLen1\" size=\"3\" maxlength=\"3\" value=\"140\" class=\"thewire_characters_remaining_field\">";
                echo $display;
                echo elgg_echo("thewire:charleft") . "</div>";
                echo "<label> " .elgg_echo('access'). "</label>";
			?>
			</label>
		<p>
			<?php
				
				echo elgg_view('input/access', array('internalname' => 'access_id', 'value' => 2));
			
			?>
		</p>
			<input type="hidden" name="method" value="site" />
			<br />
			<input type="submit" value="<?php echo elgg_echo('save'); ?>" id="thewire_submit_button" />
		
	
	</form>
</div>