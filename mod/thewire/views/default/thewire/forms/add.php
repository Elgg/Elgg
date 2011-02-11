<?php

	/**
	 * Elgg thewire edit/add page
	 * 
	 * @package ElggTheWire
	 * 
	 */

		$wire_user = get_input('wire_username');
		if (!empty($wire_user)) { $msg = '@' . $wire_user . ' '; } else { $msg = ''; }

?>
<div class="new_wire_post clearfix">
<h3><?php echo elgg_echo("thewire:doing"); ?></h3>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript" src="<?php echo elgg_get_site_url(); ?>mod/thewire/views/default/thewire/scripts/counter.js"></script>
<form action="<?php echo elgg_get_site_url(); ?>action/thewire/add" method="post" name="new_post">
	<?php
		$action_txt = elgg_echo('post');
		$display .= "<textarea name='new_post_textarea' value='' onKeyDown=\"textCounter(document.new_post.new_post_textarea,document.new_post.remLen1,140)\" onKeyUp=\"textCounter(document.new_post.new_post_textarea,document.new_post.remLen1,140)\">{$msg}</textarea>";
		$display .= "<input type='submit' class='elgg-button-action' value='{$action_txt}' />";
		$display .= "<div class='character_count'><input readonly type=\"text\" name=\"remLen1\" size=\"3\" maxlength=\"3\" value=\"140\">";
		echo $display;
		echo elgg_echo("thewire:charleft") . "</div>";
		echo elgg_view('input/securitytoken');
	?>
	<input type="hidden" name="method" value="site" />
</form>
</div>
<?php echo elgg_view('input/urlshortener'); ?>