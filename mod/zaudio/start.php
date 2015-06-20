<?php
 /**
 * ZAudio - a simple mp3 player
 * A simple plugin to play mp3 files on the page
 * http://wpaudioplayer.com/license
 * http://wpaudioplayer.com/standalone
 *
 * @package ElggZAudio
 */

elgg_register_event_handler('init', 'system', 'zaudio_init');

function zaudio_init() {
	elgg_extend_view('css/elgg', 'zaudio/css');

	elgg_define_js('AudioPlayer', [
		'exports' => 'AudioPlayer',
	]);

	// leave library registered for BC
	elgg_register_js('elgg.zaudio', elgg_get_simplecache_url('js/AudioPlayer.js'));
}
