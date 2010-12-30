<?php
/**
 * User hover wrapper
 */

?>

<div class="avatar_menu_button">
	<img src="<?php echo elgg_get_site_url(); ?>_graphics/spacer.gif" border="0" width="15" height="15" />
</div>

<?php

echo elgg_view_menu('user_hover', $vars);

