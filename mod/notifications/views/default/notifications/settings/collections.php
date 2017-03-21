<?php
/**
 * Configure settings that will be applied to new users in friend collections
 *
 * @uses $vars['user'] Subscriber
 */
$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

$methods = elgg_get_notification_methods();
if (empty($methods)) {
	return;
}

$method_options = [];
foreach ($methods as $method) {
	$label = elgg_echo("notification:method:$method");
	$method_options[$label] = $method;
}

$collections = [
	-1 => [
		'description' => elgg_echo('notifications:subscriptions:collections:friends'),
		'methods' => [],
	],
];

$user_collections = get_user_access_collections($user->guid);
if (!empty($user_collections)) {
	foreach ($user_collections as $user_collection) {
		$collection_id = $user_collection->id;
		$description = elgg_echo('notifications:subscriptions:collections:custom', [
			$user_collection->name,
		]);
		$collections[$collection_id] = [
			'description' => $description,
			'methods' => [],
		];
	}
}

foreach ($methods as $method) {
	$metaname = 'collections_notifications_preferences_' . $method;
	$collection_ids = (array) $user->$metaname;
	foreach ($collection_ids as $collection_id) {
		$collections[$collection_id]['methods'][] = $method;
	}
}

foreach ($collections as $collection_id => $collection_opts) {
	?>
	<div class="elgg-subscription-record">
		<div class="elgg-subscription-description">
			<?= $collection_opts['description'] ?>
		</div>
		<?php
		echo elgg_view_input('checkboxes', [
			'name' => "collections[$collection_id]",
			'options' => $method_options,
			'value' => $collection_opts['methods'],
			'align' => 'horizontal',
			'field_class' => 'elgg-subscription-methods',
		]);
		?>
	</div>
	<?php
}
