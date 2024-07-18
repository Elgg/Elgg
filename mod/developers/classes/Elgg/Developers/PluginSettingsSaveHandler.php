<?php

namespace Elgg\Developers;

/**
 * Callback saving plugin settings
 *
 * @since 5.1
 */
class PluginSettingsSaveHandler {

	/**
	 * Saves config values on settings save
	 *
	 * @param \Elgg\Event $event 'action:validate', 'plugins/settings/save'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event): void {
		if (get_input('plugin_id') !== 'developers') {
			// different plugin
			return;
		}
		
		if (!elgg()->config->hasInitialValue('simplecache_enabled')) {
			if (get_input('simple_cache')) {
				_elgg_services()->simpleCache->enable();
			} else {
				_elgg_services()->simpleCache->disable();
			}
		}
		
		elgg_save_config('system_cache_enabled', get_input('system_cache'));
		
		if (!elgg()->config->hasInitialValue('debug')) {
			$debug = get_input('debug_level');
			if ($debug) {
				elgg_save_config('debug', $debug);
			} else {
				elgg_remove_config('debug');
			}
		}
	}
}
