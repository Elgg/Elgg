<?php
return [
	[
		'type' => 'user',
		'subtype' => 'user',
		'class' => \ElggUser::class,
		'capabilities' => [
			'searchable' => true,
			'river_emittable' => true,
		],
	],
	[
		'type' => 'group',
		'subtype' => 'group',
		'class' => \ElggGroup::class,
	],
	[
		'type' => 'site',
		'subtype' => 'site',
		'class' => \ElggSite::class,
	],
	[
		'type' => 'object',
		'subtype' => 'plugin',
		'class' => \ElggPlugin::class,
	],
	[
		'type' => 'object',
		'subtype' => 'file',
		'class' => \ElggFile::class,
	],
	[
		'type' => 'object',
		'subtype' => 'widget',
		'class' => \ElggWidget::class,
	],
	[
		'type' => 'object',
		'subtype' => 'comment',
		'class' => \ElggComment::class,
		'capabilities' => [
			'commentable' => true,
			'likable' => true,
			'searchable' => true,
			'river_emittable' => true,
		],
	],
	[
		'type' => 'object',
		'subtype' => 'elgg_upgrade',
		'class' => \ElggUpgrade::class,
	],
	[
		'type' => 'object',
		'subtype' => 'admin_notice',
		'class' => \ElggAdminNotice::class,
	],
];
