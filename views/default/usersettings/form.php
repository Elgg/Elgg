<form action="<?php echo $vars['url']; ?>action/usersettings/save" method="post" >

	<div class="settings_form">
<?php

	echo elgg_view("usersettings/user");

?>
	</div>

	<p>
		<input type="submit" value="<?php echo elgg_echo('save'); ?>" />
	</p>

</form>