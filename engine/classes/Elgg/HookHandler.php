<?php
namespace Elgg;

/**
 * Defines a plugin hook handler
 *
 * @since 2.0.0
 */
interface HookHandler {

	/**
	 * Handle the plugin hook
	 *
	 * @param Hook $hook The plugin hook object
	 *
	 * @return mixed if not null, this will become the new value of the hook
	 */
	public function __invoke(Hook $hook);
}
