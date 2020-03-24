<?php
$icons = [
	'arrow-left',
	'arrow-right',
	'arrow-circle-left',
	'arrow-circle-right',
	'arrows-alt',
	'calendar',
	'check',
	'check-circle',
	'comment',
	'comments',
	'delete',
	'delete-alt',
	'download',
	'eye',
	'facebook',
	'grid',
	'home',
	'image',
	'info',
	'link',
	'list',
	'lock',
	'mail',
	'minus-circle',
	'mobile',
	'paperclip',
	'plus-circle',
	'print',
	'refresh',
	'rss-square',
	'search',
	'settings',
	'settings-alt',
	'share',
	'shopping-cart',
	'star',
	'star-regular',
	'tag',
	'thumbs-down',
	'thumbs-up',
	'thumbtack',
	'times-circle',
	'trash',
	'twitter-square',
	'undo',
	'unlock',
	'user',
	'users',
	'video',
];
?>
<p>
	<?php echo elgg_echo('theme_sandbox:icons:blurb'); ?>
</p>

<ul class="elgg-gallery">
<?php
foreach ($icons as $icon) {
	echo "<li title=\"elgg-icon-$icon\" style=\"margin:10px\">" . elgg_view_icon($icon) . "</li>";
}
?>
</ul>
