define(function (require) {
	// dependencies
	var AudioPlayer = require('AudioPlayer');
	var $ = require('jquery');
	var elgg = require('elgg');

	// for unique IDs
	var i = 0;

	AudioPlayer.setup(elgg.get_simplecache_url('AudioPlayer.swf'), {width: 290});

	function embed(element) {
		var config = $(element).data().zaudioPlayer;

		if (!element.id) {
			element.id = "zaudio" + i;
			i++;
		}

		AudioPlayer.embed(element.id, config);
	}

	// apply to those in page
	$('[data-zaudio-player]').each(function () {
		embed(this);
	});

	// embed can be used for new elements added to the page
	return embed;
});