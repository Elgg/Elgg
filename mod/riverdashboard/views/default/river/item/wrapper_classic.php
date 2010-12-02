<?php
/**
 * Elgg river item wrapper.
 * Wraps all river items.
 *
 * @package Elgg
 */
?>
<div class="river-item riverdashboard">
	<span class="river-item-useravatar">
<?php
echo elgg_view("profile/icon",array('entity' => get_entity($vars['item']->subject_guid), 'size' => 'small'));
?>
	</span>
	<div class="river-item-contents clearfix">
<?php
echo $vars['body'];
?>
	</div>
</div>