<?php
/**
 * Elgg admin page layout.  Includes the admin sidebar and the ownerblock (for legacy support)
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$notices_html = '';
if ($notices = elgg_get_admin_notices()) {
	foreach ($notices as $notice) {
		$notices_html .= elgg_view_entity($notice);
	}
}

?>
<div id="elgg_content" class="clearfloat sidebar">
	<div id="elgg_sidebar">
		<?php
			echo elgg_view('admin/components/sidemenu', $vars);
			echo '<hr />';
			echo elgg_view('page_elements/owner_block');
		?>
	</div>

	<div id="elgg_page_contents" class="clearfloat">
		<?php
			if ($notices) {
				echo "<div class=\"admin_notices\">$notices_html</div>";
			}
			echo $vars['content'];
		?>
	</div>
</div>
