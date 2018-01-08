<?php

echo elgg_view('page/components/tabs', [
	'tabs' => [
		'inline' => [
			'text' => 'Inline Content',
			'content' => elgg_view('developers/ipsum'),
		],
		'ajax' => [
			'text' => 'Ajax [selected]',
			'href' => 'ajax/view/theme_sandbox/components/tabs/ajax',
			'selected' => true,
			'data-ajax-reload' => false,
			'data-ajax-query' => json_encode([
				'content' => 'This tab was preselected and loaded via ajax',
			]),
		],
		'inline2' => [
			'text' => 'Inline List',
			'content' => elgg_list_entities([
				'types' => 'user',
				'list_type' => 'gallery',
				'gallery_class' => 'elgg-gallery-users',
				'pagination' => false,
			]),
		],
		'ajax2' => [
			'text' => 'Ajax [data-ajax-reload]',
			'href' => 'ajax/view/theme_sandbox/components/tabs/ajax',
			'data-ajax-reload' => true,
			'data-ajax-query' => json_encode([
				'content' => 'This tab reloads every time you click on it',
			]),
		],
		'ajax3' => [
			'text' => 'Ajax [data-ajax-href]',
			'href' => elgg_generate_url('default:river'),
			'data-ajax-href' => 'ajax/view/theme_sandbox/components/tabs/ajax',
			'data-ajax-reload' => false,
			'data-ajax-query' => json_encode([
				'content' => 'If you right click on the tab and open it in a new tab, you will end up on the activity page',
			]),
		],
		'callback' => [
			'text' => 'Click Me',
			'href' => 'ajax/view/theme_sandbox/components/tabs/ajax',
			'data-ajax-query' => json_encode([
				'content' => 'This tab has events attached to it.',
			]),
			'data-ajax-reload' => false,
			'class' => 'theme-sandbox-tab-callback',
		]
	],
]);

?>
<script>
	require(['jquery', 'elgg/ready'], function($) {
		$(document).on('open', '.theme-sandbox-tab-callback', function() {
			$(this).find('a').text('Clicked!');
			$(this).data('target').hide().show('slide', {
				duration: 2000,
				direction: 'right',
				complete: function() {
					alert('Thank you for clicking. We hope you enjoyed the show!');
					$(this).css('display', ''); // .show() adds display property
				}
			});
		});
	});
</script>
