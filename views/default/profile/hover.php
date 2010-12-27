<?php


?>

<div class="avatar_menu_button">
	<img src="<?php echo elgg_get_site_url(); ?>_graphics/spacer.gif" border="0" width="15" height="15" />
</div>

<?php

echo elgg_view_menu('user_hover', $vars);

// @todo change how to add on demand registration of menu items
global $CONFIG;
unset($CONFIG->menus['user_hover']);
