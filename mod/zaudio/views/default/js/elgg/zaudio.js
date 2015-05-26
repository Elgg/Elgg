define(function (require) {
	// dependencies
	var AudioPlayer = require('AudioPlayer');
	var $ = require('jquery');
	var elgg = require('elgg');

	// for unique IDs
	var i = 0;

	AudioPlayer.setup(elgg.get_site_url() + "mod/zaudio/audioplayer/player.20150521.swf", {width: 290});

	return function(element) {
		var config = $(element).data().zaudioPlayer;

		if (!element.id) {
			element.id = "zaudio" + i;
			i++;
		}

		AudioPlayer.embed(element.id, config);
	};
});