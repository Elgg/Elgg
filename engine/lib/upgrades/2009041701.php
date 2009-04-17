<?php

	global $CONFIG;
	
	/// Activate kses
	/**
	 * Elgg now has kses tag filtering built as a plugin. This needs to be enabled.
	 */	
	enable_plugin('kses', $CONFIG->site->guid);
?>