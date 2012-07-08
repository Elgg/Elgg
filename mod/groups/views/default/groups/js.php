<?php
/**
 * Javascript for Groups forms
 *
 * @package ElggGroups
 */
?>

// this adds a class to support IE8 and older
elgg.register_hook_handler('init', 'system', function() {
	// jQuery uses 0-based indexing
	$('#groups-tools').children('li:even').addClass('odd');
});

// Hide group owner dropdown
elgg.register_hook_handler('init', 'system', function() {
	$('.groups-owner-input').hide().each(function(){
		$(this).after($(
			'<?php
				echo elgg_view('output/url', array(
					'text' => elgg_echo('groups:handover'),
					'rel' => 'toggle',
					'class' => 'elgg-button elgg-button-delete',
				));
			?>'
		).click(function(){
			$(this).hide()
			.prev().show();
		}));
	});
});
