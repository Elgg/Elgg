<?php
/**
 * Personal notifications form body
 */

echo elgg_view('notifications/subscriptions/personal');
echo elgg_view('notifications/subscriptions/collections');
echo elgg_view('notifications/subscriptions/forminternals');

?>
<div class="elgg-foot">
<?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>
