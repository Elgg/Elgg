define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');

	$('.no-data-echo').text(elgg.echo('no-data-proof'));

	(new Ajax).action('no-data-demo').done(function (value) {
		if (value.cheese) {
			elgg.system_message('&#128077;');
		} else {
			elgg.register_error('&#128078;');
		}
	});
});
