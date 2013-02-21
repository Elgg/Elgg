<?php
/**
 * Adminshout admin page
 * 
 * @package ElggAdminShout
 */

elgg_load_js('elgg.admin.shout');

echo '<p>' . elgg_echo('adminshout:description') . '</p>';
echo elgg_view_form('adminshout/send');
echo '<div id="progressbar" class="hidden adminshout-progressbar"><div class="progress-label">0%</div></div>';
