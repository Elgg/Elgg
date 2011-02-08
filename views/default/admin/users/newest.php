<?php
// newest users
$users = elgg_list_entities(array(
	'type' => 'user',
	'subtype'=> null,
	'full_view' => FALSE
));

?>

<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('admin:users:newest'); ?></h3>
	</div>
	<div class="elgg-body">
		<?php echo $users; ?>
	</div>
</div>
