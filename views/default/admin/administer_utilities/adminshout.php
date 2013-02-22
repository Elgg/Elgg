<?php
/**
 * Adminshout admin page
 * 
 * @package ElggAdminShout
 */

echo '<p>' . elgg_echo('adminshout:description') . '</p>';
echo elgg_view_form('admin/mass_mailout/send');
echo '<div id="progressbar" class="hidden adminshout-progressbar"><div class="progress-label">0%</div></div>';
