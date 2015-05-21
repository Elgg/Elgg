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
		'src' => 'mod/zaudio/audioplayer/audio-player.20150521.min.js',
		'exports' => 'AudioPlayer',
	]);

	// leave library registered for BC
	$js_url = elgg_get_site_url() . 'mod/zaudio/audioplayer/audio-player.20150521.min.js';
	elgg_register_js('elgg.zaudio', $js_url);
}
