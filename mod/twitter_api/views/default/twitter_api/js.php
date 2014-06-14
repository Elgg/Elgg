<?php if (0): ?><script><?php endif; ?>
elgg.deprecated_notice('Use of elgg.twitter_api is deprecated in favor of the elgg/twitter_api AMD module', '1.9');

// add ?persistent to login link
elgg.register_hook_handler('init', 'system', function() {
	$('form.elgg-form-login').each(function () {
		var link = $('.login_with_twitter a', this).get(0),
			$input = $('input[name="persistent"]', this);
		function sync() {
			link.href = link.href.replace(/\?.*/, '') + ($input[0].checked ? '?persistent' : '');
		}
		if (link && $input.length) {
			sync();
			$input.change(sync);
		}
	});
});
