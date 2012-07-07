<?php
/**
 * Adminshout admin page
 * 
 * @package ElggAdminShout
 */

echo '<p>' . elgg_echo('adminshout:description') . '</p>';
echo elgg_view_form('adminshout/send');
