define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	
	elgg.register_hook_handler('init', 'system', function init() {
		$('form.elgg-form-login').each(function() {
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

	return {
		init: init
	};
});