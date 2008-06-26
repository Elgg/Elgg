<form action="<?php echo $vars['url']; ?>action/usersettings/save" method="post" >
<?php

	echo elgg_view("usersettings/user");

?>

	<p>
		<input type="submit" value="<?php echo elgg_echo('save'); ?>" />
	</p>

</form>